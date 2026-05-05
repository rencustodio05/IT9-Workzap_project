<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $recommendedJobs = Job::with('employer')
            ->where('status', 'active')
            ->whereDoesntHave('applications', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'interview', 'hired']);
            })
            ->orderBy('id')
            ->limit(3)
            ->get();

        $recentApplications = Application::with('job')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'interview', 'hired'])
            ->orderBy('id')
            ->limit(3)
            ->get();

        $totalAppliedJobs = Application::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $latestApplicationForDetails = Application::where('user_id', $user->id)
            ->latest()
            ->first();

        $progressApplications = Application::where('user_id', $user->id)
            ->where('status', 'interview')
            ->count();

        $hiredJobsCount = Application::where('user_id', $user->id)
            ->where('status', 'hired')
            ->count();

        $latestHiredApplication = Application::where('user_id', $user->id)
            ->where('status', 'hired')
            ->latest()
            ->first();

        $now = now();

        $upcomingInterviews = Interview::whereHas('application', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'interview']);
        })
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'scheduled');
            })
            ->get()
            ->map(function ($interview) {
                $interview->nearest_at = $interview->scheduled_at;

                return $interview;
            })
            ->filter(fn($interview) => $interview->nearest_at && $interview->nearest_at->greaterThanOrEqualTo($now))
            ->sortBy('nearest_at')
            ->values();

        $upcomingInterviewCount = $upcomingInterviews->count();
        $nearestInterview = $upcomingInterviews->first();

        $nearestInterviewSchedule = null;

        if ($nearestInterview && $nearestInterview->nearest_at) {
            $nearestAt = $nearestInterview->nearest_at;

            $nearestInterviewSchedule = $nearestAt->isTomorrow()
                ? 'Tomorrow at ' . $nearestAt->format('g:i A')
                : $nearestAt->format('M d, Y g:i A');
        }

        $savedJobs = $user->savedJobs()
            ->with('employer')
            ->latest('saved_jobs.created_at')
            ->take(4)
            ->get();

        return view('applicant.dashboard', compact(
            'recommendedJobs',
            'recentApplications',
            'savedJobs',
            'totalAppliedJobs',
            'progressApplications',
            'latestApplicationForDetails',
            'hiredJobsCount',
            'latestHiredApplication',
            'upcomingInterviewCount',
            'nearestInterviewSchedule'
        ));
    }
}
