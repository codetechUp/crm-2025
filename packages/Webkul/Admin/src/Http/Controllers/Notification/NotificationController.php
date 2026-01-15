<?php

namespace Webkul\Admin\Http\Controllers\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Mark notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|integer',
            'alert_type' => 'required|string',
        ]);

        DB::table('admin_alert_reads')->updateOrInsert([
            'user_id'    => auth()->guard('user')->id(),
            'order_id'   => $request->order_id,
            'alert_type' => $request->alert_type,
        ], [
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Notification marked as read']);
    }
}
