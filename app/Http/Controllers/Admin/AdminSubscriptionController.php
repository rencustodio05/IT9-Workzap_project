<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');

        $subscriptions = EmployerSubscription::query()
            ->with(['employer:id,first_name,last_name,email', 'approvedBy:id,first_name,last_name'])
            ->when(in_array($status, ['pending', 'active', 'expired'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions', 'status'));
    }
}
