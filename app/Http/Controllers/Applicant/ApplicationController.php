<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));

        $allowedStatuses = ['pending', 'interview', 'cancelled', 'rejected', 'hired', 'fired'];

        $applications = Application::with(['job', 'interview'])
            ->where('user_id', $user->id)
            ->whereIn('status', $allowedStatuses)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('job', function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', '%' . $search . '%');
                });
            })
            ->when($status !== '' && in_array($status, $allowedStatuses, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only(['search', 'status']));

        return view('applicant.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $application = Application::with(['job.employer', 'interview'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $hiredApplications = Application::with('job.employer')
            ->where('user_id', $user->id)
            ->where('status', 'hired')
            ->latest()
            ->get();

        return view('applicant.applications.show', compact('application', 'hiredApplications'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $application = Application::where('user_id', $user->id)->findOrFail($id);

        if (!in_array($application->status, ['pending', 'interview'])) {
            return redirect()->back()->with('error', 'You can only cancel pending or interview applications.');
        }

        $application->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('applicant.applications.index')->with('success', 'Application cancelled successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $application = Application::where('user_id', $user->id)->findOrFail($id);

        $application->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Item deleted successfully.',
            ]);
        }

        return redirect()->route('applicant.applications.index')->with('success', 'Application deleted successfully.');
    }

    public function history()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $employmentHistory = Application::with('job.employer')
            ->where('user_id', $user->id)
            ->whereIn('status', ['hired', 'fired'])
            ->latest('updated_at')
            ->get();

        return view('applicant.applications.history', compact('employmentHistory'));
    }
}
