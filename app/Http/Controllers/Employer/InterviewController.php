<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::with([
            'application.job',
            'application.jobseeker',
        ])
            ->whereHas('application', function ($q) {
                $q->whereIn('status', ['pending', 'interview']);
            })
            ->whereHas('application.job', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('interview_date')
            ->orderBy('interview_time')
            ->get();

        $today = Carbon::today('Asia/Manila')->toDateString();

        $todayInterviews = $interviews
            ->filter(function ($interview) use ($today) {
                return Carbon::parse($interview->interview_date)
                    ->timezone('Asia/Manila')
                    ->toDateString() === $today;
            })
            ->values();

        $upcomingInterviews = $interviews
            ->filter(function ($interview) use ($today) {
                return Carbon::parse($interview->interview_date)
                    ->timezone('Asia/Manila')
                    ->toDateString() > $today;
            })
            ->values();

        $applications = Application::with(['job', 'jobseeker'])
            ->whereHas('job', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereIn('status', ['pending', 'interview'])
            ->latest()
            ->get();

        return view('employer.interviews.index', compact('todayInterviews', 'upcomingInterviews', 'applications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'interview_date' => ['required', 'date_format:Y-m-d'],
            'interview_time' => ['required', 'date_format:H:i'],
            'status' => ['nullable', 'in:scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $application = Application::with(['job', 'jobseeker'])->findOrFail($validated['application_id']);

        if ((int) $application->job->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($application->status, ['pending', 'interview'], true)) {
            return back()
                ->withInput()
                ->withErrors(['application_id' => 'Only active applications can be scheduled for interview.']);
        }

        $alreadyScheduled = Interview::where('application_id', $application->id)
            ->whereIn('status', ['scheduled', 'completed'])
            ->exists();

        if ($alreadyScheduled) {
            return back()
                ->withInput()
                ->withErrors(['application_id' => 'Interview already scheduled for this application.']);
        }

        try {
            $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $validated['interview_date'] . ' ' . $validated['interview_time'], 'Asia/Manila');
        } catch (InvalidFormatException $e) {
            return back()
                ->withInput()
                ->withErrors(['interview_date' => 'Invalid interview date/time format.']);
        }

        Interview::create([
            'application_id' => $application->id,
            'employer_id' => Auth::id(),
            'jobseeker_id' => $application->user_id,
            'job_id' => $application->job_id,
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'scheduled_at' => $scheduledAt,
            'status' => $validated['status'] ?? 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);

        $application->update([
            'status' => 'interview',
        ]);

        return redirect()->route('employer.applications.decision', $application->id)
            ->with('success', 'Interview scheduled successfully.');
    }

    public function show(Interview $interview)
    {
        $interview->load(['application.job', 'application.jobseeker']);

        $ownerId = optional(optional($interview->application)->job)->user_id;

        if ((int) $ownerId !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (request()->expectsJson()) {
            return response()->json($interview);
        }

        return view('employer.interviews.show', compact('interview'));
    }

    public function update(Request $request, Interview $interview)
    {
        $interview->load('application.job');

        $ownerId = optional(optional($interview->application)->job)->user_id;

        if ((int) $ownerId !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'interview_date' => ['nullable', 'date_format:Y-m-d'],
            'interview_time' => ['nullable', 'date_format:H:i'],
            'status' => ['nullable', 'in:scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $hasDate = !empty($validated['interview_date']);
        $hasTime = !empty($validated['interview_time']);

        if ($hasDate xor $hasTime) {
            return back()
                ->withInput()
                ->withErrors(['interview_date' => 'Interview date and time are both required when rescheduling.']);
        }

        if ($hasDate && $hasTime) {
            try {
                $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $validated['interview_date'] . ' ' . $validated['interview_time'], 'Asia/Manila');
            } catch (InvalidFormatException $e) {
                return back()
                    ->withInput()
                    ->withErrors(['interview_date' => 'Invalid interview date/time format.']);
            }

            $interview->interview_date = $validated['interview_date'];
            $interview->interview_time = $validated['interview_time'];
            $interview->scheduled_at = $scheduledAt;
        }

        if (array_key_exists('status', $validated)) {
            $interview->status = $validated['status'];
        }

        if (array_key_exists('notes', $validated)) {
            $interview->notes = $validated['notes'];
        }

        $interview->save();

        if ($interview->application) {
            $interview->application->update([
                'status' => 'interview',
            ]);
        }

        return redirect()->route('employer.applications.decision', $interview->application_id)
            ->with('success', 'Interview rescheduled successfully.');
    }
}
