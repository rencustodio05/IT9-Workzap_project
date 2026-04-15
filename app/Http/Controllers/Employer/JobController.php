<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('user_id', Auth::id());

        // 🔎 SEARCH
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 🟢 STATUS FILTER (ACTIVE / CLOSED / ALL)
        if ($request->filled('status')) {
            $status = $request->status === 'closed' ? 'inactive' : $request->status;
            $query->where('status', $status);
        }

        // 📅 DATE SORT
        if ($request->filled('date')) {
            $request->date === 'oldest'
                ? $query->oldest()
                : $query->latest();
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(10);

        // 🔥 COUNTS FOR TABS
        $base = Job::where('user_id', Auth::id());

        $allCount = (clone $base)->count();
        $activeCount = (clone $base)->where('status', 'active')->count();
        $closedCount = (clone $base)->where('status', 'inactive')->count();

        return view('employer.jobs.index', compact(
            'jobs',
            'allCount',
            'activeCount',
            'closedCount'
        ));
    }

    public function create()
    {
        return view('employer.jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'status' => 'required|in:active,inactive,closed',
            'type' => 'nullable|array'
        ]);

        $status = $request->status === 'closed' ? 'inactive' : $request->status;

        Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'status' => $status,
            'type' => $request->type ? implode(',', $request->type) : null,
        ]);

        return redirect()->route('jobs.index')
            ->with('success', 'Job posted successfully!');
    }

    public function show(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        $job->increment('views');

        return view('employer.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'status' => 'required|in:active,inactive,closed',
            'type' => 'nullable|array',
        ]);

        $status = $request->status === 'closed' ? 'inactive' : $request->status;

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'status' => $status,
            'type' => $request->type ? implode(',', $request->type) : null,
        ]);

        return redirect()->route('jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully!');
    }
}
