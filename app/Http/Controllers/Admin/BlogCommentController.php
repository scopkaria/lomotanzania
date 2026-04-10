<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogComment::with('post')->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $comments = $query->paginate(25)->withQueryString();
        $pendingCount = BlogComment::where('status', 'pending')->count();

        return view('admin.blog-comments.index', compact('comments', 'pendingCount'));
    }

    public function approve(BlogComment $blogComment)
    {
        $blogComment->update(['status' => 'approved']);
        return back()->with('success', 'Comment approved.');
    }

    public function reject(BlogComment $blogComment)
    {
        $blogComment->update(['status' => 'rejected']);
        return back()->with('success', 'Comment rejected.');
    }

    public function destroy(BlogComment $blogComment)
    {
        $blogComment->delete();
        return back()->with('success', 'Comment deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:blog_comments,id',
        ]);

        $ids = $request->ids;

        match ($request->action) {
            'approve' => BlogComment::whereIn('id', $ids)->update(['status' => 'approved']),
            'reject'  => BlogComment::whereIn('id', $ids)->update(['status' => 'rejected']),
            'delete'  => BlogComment::whereIn('id', $ids)->delete(),
        };

        return back()->with('success', count($ids) . ' comment(s) updated.');
    }
}
