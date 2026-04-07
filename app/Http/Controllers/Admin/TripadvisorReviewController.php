<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TripadvisorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function create()
    {
        return view('admin.tripadvisor.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewer_name'     => 'required|string|max:100',
            'reviewer_location' => 'nullable|string|max:255',
            'title'             => 'nullable|string|max:200',
            'review_text'       => 'required|string',
            'rating'            => 'required|integer|min:1|max:5',
            'review_date'       => 'nullable|date',
            'trip_type'         => 'nullable|string|max:255',
            'published'         => 'boolean',
            'display_order'     => 'integer|min:0',
        ]);

        $validated['tripadvisor_id'] = 'manual_' . Str::random(16);
        $validated['published'] = $request->boolean('published');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        TripadvisorReview::create($validated);

        return redirect()->route('admin.tripadvisor.index')
            ->with('success', 'Review added successfully!');
    }

    public function edit(TripadvisorReview $review)
    {
        return view('admin.tripadvisor.form', compact('review'));
    }

    public function update(Request $request, TripadvisorReview $review)
    {
        $validated = $request->validate([
            'reviewer_name'     => 'required|string|max:100',
            'reviewer_location' => 'nullable|string|max:255',
            'title'             => 'nullable|string|max:200',
            'review_text'       => 'required|string',
            'rating'            => 'required|integer|min:1|max:5',
            'review_date'       => 'nullable|date',
            'trip_type'         => 'nullable|string|max:255',
            'published'         => 'boolean',
            'display_order'     => 'integer|min:0',
        ]);

        $validated['published'] = $request->boolean('published');

        $review->update($validated);

        return redirect()->route('admin.tripadvisor.index')
            ->with('success', 'Review updated successfully!');
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
