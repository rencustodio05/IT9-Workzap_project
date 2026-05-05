<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display applications list with filters
     */
    public function index(Request $request)
    {
        $query = Application::with(['applicant', 'job', 'interview'])
            ->whereHas('job', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // Search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('employer.applications.index', compact('applications'));
    }

    /**
     * Show full application profile page
     */
    public function show(Application $application)
    {
        // Ensure employer owns the job
        $application->load(['job', 'applicant', 'interview']);

        if ($application->job->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employer.applications.show', compact('application'));
    }

    public function decision(Application $application)
    {
        $application->load(['job', 'applicant', 'interview']);

        if ($application->job->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$application->interview) {
            return redirect()->route('employer.applications.show', $application->id)
                ->withErrors(['application_id' => 'Schedule an interview before making a decision.']);
        }

        return view('employer.applications.decision', compact('application'));
    }

    /**
     * Update application status
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,interview,hired,rejected,fired'
        ]);

        if ($application->job->user_id !== Auth::id()) {
            abort(403);
        }

        $application->update([
            'status' => $request->status,
            'hired_at' => $request->status === 'hired' ? now() : $application->hired_at,
            'fired_at' => $request->status === 'fired' ? now() : $application->fired_at,
        ]);

        return response()->json([
            'success' => true,
            'status' => $application->status,
            'message' => 'Status updated!'
        ]);
    }

    public function hire(Application $application)
    {
        $application->load(['job', 'interview']);

        if ((int) $application->job->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$application->interview) {
            return back()->withErrors(['application_id' => 'Interview must be scheduled before hiring.']);
        }

        $application->update([
            'status' => 'hired',
            'hired_at' => now(),
        ]);

        return back()->with('success', 'Applicant hired successfully.');
    }

    public function reject(Application $application)
    {
        $application->load(['job', 'interview']);

        if ((int) $application->job->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$application->interview) {
            return back()->withErrors(['application_id' => 'Interview must be scheduled before rejecting.']);
        }

        $application->update([
            'status' => 'rejected',
            'fired_at' => now(),
        ]);

        return back()->with('success', 'Applicant rejected successfully.');
    }

    public function fire(Application $application)
    {
        $application->load(['job', 'interview']);

        if ((int) $application->job->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$application->interview) {
            return back()->withErrors(['application_id' => 'Interview must be scheduled before firing.']);
        }

        if ($application->status !== 'hired') {
            return back()->withErrors(['application_id' => 'Only hired applicants can be fired.']);
        }

        $application->update([
            'status' => 'fired',
            'fired_at' => now(),
        ]);

        return back()->with('success', 'Applicant fired successfully.');
    }
}
