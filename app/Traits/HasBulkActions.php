<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasBulkActions
{
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
            'action' => 'required|string',
        ]);

        $modelClass = $this->bulkModel();
        $allowed    = $this->allowedBulkActions();

        if (! in_array($request->action, $allowed, true)) {
            return back()->with('error', 'Invalid bulk action.');
        }

        switch ($request->action) {
            case 'delete':
                $count = $modelClass::whereIn('id', $request->ids)->delete();
                return back()->with('success', $count . ' item(s) deleted.');

            case 'publish':
                $count = $modelClass::whereIn('id', $request->ids)->update(['status' => 'published']);
                return back()->with('success', $count . ' item(s) published.');

            case 'draft':
                $count = $modelClass::whereIn('id', $request->ids)->update(['status' => 'draft']);
                return back()->with('success', $count . ' item(s) set to draft.');

            case 'approve':
                $count = $modelClass::whereIn('id', $request->ids)->update(['approved' => true]);
                return back()->with('success', $count . ' item(s) approved.');

            case 'activate':
                $count = $modelClass::whereIn('id', $request->ids)->update(['is_active' => true]);
                return back()->with('success', $count . ' item(s) activated.');

            case 'deactivate':
                $count = $modelClass::whereIn('id', $request->ids)->update(['is_active' => false]);
                return back()->with('success', $count . ' item(s) deactivated.');

            case 'confirm':
                $count = $modelClass::whereIn('id', $request->ids)->update(['status' => 'confirmed']);
                return back()->with('success', $count . ' booking(s) confirmed.');

            case 'cancel':
                $count = $modelClass::whereIn('id', $request->ids)->update(['status' => 'cancelled']);
                return back()->with('success', $count . ' item(s) cancelled.');
        }

        return back();
    }

    abstract protected function bulkModel(): string;

    protected function allowedBulkActions(): array
    {
        return ['delete'];
    }
}
