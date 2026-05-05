<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class AdminApplicantController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $applicants = User::query()
            ->where('role', 'jobseeker')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('applications')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.applicants.index', compact('applicants', 'search'));
    }

    public function show(User $applicant)
    {
        abort_unless($applicant->role === 'jobseeker', 404);

        $applications = $applicant->applications()->with('job.employer')->latest()->paginate(10);

        return view('admin.applicants.show', [
            'applicant' => $applicant,
            'applications' => $applications,
        ]);
    }

    public function toggleStatus(User $applicant)
    {
        abort_unless($applicant->role === 'jobseeker', 404);

        $applicant->update([
            'is_active' => !$applicant->is_active,
        ]);

        return redirect()->route('admin.applicants.show', $applicant)->with('success', 'Applicant status updated successfully.');
    }
}
