<?php

namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * 📄 Browse Jobs
     */
    public function index(Request $request)
    {
        $jobs = Job::with('employer')
            ->where('status', 'active');

        // 🔍 SEARCH TITLE
        if ($request->search) {
            $jobs->where('title', 'like', '%' . $request->search . '%');
        }

        // 📍 LOCATION
        if ($request->location) {
            $jobs->where('location', 'like', '%' . $request->location . '%');
        }

        // 💼 TYPE (SAFE FIX - no DB error)
        if ($request->type) {
            $jobs->where('type', $request->type);
        }

        // 💰 SALARY FILTER
        if ($request->min_salary) {
            $jobs->where('salary_min', '>=', $request->min_salary);
        }

        if ($request->max_salary) {
            $jobs->where('salary_max', '<=', $request->max_salary);
        }

        $jobs = $jobs->latest()->get();

        // 🟢 applied jobs
        $appliedJobs = Application::where('user_id', Auth::id())
            ->pluck('job_id')
            ->toArray();

        return view('jobseeker.jobs.index', compact('jobs', 'appliedJobs'));
    }

    /**
     * 🔍 Single Job View
     */
    public function show($id)
    {
        $job = Job::with('employer')
            ->where('status', 'active')
            ->findOrFail($id);

        return view('jobseeker.jobs.show', compact('job'));
    }

    /**
     * 🟢 Apply Job
     */
    public function apply($id)
    {
        $exists = Application::where('job_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already applied.');
        }

        Application::create([
            'job_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Applied successfully!');
    }

    /**
     * 🔎 Predictive Search
     */
    public function suggest(Request $request)
    {
        $query = $request->q;

        if (!$query) {
            return response()->json([]);
        }

        $suggestions = Job::where('status', 'active')
            ->where('title', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('title');

        return response()->json($suggestions);
    }
}
