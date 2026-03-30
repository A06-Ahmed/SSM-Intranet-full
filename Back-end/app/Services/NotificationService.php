<?php

namespace App\Services;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationService
{
    public function create(string $title, ?string $body, string $type, ?int $relatedId = null): Notification
    {
        return Notification::create([
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'related_id' => $relatedId,
            'expires_at' => Carbon::now()->addDay(),
        ]);
    }
}
