<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\Interview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Only allow employers.
        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        // 1. Job stats
        $totalJobs = $user->jobs()->count();
        $activeJobs = $user->jobs()->where('status', 'active')->count();

        // 2. Applications on user's jobs
        $jobIds = $user->jobs()->pluck('id');
        $totalApplicants = Application::whereIn('job_id', $jobIds)->count();

        $today = Carbon::today();
        $applicantsToday = Application::whereIn('job_id', $jobIds)
            ->whereDate('created_at', $today)
            ->count();

        // 3. Interviews scheduled today
        $interviewsToday = Interview::whereIn('application_id', function ($q) use ($jobIds) {
            $q->select('id')->from('applications')->whereIn('job_id', $jobIds);
        })
            ->whereDate('scheduled_at', $today)
            ->count();

        // 4. Recent Applicants (latest 5)
        $recentApplicants = Application::with(['job', 'jobseeker'])
            ->whereIn('job_id', $jobIds)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // 5. Latest Jobs (latest 5)
        $latestJobs = $user->jobs()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Fallback to default (empty) values if DB is empty
        $dashboardData = [
            'totalJobs' => $totalJobs ?? 0,
            'activeJobs' => $activeJobs ?? 0,
            'totalApplicants' => $totalApplicants ?? 0,
            'applicantsToday' => $applicantsToday ?? 0,
            'interviewsToday' => $interviewsToday ?? 0,
            'recentApplicants' => $recentApplicants ?? collect(),
            'latestJobs' => $latestJobs ?? collect(),
        ];

        return view('employer.dashboard', $dashboardData);
    }
}
