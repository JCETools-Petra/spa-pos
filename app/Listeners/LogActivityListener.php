<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Events\ActivityCreating; // Gunakan event ActivityCreating
use Jenssegers\Agent\Agent; // Import Agent

class LogActivityListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ActivityCreating $event): void
    {
        $agent = new Agent();

        // Kumpulkan informasi detail
        $deviceInfo = [
            'device_type' => $agent->isDesktop() ? 'PC/Desktop' : ($agent->isTablet() ? 'Tablet' : 'Ponsel'),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
        ];

        // Tambahkan informasi ini ke dalam 'properties' log
        $event->activity->properties = $event->activity->properties->merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'device_info' => $deviceInfo,
        ]);
    }
}