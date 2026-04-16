<?php

namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * 📄 Browse Jobs
     */
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'jobseeker') {
            abort(403, 'Unauthorized');
        }

        /** @var User $user */
        $user = Auth::user();

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

        $applicationStatuses = Application::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->unique('job_id')
            ->pluck('status', 'job_id')
            ->toArray();

        $activeApplications = Application::with('job:id,type')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->get();

        $activeApplicationsCount = $activeApplications->count();

        $activeHasFullTime = $activeApplications->contains(function ($application) {
            $jobTypes = collect(explode(',', strtolower($application->job->type ?? '')))
                ->map(fn($type) => trim($type));

            return $jobTypes->contains('full-time');
        });

        $savedJobs = $user->savedJobs()->pluck('jobs.id')->toArray();

        return view('jobseeker.jobs.index', compact('jobs', 'applicationStatuses', 'savedJobs', 'activeApplicationsCount', 'activeHasFullTime'));
    }

    /**
     * 🔍 Single Job View
     */
    public function show(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'jobseeker') {
            abort(403, 'Unauthorized');
        }

        $application = null;

        if ($request->filled('application_id')) {
            $application = Application::with(['job.employer', 'interview'])
                ->where('id', $request->integer('application_id'))
                ->where('user_id', Auth::id())
                ->where('job_id', $id)
                ->firstOrFail();

            $job = $application->job;
        } else {
            $job = Job::with('employer')
                ->where('status', 'active')
                ->findOrFail($id);
        }

        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->exists();

        $activeApplications = Application::with('job:id,type')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->get();

        $activeApplicationsCount = $activeApplications->count();

        $activeHasFullTime = $activeApplications->contains(function ($application) {
            $jobTypes = collect(explode(',', strtolower($application->job->type ?? '')))
                ->map(fn($type) => trim($type));

            return $jobTypes->contains('full-time');
        });

        $jobTypes = collect(explode(',', strtolower($job->type ?? '')))
            ->map(fn($type) => trim($type));

        $isFullTimeJob = $jobTypes->contains('full-time');
        $limitReached = $activeApplicationsCount >= 2;
        $fullTimeLimitReached = $activeHasFullTime && $isFullTimeJob;
        $applyBlockedByRule = !$alreadyApplied && ($limitReached || $fullTimeLimitReached);
        $applyRestrictionMessage = 'You cannot apply for more than 1 Full-Time job. Part-Time jobs are allowed up to 2.';

        return view('jobseeker.jobs.show', compact('job', 'application', 'alreadyApplied', 'applyBlockedByRule', 'applyRestrictionMessage'));
    }

    /**
     * 🟢 Apply Job
     */
    public function apply($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'jobseeker') {
            abort(403, 'Unauthorized');
        }

        $job = Job::where('status', 'active')->findOrFail($id);

        $jobTypes = collect(explode(',', strtolower($job->type ?? '')))
            ->map(fn($type) => trim($type));

        $isFullTimeJob = $jobTypes->contains('full-time');

        $activeApplications = Application::with('job:id,type')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->get();

        $activeApplicationsCount = $activeApplications->count();

        $activeHasFullTime = $activeApplications->contains(function ($application) {
            $types = collect(explode(',', strtolower($application->job->type ?? '')))
                ->map(fn($type) => trim($type));

            return $types->contains('full-time');
        });

        if ($activeApplicationsCount >= 2 || ($isFullTimeJob && $activeHasFullTime)) {
            return back()->with('error', 'You cannot apply for more than 1 Full-Time job. Part-Time jobs are allowed up to 2.');
        }

        $exists = Application::where('job_id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already applied.');
        }

        $oldApplications = Application::where('job_id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->get();

        foreach ($oldApplications as $oldApp) {
            Interview::where('application_id', $oldApp->id)->delete();
        }

        Application::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Applied successfully!');
    }
}
