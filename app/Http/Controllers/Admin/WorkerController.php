<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = User::where('role', 'worker')
            ->with('department')
            ->withCount(['chatSessions' => fn ($q) => $q->where('status', 'active')])
            ->orderByDesc('created_at')
            ->paginate(25);

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.workers.index', compact('workers', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.workers.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'bio' => 'nullable|string|max:255',
        ]);

        $validated['role'] = 'worker';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.workers.index')
            ->with('success', 'Worker account created successfully.');
    }

    public function edit(User $worker)
    {
        abort_unless($worker->role === 'worker', 404);
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.workers.edit', compact('worker', 'departments'));
    }

    public function update(Request $request, User $worker)
    {
        abort_unless($worker->role === 'worker', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $worker->id,
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'bio' => 'nullable|string|max:255',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $worker->update($validated);

        return redirect()->route('admin.workers.index')
            ->with('success', 'Worker updated successfully.');
    }

    public function destroy(User $worker)
    {
        abort_unless($worker->role === 'worker', 404);
        $worker->delete();

        return redirect()->route('admin.workers.index')
            ->with('success', 'Worker deleted.');
    }

    // Department CRUD
    public function departments()
    {
        $departments = Department::withCount('workers')->orderBy('name')->get();
        return view('admin.workers.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name',
            'description' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        Department::create($validated);

        return back()->with('success', 'Department created.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name,' . $department->id,
            'description' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return back()->with('success', 'Department updated.');
    }

    public function destroyDepartment(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }
}
