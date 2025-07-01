<?php
// app/Http/Controllers/PatientNotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class PatientNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the patient’s notifications, with an optional filter.
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->input('filter', 'All');

        $query = $user->notifications()->orderBy('created_at', 'desc');

        if ($filter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->get();

        return view('patient.notifications', compact('notifications','filter'));
    }

    /**
     * Toggle read/unread for one notification.
     */
    public function update(Request $request, DatabaseNotification $notification)
    {
        $user = Auth::user();

        // ensure the notification belongs to this user
        if ($notification->notifiable_id !== $user->id) {
            abort(403);
        }

        if ($request->has('read')) {
            $notification->markAsRead();
        } else {
            $notification->markAsUnread();
        }

        return back();
    }
}
