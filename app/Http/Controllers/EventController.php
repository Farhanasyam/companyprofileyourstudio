<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Helpers\SettingHelper;

class EventController extends Controller
{
    public function index()
    {
        app('seo')
            ->setTitle(app()->getLocale() === 'en' ? 'Events - ' . SettingHelper::getCompanyName() : 'Events - ' . SettingHelper::getCompanyName())
            ->setDescription(app()->getLocale() === 'en' ? 'List of events and workshops organized by ' . SettingHelper::getCompanyName() : 'Daftar event dan workshop yang diadakan oleh ' . SettingHelper::getCompanyName())
            ->setType('website');

        // Get events for calendar - use cached methods where possible
        $upcomingEvents = Event::getUpcomingEvents(50); // Get more for calendar
        $ongoingEvents = Event::published()->active()->ongoing()->get();
        $pastEvents = Event::published()->active()
            ->where('end_date', '<', now())
            ->latest('start_date')
            ->take(6)
            ->get();
        
        // Add countdown data to upcoming events
        $upcomingEvents->each(function ($event) {
            $event->countdown_data = $event->countdown;
            $event->event_status = $event->event_status;
        });
        
        // Get all events for calendar display
        $allEvents = Event::published()->active()->get();
        
        // Format events for calendar
        $calendarEvents = $allEvents->map(function ($event) {
            $status = 'upcoming';
            if ($event->is_ongoing) {
                $status = 'ongoing';
            } elseif ($event->is_past) {
                $status = 'past';
            }
            
            return [
                'id' => $event->id,
                'title' => $event->localized_title,
                'start_date' => $event->start_date->format('Y-m-d'),
                'end_date' => $event->end_date ? $event->end_date->format('Y-m-d') : null,
                'status' => $status,
                'url' => route('events.show', $event->slug)
            ];
        });

        return view('events.index', compact('upcomingEvents', 'ongoingEvents', 'pastEvents', 'calendarEvents'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        app('seo')
            ->setTitle($event->localized_meta_title ?: $event->localized_title . ' - ' . SettingHelper::getCompanyName())
            ->setDescription($event->localized_meta_description ?: $event->localized_short_description ?: strip_tags($event->localized_description))
            ->setImage($event->image ? $event->image_url : null)
            ->setType('article');

        $relatedEvents = Event::published()
            ->active()
            ->where('id', '!=', $event->id)
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }
}
