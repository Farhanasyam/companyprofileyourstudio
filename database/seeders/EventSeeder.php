<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Workshop Photography Dasar',
                'title_en' => 'Basic Photography Workshop',
                'slug' => 'workshop-photography-dasar',
                'description' => 'Pelajari dasar-dasar fotografi dari para profesional. Workshop ini cocok untuk pemula yang ingin memulai perjalanan fotografi mereka.',
                'description_en' => 'Learn the basics of photography from professionals. This workshop is perfect for beginners who want to start their photography journey.',
                'short_description' => 'Workshop fotografi untuk pemula dengan instruktur berpengalaman.',
                'short_description_en' => 'Photography workshop for beginners with experienced instructors.',
                'start_date' => Carbon::now()->addDays(30)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(30)->setTime(17, 0),
                'location' => 'Studio YourStudio, Jakarta',
                'location_en' => 'YourStudio Studio, Jakarta',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Workshop Photography Dasar - YourStudio',
                'meta_title_en' => 'Basic Photography Workshop - YourStudio',
                'meta_description' => 'Pelajari dasar-dasar fotografi dari para profesional di YourStudio.',
                'meta_description_en' => 'Learn the basics of photography from professionals at YourStudio.',
            ],
            [
                'title' => 'Seminar Digital Marketing 2024',
                'title_en' => 'Digital Marketing Seminar 2024',
                'slug' => 'seminar-digital-marketing-2024',
                'description' => 'Seminar komprehensif tentang strategi digital marketing terbaru. Dapatkan insight dari praktisi berpengalaman di industri.',
                'description_en' => 'Comprehensive seminar about the latest digital marketing strategies. Get insights from experienced practitioners in the industry.',
                'short_description' => 'Seminar digital marketing dengan pembicara ahli dari berbagai perusahaan ternama.',
                'short_description_en' => 'Digital marketing seminar with expert speakers from various leading companies.',
                'start_date' => Carbon::now()->addDays(45)->setTime(8, 30),
                'end_date' => Carbon::now()->addDays(45)->setTime(16, 30),
                'location' => 'Hotel Grand Indonesia, Jakarta',
                'location_en' => 'Grand Indonesia Hotel, Jakarta',
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Seminar Digital Marketing 2024 - YourStudio',
                'meta_title_en' => 'Digital Marketing Seminar 2024 - YourStudio',
                'meta_description' => 'Seminar digital marketing terbaru dengan pembicara ahli.',
                'meta_description_en' => 'Latest digital marketing seminar with expert speakers.',
            ],
            [
                'title' => 'Workshop Video Editing',
                'title_en' => 'Video Editing Workshop',
                'slug' => 'workshop-video-editing',
                'description' => 'Pelajari teknik editing video profesional menggunakan software terbaru. Cocok untuk content creator dan filmmaker.',
                'description_en' => 'Learn professional video editing techniques using the latest software. Perfect for content creators and filmmakers.',
                'short_description' => 'Workshop editing video dengan software profesional.',
                'short_description_en' => 'Video editing workshop with professional software.',
                'start_date' => Carbon::now()->addDays(60)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(60)->setTime(18, 0),
                'location' => 'YourStudio Training Center',
                'location_en' => 'YourStudio Training Center',
                'status' => 'published',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Workshop Video Editing - YourStudio',
                'meta_title_en' => 'Video Editing Workshop - YourStudio',
                'meta_description' => 'Pelajari teknik editing video profesional di YourStudio.',
                'meta_description_en' => 'Learn professional video editing techniques at YourStudio.',
            ],
            [
                'title' => 'Event Networking Creative Industry',
                'title_en' => 'Creative Industry Networking Event',
                'slug' => 'event-networking-creative-industry',
                'description' => 'Bergabunglah dengan para kreator, desainer, dan profesional di industri kreatif. Networking event yang akan menghubungkan Anda dengan peluang baru.',
                'description_en' => 'Join creators, designers, and professionals in the creative industry. A networking event that will connect you with new opportunities.',
                'short_description' => 'Event networking untuk para profesional di industri kreatif.',
                'short_description_en' => 'Networking event for professionals in the creative industry.',
                'start_date' => Carbon::now()->addDays(15)->setTime(19, 0),
                'end_date' => Carbon::now()->addDays(15)->setTime(22, 0),
                'location' => 'Co-working Space Jakarta',
                'location_en' => 'Co-working Space Jakarta',
                'status' => 'published',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Event Networking Creative Industry - YourStudio',
                'meta_title_en' => 'Creative Industry Networking Event - YourStudio',
                'meta_description' => 'Event networking untuk para profesional di industri kreatif.',
                'meta_description_en' => 'Networking event for professionals in the creative industry.',
            ],
            [
                'title' => 'Workshop UI/UX Design',
                'title_en' => 'UI/UX Design Workshop',
                'slug' => 'workshop-ui-ux-design',
                'description' => 'Pelajari prinsip-prinsip desain UI/UX yang efektif. Workshop hands-on dengan project real untuk portfolio Anda.',
                'description_en' => 'Learn effective UI/UX design principles. Hands-on workshop with real projects for your portfolio.',
                'short_description' => 'Workshop desain UI/UX dengan project hands-on.',
                'short_description_en' => 'UI/UX design workshop with hands-on projects.',
                'start_date' => Carbon::now()->addDays(90)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(92)->setTime(17, 0),
                'location' => 'YourStudio Design Lab',
                'location_en' => 'YourStudio Design Lab',
                'status' => 'draft',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Workshop UI/UX Design - YourStudio',
                'meta_title_en' => 'UI/UX Design Workshop - YourStudio',
                'meta_description' => 'Pelajari desain UI/UX dengan project hands-on di YourStudio.',
                'meta_description_en' => 'Learn UI/UX design with hands-on projects at YourStudio.',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}
