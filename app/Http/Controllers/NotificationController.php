<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        return NotificationResource::collection(
            Notification::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get()
        );
    }
}
