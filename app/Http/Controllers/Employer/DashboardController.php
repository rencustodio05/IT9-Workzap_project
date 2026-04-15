<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        $jobIds = Job::where('user_id', $user->id)->pluck('id');

        $totalJobs = Job::where('user_id', $user->id)->count();
        $activeJobs = Job::where('user_id', $user->id)->where('status', 'active')->count();

        $totalApplicants = Application::whereIn('job_id', $jobIds)->count();
        $applicantsToday = Application::whereIn('job_id', $jobIds)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $interviewsToday = Interview::whereIn('job_id', $jobIds)
            ->whereDate('scheduled_at', now()->toDateString())
            ->count();

        $recentApplicants = Application::with(['job', 'jobseeker'])
            ->whereIn('job_id', $jobIds)
            ->latest()
            ->take(5)
            ->get();

        $latestJobs = Job::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('employer.dashboard', compact(
            'totalJobs',
            'activeJobs',
            'totalApplicants',
            'applicantsToday',
            'interviewsToday',
            'recentApplicants',
            'latestJobs'
        ));
    }
}
