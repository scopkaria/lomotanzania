<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TripadvisorReview;
use App\Services\TripAdvisorScraper;
use Illuminate\Http\Request;

class TripadvisorReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = TripadvisorReview::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reviewer_name', 'like', "%{$search}%")
                  ->orWhere('review_text', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'published') {
            $query->where('published', true);
        } elseif ($request->input('status') === 'pending') {
            $query->where('published', false);
        }

        $reviews = $query->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 15))
            ->withQueryString();

        $settings = Setting::first();

        return view('admin.tripadvisor.index', compact('reviews', 'settings'));
    }

    public function pull()
    {
        $settings = Setting::first();
        $url = $settings?->tripadvisor_url;

        if (empty($url)) {
            return back()->with('error', 'Please set your TripAdvisor URL in Settings first.');
        }

        $scraper = new TripAdvisorScraper();
        $result = $scraper->scrape($url);

        if (!empty($result['errors'])) {
            return back()->with('error', implode(' ', $result['errors']));
        }

        if ($result['new'] === 0) {
            return back()->with('info', "Checked {$result['total']} reviews — no new reviews found.");
        }

        return back()->with('success', "Pulled {$result['new']} new review(s) from TripAdvisor!");
    }

    public function togglePublish(TripadvisorReview $review)
    {
        $review->update(['published' => !$review->published]);

        $status = $review->published ? 'published' : 'unpublished';
        return back()->with('success', "Review by {$review->reviewer_name} {$status}.");
    }

    public function updateOrder(Request $request, TripadvisorReview $review)
    {
        $request->validate(['display_order' => 'required|integer|min:0']);
        $review->update(['display_order' => $request->input('display_order')]);

        return back()->with('success', 'Display order updated.');
    }

    public function destroy(TripadvisorReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:tripadvisor_reviews,id',
        ]);

        $ids = $request->input('ids');
        $action = $request->input('action');

        match ($action) {
            'publish'   => TripadvisorReview::whereIn('id', $ids)->update(['published' => true]),
            'unpublish' => TripadvisorReview::whereIn('id', $ids)->update(['published' => false]),
            'delete'    => TripadvisorReview::whereIn('id', $ids)->delete(),
        };

        $count = count($ids);
        return back()->with('success', "{$count} review(s) {$action}ed.");
    }
}
