<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventCounterController extends Controller
{
    public function getCountdownData($eventId)
    {
        // Rate limiting: cache for 1 hour to minimize server load
        $cacheKey = "countdown_event_{$eventId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($eventId) {
            $event = Event::findOrFail($eventId);
            
            $now = Carbon::now();
            $eventDate = $event->start_date;
            $timeLeft = $now->diffInSeconds($eventDate, false);
            
            $countdown = $this->formatCountdown($timeLeft);
            $eventStatus = $timeLeft > 0 ? 'upcoming' : ($timeLeft === 0 ? 'starting' : 'ended');
            
            return response()->json([
                'countdown' => $countdown,
                'status' => $eventStatus,
                'timeLeft' => $timeLeft,
                'eventDate' => $eventDate->format('d M Y H:i:s'),
                'eventTitle' => $event->localized_title,
                'cached_at' => $now->toISOString()
            ]);
        });
    }
    
    public function getAllCountdownData()
    {
        $upcomingEvents = Event::where('status', 'published')
            ->where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take(10)
            ->get();
        
        $countdownData = [];
        
        foreach ($upcomingEvents as $event) {
            $now = Carbon::now();
            $eventDate = $event->start_date;
            $timeLeft = $now->diffInSeconds($eventDate, false);
            
            $countdown = $this->formatCountdown($timeLeft);
            $eventStatus = $timeLeft > 0 ? 'upcoming' : ($timeLeft === 0 ? 'starting' : 'ended');
            
            $countdownData[] = [
                'eventId' => $event->id,
                'countdown' => $countdown,
                'status' => $eventStatus,
                'timeLeft' => $timeLeft,
                'eventDate' => $eventDate->format('d M Y H:i:s'),
                'eventTitle' => $event->localized_title
            ];
        }
        
        return response()->json([
            'events' => $countdownData,
            'total' => count($countdownData)
        ]);
    }
    
    private function formatCountdown($seconds)
    {
        if ($seconds <= 0) {
            return [
                'days' => 0
            ];
        }
        
        $days = floor($seconds / 86400);
        
        return [
            'days' => $days
        ];
    }
}
