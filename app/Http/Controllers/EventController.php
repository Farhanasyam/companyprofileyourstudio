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

        $allEvents = $this->publicEvents();
        $upcomingEvents = $allEvents->filter(fn ($event) => $event->start_date->isFuture())->take(50)->values();
        $ongoingEvents = $allEvents->filter(fn ($event) => $event->is_ongoing)->values();
        $pastEvents = $allEvents->filter(fn ($event) => $event->is_past)->sortByDesc('start_date')->take(6)->values();
        
        // Add countdown data to upcoming events
        $upcomingEvents->each(function ($event) {
            $event->countdown_data = $event->countdown;
            $event->event_status = $event->event_status;
        });
        
        $calendarEvents = $this->formatCalendarEvents($allEvents);

        return view('events.index', compact('upcomingEvents', 'ongoingEvents', 'pastEvents', 'calendarEvents'));
    }

    public function upcoming()
    {
        app('seo')
            ->setTitle(app()->getLocale() === 'en' ? 'Upcoming Events - ' . SettingHelper::getCompanyName() : 'Event Mendatang - ' . SettingHelper::getCompanyName())
            ->setDescription(app()->getLocale() === 'en' ? 'List of upcoming events and workshops organized by ' . SettingHelper::getCompanyName() : 'Daftar event dan workshop mendatang yang diadakan oleh ' . SettingHelper::getCompanyName())
            ->setType('website');

        $allEvents = $this->publicEvents();
        $upcomingEvents = $allEvents->filter(fn ($event) => $event->start_date->isFuture())->values();
        $ongoingEvents = collect(); // Empty for upcoming page
        $pastEvents = collect(); // Empty for upcoming page
        
        // Add countdown data to upcoming events
        $upcomingEvents->each(function ($event) {
            $event->countdown_data = $event->countdown;
            $event->event_status = $event->event_status;
        });
        
        $calendarEvents = $this->formatCalendarEvents($allEvents);

        return view('events.index', compact('upcomingEvents', 'ongoingEvents', 'pastEvents', 'calendarEvents'));
    }

    public function completed()
    {
        app('seo')
            ->setTitle(app()->getLocale() === 'en' ? 'Completed Events - ' . SettingHelper::getCompanyName() : 'Event Selesai - ' . SettingHelper::getCompanyName())
            ->setDescription(app()->getLocale() === 'en' ? 'List of completed events and workshops organized by ' . SettingHelper::getCompanyName() : 'Daftar event dan workshop yang telah selesai diadakan oleh ' . SettingHelper::getCompanyName())
            ->setType('website');

        $upcomingEvents = collect(); // Empty for completed page
        $ongoingEvents = collect(); // Empty for completed page
        $allEvents = $this->publicEvents();
        $pastEvents = $allEvents->filter(fn ($event) => $event->is_past)->sortByDesc('start_date')->values();
        
        $calendarEvents = $this->formatCalendarEvents($allEvents);

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

    private function publicEvents()
    {
        return Event::published()->active()->orderByStartDate()->get();
    }

    private function formatCalendarEvents($events)
    {
        return $events->map(function ($event) {
            $status = $event->is_ongoing ? 'ongoing' : ($event->is_past ? 'past' : 'upcoming');

            return [
                'id' => $event->id,
                'title' => $event->localized_title,
                'start_date' => $event->start_date->format('Y-m-d'),
                'end_date' => $event->end_date ? $event->end_date->format('Y-m-d') : null,
                'status' => $status,
                'url' => route('events.show', $event->slug),
            ];
        });
    }
}
