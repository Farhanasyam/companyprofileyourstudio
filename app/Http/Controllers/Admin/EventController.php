<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::latest();
        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'cancelled'], true)) {
            $query->where('status', $request->status);
        }
        $events = $query->paginate(10)->withQueryString();
        $stats = [
            'published' => Event::where('status', 'published')->count(),
            'upcoming' => Event::upcoming()->count(),
            'featured' => Event::featured()->count(),
        ];
        return view('admin.events.index', compact('events', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'short_description_en' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov|max:10240',
            'gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov|max:10240',
            'start_date' => 'required|date|after:now',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,published,cancelled',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_description_en' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Judul event wajib diisi.',
            'title.max' => 'Judul event maksimal 255 karakter.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'start_date.required' => 'Tanggal mulai event wajib diisi.',
            'start_date.after' => 'Tanggal mulai event harus setelah hari ini.',
            'end_date.after' => 'Tanggal selesai event harus setelah tanggal mulai.',
            'status.in' => 'Status event tidak valid.',
            'status.in' => 'Status event tidak valid.',
            'image.image' => 'File gambar harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'video.file' => 'File video harus berupa video.',
            'video.mimes' => 'Format video harus mp4, avi, atau mov.',
            'video.max' => 'Ukuran video maksimal 10MB.',
            'gallery.*.file' => 'File galeri harus berupa file yang valid.',
            'gallery.*.mimes' => 'Format file galeri harus jpeg, png, jpg, gif, mp4, avi, atau mov.',
            'gallery.*.max' => 'Ukuran file galeri maksimal 10MB.',
        ]);

        $data = $request->only([
            'title', 'title_en', 'description', 'description_en', 'short_description', 'short_description_en',
            'start_date', 'end_date', 'location', 'location_en', 'status', 'meta_title', 'meta_title_en',
            'meta_description', 'meta_description_en',
        ]);

        // Ensure status is set - use hidden field as fallback
        if (empty($data['status']) || $data['status'] === '') {
            $data['status'] = $request->input('status', 'published');
        }

        // Generate slug
        $data['slug'] = Event::createSlug($request->title);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('events', 'public');
        }

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            $galleryFiles = [];
            foreach ($request->file('gallery') as $file) {
                $galleryFiles[] = $file->store('events/gallery', 'public');
            }
            $data['gallery'] = $galleryFiles;
        }

        // Convert boolean fields - use hidden field as fallback
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'short_description_en' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov|max:10240',
            'gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,published,cancelled',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_description_en' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Judul event wajib diisi.',
            'title.max' => 'Judul event maksimal 255 karakter.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'start_date.required' => 'Tanggal mulai event wajib diisi.',
            'end_date.after' => 'Tanggal selesai event harus setelah tanggal mulai.',
            'status.in' => 'Status event tidak valid.',
            'status.in' => 'Status event tidak valid.',
            'image.image' => 'File gambar harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'video.file' => 'File video harus berupa video.',
            'video.mimes' => 'Format video harus mp4, avi, atau mov.',
            'video.max' => 'Ukuran video maksimal 10MB.',
            'gallery.*.file' => 'File galeri harus berupa file yang valid.',
            'gallery.*.mimes' => 'Format file galeri harus jpeg, png, jpg, gif, mp4, avi, atau mov.',
            'gallery.*.max' => 'Ukuran file galeri maksimal 10MB.',
        ]);

        $data = $request->only([
            'title', 'title_en', 'description', 'description_en', 'short_description', 'short_description_en',
            'start_date', 'end_date', 'location', 'location_en', 'status', 'meta_title', 'meta_title_en',
            'meta_description', 'meta_description_en',
        ]);

        // Ensure status is set - use hidden field as fallback
        if (empty($data['status']) || $data['status'] === '') {
            $data['status'] = $request->input('status', 'published');
        }

        // Update slug if title changed
        if ($event->title !== $request->title) {
            $data['slug'] = Event::createSlug($request->title);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video
            if ($event->video) {
                Storage::disk('public')->delete($event->video);
            }
            $data['video'] = $request->file('video')->store('events', 'public');
        }

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery files
            if ($event->gallery) {
                foreach ($event->gallery as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            $galleryFiles = [];
            foreach ($request->file('gallery') as $file) {
                $galleryFiles[] = $file->store('events/gallery', 'public');
            }
            $data['gallery'] = $galleryFiles;
        }

        // Convert boolean fields - use hidden field as fallback
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Delete associated files
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        if ($event->video) {
            Storage::disk('public')->delete($event->video);
        }
        if ($event->gallery) {
            foreach ($event->gallery as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
