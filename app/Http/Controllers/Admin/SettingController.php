<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $section = $request->get('section');
        
        if ($section === 'contact') {
            // Pastikan key kontak perusahaan ada (alamat, telepon, email) agar bisa diedit di admin
            $companyKeys = [
                'company_name' => ['value' => '', 'type' => 'text', 'description' => 'Nama perusahaan'],
                'company_address' => ['value' => 'Jl. Contoh Alamat No. 123, Kota Anda', 'type' => 'textarea', 'description' => 'Alamat perusahaan (ditampilkan di Hubungi Kami & footer)'],
                'company_phone' => ['value' => '+62 812-3456-7890', 'type' => 'text', 'description' => 'Nomor telepon / WA (ditampilkan di Hubungi Kami & footer)'],
                'company_email' => ['value' => 'info@yourstudio.com', 'type' => 'text', 'description' => 'Email perusahaan (ditampilkan di Hubungi Kami & footer)'],
            ];
            foreach ($companyKeys as $key => $defaults) {
                Setting::firstOrCreate(
                    ['key' => $key],
                    array_merge($defaults, ['group' => 'company'])
                );
            }
            $contactKeys = [
                'maps_iframe', 'maps_address',
                'company_address', 'company_phone', 'company_email', 'company_name'
            ];
            $settings = Setting::whereIn('key', $contactKeys)
                ->orderBy('key')
                ->get()
                ->groupBy(function($item) {
                    if (in_array($item->key, ['maps_iframe', 'maps_address'])) {
                        return 'maps';
                    }
                    return 'company';
                });
        } elseif ($section === 'order-wa') {
            // Pengaturan Order Manual via WhatsApp
            $orderWaKeys = ['whatsapp_order_number', 'whatsapp_order_template'];
            foreach ($orderWaKeys as $key) {
                Setting::firstOrCreate(
                    ['key' => $key],
                    [
                        'value' => $key === 'whatsapp_order_template'
                            ? "Halo! 🙏\n\nSaya ingin memesan dari *{company_name}*:\n\n📦 *Daftar Pesanan:*\n{items}\n\n👤 *Pemesan:* {nama_pemesan}\n📱 *No. WA:* {no_hp}\n📝 *Catatan:* {catatan}\n\nTerima kasih. Salam kreatif! ✨"
                            : '',
                        'type' => $key === 'whatsapp_order_template' ? 'textarea' : 'text',
                        'group' => 'order_wa',
                        'description' => $key === 'whatsapp_order_number'
                            ? 'Nomor WhatsApp untuk menerima order (contoh: 6281234567890). User akan diarahkan ke nomor ini.'
                            : 'Template pesan WA. Gunakan: {company_name}, {items}, {nama_pemesan}, {no_hp}, {catatan}',
                    ]
                );
            }
            $settings = Setting::whereIn('key', $orderWaKeys)->orderBy('key')->get()->groupBy('group');
        } else {
            // Show all settings except contact/map related ones
            $contactKeys = [
                'maps_iframe', 'maps_address',
                'company_address', 'company_phone', 'company_email', 'company_name',
                'whatsapp_order_number', 'whatsapp_order_template'
            ];
            $settings = Setting::whereNotIn('key', $contactKeys)
                ->orderBy('group')
                ->orderBy('key')
                ->get()
                ->groupBy('group');
        }
        
        return view('admin.settings.index', compact('settings', 'section'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:text,textarea,image,json,boolean',
            'group' => 'required|in:general,company,social,seo',
            'description' => 'nullable|string',
        ]);

        Setting::create($request->all());

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key,' . $setting->id,
            'value' => 'nullable|string',
            'type' => 'required|in:text,textarea,image,json,boolean',
            'group' => 'required|in:general,company,social,seo',
            'description' => 'nullable|string',
        ]);

        $setting->update($request->all());

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil dihapus!');
    }

    /**
     * Update multiple settings at once
     */
    public function updateBulk(Request $request)
    {
        $settings = $request->except(['_token', '_method']);
        $section = $request->get('section');
        
        foreach ($settings as $key => $value) {
            if ($value !== null) {
                Setting::set($key, $value);
            }
        }

        $redirectRoute = $section === 'contact'
            ? route('admin.settings.index', ['section' => 'contact'])
            : ($section === 'order-wa'
                ? route('admin.settings.index', ['section' => 'order-wa'])
                : route('admin.settings.index'));

        return redirect($redirectRoute)
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * SEO Settings page
     */
    public function seo()
    {
        $seoSettings = Setting::where('group', 'seo')->get();
        return view('admin.settings.seo-working', compact('seoSettings'));
    }

    /**
     * Update SEO settings
     */
    public function updateSeo(Request $request)
    {
        $seoFields = [
            'meta_title',
            'meta_description', 
            'meta_keywords',
            'og_title',
            'og_description',
            'twitter_title',
            'twitter_description',
            'canonical_url',
            'robots',
            'sitemap_priority',
            'whatsapp_event_registration',
            'maps_iframe',
            'maps_address',
        ];

        // Handle text fields
        foreach ($seoFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Handle file uploads
        if ($request->hasFile('og_image')) {
            $ogImage = $request->file('og_image');
            $ogImageName = 'seo/og_image_' . time() . '.' . $ogImage->getClientOriginalExtension();
            $ogImage->storeAs('public/images', $ogImageName);
            Setting::set('og_image', 'images/' . $ogImageName);
        }

        if ($request->hasFile('twitter_image')) {
            $twitterImage = $request->file('twitter_image');
            $twitterImageName = 'seo/twitter_image_' . time() . '.' . $twitterImage->getClientOriginalExtension();
            $twitterImage->storeAs('public/images', $twitterImageName);
            Setting::set('twitter_image', 'images/' . $twitterImageName);
        }

        return redirect()->route('admin.settings.seo')
            ->with('success', 'Pengaturan SEO berhasil diperbarui!');
    }
}
