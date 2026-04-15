<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['job', 'jobseeker'])
            ->whereHas('job', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // 🔍 SEARCH APPLICANT NAME
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('jobseeker', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
            });
        }

        // 🧑 JOB POSITION FILTER
        if ($request->filled('job')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->job . '%');
            });
        }

        // 📌 STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->get();

        return view('employer.applications.index', compact('applications'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,interview,hired,rejected,fired',
        ]);

        $application = Application::findOrFail($id);
        $application->status = $request->status;

        if ($request->status === 'hired' && Schema::hasColumn('applications', 'hired_at')) {
            $application->hired_at = now();
        }

        if ($request->status === 'fired' && Schema::hasColumn('applications', 'fired_at')) {
            $application->fired_at = now();
        }

        $application->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => $application->status,
            ]);
        }

        if ($request->status === 'fired') {
            return redirect()->route('applications.index')
                ->with('success', 'Employee has been fired successfully.');
        }

        return redirect()->route('applications.index')
            ->with('success', 'Application updated successfully.');
    }

    public function show($id)
    {
        $application = Application::with(['job', 'jobseeker'])
            ->whereHas('job', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        return view('employer.applications.show', compact('application'));
    }
}
