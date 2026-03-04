<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing events with "zz" data
        Event::where('title', 'zz')->orWhere('location', 'zz')->delete();
        
        // Create proper events
        $events = [
            [
                'slug' => 'workshop-digital-art-2025',
                'title' => 'Workshop Digital Art 2025',
                'title_en' => 'Digital Art Workshop 2025',
                'description' => 'Workshop lengkap tentang digital art untuk pemula hingga advanced. Pelajari teknik-teknik terbaru dalam dunia digital art.',
                'description_en' => 'Complete workshop about digital art for beginners to advanced. Learn the latest techniques in the digital art world.',
                'short_description' => 'Workshop digital art komprehensif dengan instruktur berpengalaman.',
                'short_description_en' => 'Comprehensive digital art workshop with experienced instructors.',
                'start_date' => Carbon::now()->addDays(30)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(30)->setTime(17, 0),
                'location' => 'Studio Kreatif Jakarta',
                'location_en' => 'Creative Studio Jakarta',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'exhibition-seni-kontemporer',
                'title' => 'Exhibition Seni Kontemporer',
                'title_en' => 'Contemporary Art Exhibition',
                'description' => 'Pameran seni kontemporer terbesar tahun ini dengan karya-karya dari seniman lokal dan internasional.',
                'description_en' => 'The biggest contemporary art exhibition this year featuring works from local and international artists.',
                'short_description' => 'Pameran seni kontemporer dengan koleksi terbaik.',
                'short_description_en' => 'Contemporary art exhibition with the best collection.',
                'start_date' => Carbon::now()->addDays(45)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(50)->setTime(18, 0),
                'location' => 'Galeri Nasional Indonesia',
                'location_en' => 'National Gallery of Indonesia',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'seminar-photography-modern',
                'title' => 'Seminar Photography Modern',
                'title_en' => 'Modern Photography Seminar',
                'description' => 'Seminar tentang teknik photography modern dan editing dengan software terbaru.',
                'description_en' => 'Seminar about modern photography techniques and editing with the latest software.',
                'short_description' => 'Seminar photography dengan tips dan trik terbaru.',
                'short_description_en' => 'Photography seminar with the latest tips and tricks.',
                'start_date' => Carbon::now()->addDays(60)->setTime(13, 0),
                'end_date' => Carbon::now()->addDays(60)->setTime(16, 0),
                'location' => 'Convention Center Bandung',
                'location_en' => 'Bandung Convention Center',
                'status' => 'published',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'slug' => 'workshop-watercolor-painting',
                'title' => 'Workshop Watercolor Painting',
                'title_en' => 'Watercolor Painting Workshop',
                'description' => 'Workshop melukis dengan cat air untuk semua level. Teknik dasar hingga advanced watercolor painting.',
                'description_en' => 'Watercolor painting workshop for all levels. Basic to advanced watercolor painting techniques.',
                'short_description' => 'Workshop melukis cat air dengan teknik profesional.',
                'short_description_en' => 'Watercolor painting workshop with professional techniques.',
                'start_date' => Carbon::now()->addDays(15)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(15)->setTime(12, 0),
                'location' => 'Art Studio Yogyakarta',
                'location_en' => 'Yogyakarta Art Studio',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'event-seni-rupa-digital',
                'title' => 'Event Seni Rupa Digital',
                'title_en' => 'Digital Fine Art Event',
                'description' => 'Event showcase seni rupa digital dengan teknologi VR dan AR terbaru.',
                'description_en' => 'Digital fine art showcase event with the latest VR and AR technology.',
                'short_description' => 'Showcase seni digital dengan teknologi immersive.',
                'short_description_en' => 'Digital art showcase with immersive technology.',
                'start_date' => Carbon::now()->addDays(90)->setTime(19, 0),
                'end_date' => Carbon::now()->addDays(90)->setTime(22, 0),
                'location' => 'Digital Art Museum Jakarta',
                'location_en' => 'Jakarta Digital Art Museum',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
            ]
        ];

        foreach ($events as $eventData) {
            $event = Event::updateOrCreate(
                ['slug' => $eventData['slug']],
                $eventData
            );
            echo "Created/updated event: " . $event->title . "\n";
        }
    }
}
