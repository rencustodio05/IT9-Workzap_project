<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $query->whereHas('jobseeker', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
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
            'status' => 'required|in:pending,interview,hired,rejected',
        ]);

        $app = Application::findOrFail($id);

        $app->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Application updated successfully.');
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
