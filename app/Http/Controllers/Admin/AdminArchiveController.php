<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;

class AdminArchiveController extends Controller
{
    public function index()
    {
        $deletedUsers = User::onlyTrashed()->latest('deleted_at')->paginate(10, ['*'], 'users_page');
        $deletedJobs = Job::onlyTrashed()->with('employer:id,first_name,last_name,email')->latest('deleted_at')->paginate(10, ['*'], 'jobs_page');

        return view('admin.archive.index', compact('deletedUsers', 'deletedJobs'));
    }

    public function restoreUser(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.archive.index')->with('success', 'User restored successfully.');
    }

    public function restoreJob(int $id)
    {
        $job = Job::onlyTrashed()->findOrFail($id);
        $job->restore();

        return redirect()->route('admin.archive.index')->with('success', 'Job restored successfully.');
    }

    public function forceDeleteUser(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.archive.index')->with('success', 'User permanently deleted.');
    }

    public function forceDeleteJob(int $id)
    {
        $job = Job::onlyTrashed()->findOrFail($id);
        $job->forceDelete();

        return redirect()->route('admin.archive.index')->with('success', 'Job permanently deleted.');
    }
}
