<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutSections = AboutUs::ordered()->get();
        return view('admin.about-us.index', compact('aboutSections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = [
            'hero' => 'Hero Section',
            'history' => 'Sejarah Kami',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'why_choose_us' => 'Mengapa Memilih Kami',
            'contact_info' => 'Informasi Kontak',
        ];
        
        return view('admin.about-us.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255|unique:about_us,section',
            'title' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'content_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'required_with:features|string',
            'features.*.icon' => 'nullable|string|max:255',
            'features.*.color' => 'nullable|string|max:255',
            'features_en' => 'nullable|array',
            'features_en.*.title' => 'required_with:features_en|string|max:255',
            'features_en.*.description' => 'required_with:features_en|string',
            'features_en.*.icon' => 'nullable|string|max:255',
            'features_en.*.color' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        AboutUs::create($data);

        return redirect()->route('admin.about-us.index')
            ->with('success', 'Section tentang kami berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutUs $aboutUs)
    {
        return view('admin.about-us.show', compact('aboutUs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutUs $aboutUs)
    {
        $sections = [
            'hero' => 'Hero Section',
            'history' => 'Sejarah Kami',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'why_choose_us' => 'Mengapa Memilih Kami',
            'contact_info' => 'Informasi Kontak',
        ];
        
        return view('admin.about-us.edit', compact('aboutUs', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        $request->validate([
            'section' => 'required|string|max:255|unique:about_us,section,' . $aboutUs->id,
            'title' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'content_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'required_with:features|string',
            'features.*.icon' => 'nullable|string|max:255',
            'features.*.color' => 'nullable|string|max:255',
            'features_en' => 'nullable|array',
            'features_en.*.title' => 'required_with:features_en|string|max:255',
            'features_en.*.description' => 'required_with:features_en|string',
            'features_en.*.icon' => 'nullable|string|max:255',
            'features_en.*.color' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        $aboutUs->update($data);

        return redirect()->route('admin.about-us.index')
            ->with('success', 'Section tentang kami berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        $aboutUs->delete();

        return redirect()->route('admin.about-us.index')
            ->with('success', 'Section tentang kami berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggle(AboutUs $aboutUs)
    {
        $aboutUs->update(['is_active' => !$aboutUs->is_active]);
        
        $status = $aboutUs->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.about-us.index')
            ->with('success', "Section {$aboutUs->section} berhasil {$status}!");
    }
}
