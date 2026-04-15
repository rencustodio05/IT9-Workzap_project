<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class AdminEmployerController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $employers = User::query()
            ->where('role', 'employer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('jobs')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.employers.index', compact('employers', 'search'));
    }

    public function show(User $employer)
    {
        abort_unless($employer->role === 'employer', 404);

        $jobs = $employer->jobs()->latest()->paginate(10);

        $totalApplicants = Application::whereIn('job_id', $employer->jobs()->pluck('id'))->count();

        return view('admin.employers.show', [
            'employer' => $employer,
            'jobs' => $jobs,
            'totalApplicants' => $totalApplicants,
        ]);
    }

    public function toggleStatus(User $employer)
    {
        abort_unless($employer->role === 'employer', 404);

        $employer->update([
            'is_active' => !$employer->is_active,
        ]);

        return redirect()->route('admin.employers.show', $employer)->with('success', 'Employer status updated successfully.');
    }
}
