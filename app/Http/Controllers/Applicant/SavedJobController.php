<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $jobs = $user->savedJobs()
            ->with('employer')
            ->latest('saved_jobs.created_at')
            ->paginate(10);

        return view('applicant.saved.index', compact('jobs'));
    }

    public function store(Job $job)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $user->savedJobs()->syncWithoutDetaching([$job->id]);

        return back()->with('success', 'Job saved successfully.');
    }

    public function destroy(Job $job)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $user->savedJobs()->detach($job->id);

        return back()->with('success', 'Saved job removed.');
    }
}
