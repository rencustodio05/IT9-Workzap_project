<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $jobs = Job::with('employer:id,first_name,last_name,email')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['open', 'closed'], true), function ($query) use ($status) {
                $query->where('status', $status === 'open' ? 'active' : 'inactive');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs', 'search', 'status'));
    }

    public function create()
    {
        $employers = User::where('role', 'employer')->where('is_active', true)->orderBy('first_name')->get();

        return view('admin.jobs.create', compact('employers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:open,closed'],
        ]);

        Job::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'location' => $validated['location'],
            'status' => $validated['status'] === 'open' ? 'active' : 'inactive',
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    public function show(Job $job)
    {
        $job->load('employer:id,first_name,last_name,email');

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $employers = User::where('role', 'employer')->orderBy('first_name')->get();

        return view('admin.jobs.edit', compact('job', 'employers'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:open,closed'],
        ]);

        $job->update([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'location' => $validated['location'],
            'status' => $validated['status'] === 'open' ? 'active' : 'inactive',
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job moved to archive successfully.');
    }
}
