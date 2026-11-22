<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Deskripsi produk (ID & EN) yang ditulis per jenis barang: fungsi, ukuran/isi, dan tips pakai.
 * Dicocokkan lewat nama produk. Produk yang tidak dikenali tidak diubah.
 *
 * Jalankan: php artisan db:seed --class=ProductDescriptionSeeder
 * Catatan: menimpa deskripsi produk yang namanya dikenali (termasuk yang sudah diedit di admin).
 */
class ProductDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;
        $skipped = [];

        foreach (Product::orderBy('sort_order')->get() as $product) {
            $copy = $this->describe($product->name);
            if (!$copy) {
                $skipped[] = $product->name;
                continue;
            }
            [$id, $en] = $copy;

            $product->update([
                'description' => $this->html($id),
                'description_en' => $this->html($en),
                'meta_description' => Str::limit($id[0], 155),
                'meta_description_en' => Str::limit($en[0], 155),
            ]);
            $updated++;
        }

        $this->command?->info("Deskripsi diperbarui: {$updated} produk.");
        if ($skipped) {
            $this->command?->warn('Tidak dikenali (tidak diubah): ' . implode(', ', $skipped));
        }
    }

    private function html(array $paragraphs): string
    {
        return '<p>' . implode('</p><p>', array_map('e', $paragraphs)) . '</p>';
    }

    /** @return array{0: string[], 1: string[]}|null paragraf ID & EN */
    private function describe(string $name): ?array
    {
        $n = $name;

        // ---------- Marker & alat tulis ----------
        if (preg_match('/^Acrylic Marker (Enmy )?Isi (\d+)$/i', $n, $m)) {
            $count = $m[2];
            $brand = $m[1] ? 'Enmy ' : '';
            $extraId = $count >= 24 ? 'Pilihan warnanya cukup banyak untuk gradasi dan detail, jadi jarang perlu mencampur warna.' : 'Isinya pas untuk mulai mencoba atau untuk dibawa ke mana-mana.';
            $extraEn = $count >= 24 ? 'There are enough colours for shading and details, so you rarely need to mix.' : 'A good size to start with or to carry around.';
            return [[
                "Spidol cat akrilik {$brand}isi {$count} warna. Tintanya pekat dan menutup rata, bisa dipakai di clay yang sudah kering, kayu, batu, kanvas, kaca, dan keramik.",
                "Ujungnya enak untuk garis dan detail kecil seperti mata, pipi, atau motif. {$extraId}",
                'Sebelum dipakai pertama kali, kocok spidol lalu tekan ujungnya beberapa kali sampai tinta keluar.',
            ], [
                "Acrylic paint markers, {$count} colours. The ink is opaque and dries evenly on dry clay, wood, stone, canvas, glass and ceramics.",
                "The tip is handy for lines and small details like eyes, cheeks or patterns. {$extraEn}",
                'Before first use, shake the marker and press the tip a few times until the ink comes through.',
            ]];
        }
        if (preg_match('/^Drawing Pencil Maries (\w+)$/', $n, $m)) {
            $g = $m[1];
            [$id, $en] = match (true) {
                str_ends_with($g, 'H') => ['Grade H lebih keras, goresannya tipis dan terang. Pas untuk sketsa awal dan garis bantu yang nanti mudah dihapus.', 'H grades are harder and leave light, thin lines. Good for first sketches and guide lines that erase easily.'],
                $g === 'HB' => ['HB ada di tengah-tengah: tidak terlalu keras dan tidak terlalu hitam. Bisa untuk menulis sekaligus sketsa sehari-hari.', 'HB sits in the middle, not too hard and not too dark. Works for writing and everyday sketching.'],
                $g === 'B' => ['Grade B sedikit lebih lunak dari HB, goresannya lebih gelap tapi masih mudah dikontrol.', 'B is a little softer than HB, slightly darker but still easy to control.'],
                in_array($g, ['2B', '3B', '4B'], true) => ['Grade ini cukup lunak, enak untuk sketsa dan arsiran tingkat menengah.', 'This grade is fairly soft, good for sketching and mid-tone shading.'],
                default => ['Grade ini sangat lunak dan pekat, dipakai untuk bayangan gelap dan memberi kontras pada gambar.', 'This grade is very soft and dark, used for deep shadows and adding contrast.'],
            };
            return [[
                "Pensil gambar Maries {$g}. {$id}",
                'Biasanya dipakai bersama beberapa grade lain supaya gradasi terang-gelapnya lebih lengkap.',
            ], [
                "Maries drawing pencil, grade {$g}. {$en}",
                'Usually used together with a few other grades for a fuller light-to-dark range.',
            ]];
        }
        if ($n === 'Ballpoint Karakter') {
            return [[
                'Pulpen dengan hiasan karakter lucu di ujung atasnya. Dipakai menulis sehari-hari juga nyaman.',
                'Sering dibeli sebagai hadiah kecil, isi goodie bag, atau tambahan kado untuk anak.',
            ], [
                'A ballpoint pen topped with a cute character. Comfortable for everyday writing.',
                'A popular small gift, goodie-bag filler or add-on present for kids.',
            ]];
        }
        if ($n === 'Ballpoint Karakter Pouch') {
            return [[
                'Pulpen karakter yang dikemas bersama pouch-nya. Pouch bisa dipakai lagi untuk menyimpan alat tulis kecil.',
                'Praktis untuk hadiah karena sudah rapi dalam satu kemasan.',
            ], [
                'Character ballpoint pens packed with a pouch. The pouch can be reused to store small stationery.',
                'Handy as a gift since everything comes neatly in one pack.',
            ]];
        }
        if (preg_match('/^Journalling Capybara/', $n) || preg_match('/^Journalling Set - (\w+)$/', $n, $m)) {
            $colour = $m[1] ?? null;
            $variantId = $colour ? "Varian warna {$colour}." : 'Tema capybara dengan desain yang berbeda di tiap varian.';
            $variantEn = $colour ? "{$colour} colour version." : 'Capybara theme, with a different design for each version.';
            return [[
                'Paket untuk mulai journaling: buku dan pernak-pernik untuk menghias halaman jurnal. ' . $variantId,
                'Isi paket bisa sedikit berbeda antar varian. Kalau ingin tahu isinya secara detail, tanyakan saja ke admin lewat WhatsApp.',
            ], [
                'A starter pack for journaling: a book plus bits and pieces to decorate your pages. ' . $variantEn,
                'Contents may differ slightly between versions. Ask us on WhatsApp if you want the exact list.',
            ]];
        }
        if ($n === 'Notebook Capybara') {
            return [[
                'Buku catatan dengan sampul bergambar capybara. Ukurannya mudah dibawa, cocok untuk catatan sekolah, jurnal harian, atau to-do list.',
                'Tersedia beberapa warna sampul. Pilih warnanya di bagian varian.',
            ], [
                'A notebook with a capybara cover. Easy to carry, great for school notes, a daily journal or to-do lists.',
                'Available in several cover colours. Pick one in the variant section.',
            ]];
        }
        if ($n === 'Notes Mini Renceng') {
            return [[
                'Notes kecil dalam kemasan renceng. Pas untuk catatan singkat, pengingat, atau ditempel di jurnal.',
                'Ada dua pilihan motif.',
            ], [
                'Small notepads sold in a strip pack. Good for quick notes, reminders or sticking into a journal.',
                'Comes in two designs.',
            ]];
        }
        if (preg_match('/^Sticky Notes Capybara - (\w+)$/', $n, $m)) {
            return [[
                "Sticky notes bergambar capybara, desain {$m[1]}. Bisa ditempel di buku, meja, atau layar laptop untuk pengingat.",
                'Lembarannya mudah dilepas dan ditempel lagi.',
            ], [
                "Capybara sticky notes, {$m[1]} design. Stick them on books, desks or your laptop as reminders.",
                'The sheets peel off and re-stick easily.',
            ]];
        }
        if (preg_match('/^Pencil Case Capybara - (\w+)$/', $n, $m)) {
            $colour = ['Brown' => 'cokelat', 'White' => 'putih'][$m[1]] ?? strtolower($m[1]);
            return [[
                "Tempat pensil motif capybara warna {$colour}. Muat pensil, pulpen, penghapus, dan alat tulis kecil lainnya.",
                'Cocok untuk sekolah, kuliah, atau menyimpan perlengkapan menggambar supaya tidak tercecer.',
            ], [
                "Capybara pencil case in {$m[1]}. Fits pencils, pens, erasers and other small stationery.",
                'Good for school, college, or keeping your drawing supplies together.',
            ]];
        }
        if ($n === 'Bag Gift Capybara') {
            return [[
                'Tas kertas motif capybara untuk membungkus kado atau hasil karya.',
                'Muat untuk buku, alat tulis, atau satu paket DIY, jadi tidak perlu repot membungkus lagi.',
            ], [
                'A capybara paper gift bag for wrapping presents or your handmade pieces.',
                'Fits a book, stationery or a DIY kit, so no extra wrapping needed.',
            ]];
        }
        if (preg_match('/^Color Wheels (\d+)cm$/', $n, $m)) {
            return [[
                "Roda warna diameter {$m[1]} cm. Tinggal putar untuk melihat pasangan warna komplementer, analog, dan hasil campuran dua warna.",
                'Membantu saat belajar mencampur cat atau memilih kombinasi warna sebelum mulai melukis.',
            ], [
                "A {$m[1]} cm colour wheel. Turn it to see complementary and analogous colours and what two colours make when mixed.",
                'Useful when learning to mix paint or choosing a colour scheme before you start.',
            ]];
        }
        if ($n === 'Marker Pad Mont Marte A5') {
            return [[
                'Buku kertas khusus spidol ukuran A5 dari Mont Marte. Kertasnya dibuat untuk marker sehingga tinta tidak mudah merembes ke halaman berikutnya.',
                'Cocok untuk latihan mewarnai dengan marker, desain, atau sketsa ilustrasi.',
            ], [
                'An A5 marker pad from Mont Marte. The paper is made for markers, so ink is less likely to bleed through to the next page.',
                'Good for marker practice, design work or illustration sketches.',
            ]];
        }
        if (preg_match('/^Watercolor Paper Canson - (A\d)$/', $n, $m)) {
            return [[
                "Kertas cat air Canson ukuran {$m[1]}. Kertasnya tebal, jadi tidak gampang bergelombang atau sobek saat dibasahi.",
                'Bisa untuk cat air, gouache, maupun tinta. Ukuran ini juga enak untuk kartu ucapan buatan sendiri.',
            ], [
                "Canson watercolour paper, {$m[1]}. It is thick, so it does not buckle or tear easily when wet.",
                'Works with watercolour, gouache and ink. This size is also nice for handmade cards.',
            ]];
        }

        // ---------- Stiker ----------
        if (preg_match('/^DIY Stiker Ruangan 3D - Pinset (\w+)$/', $n, $m)) {
            $colour = ['Biru' => 'blue', 'Pink' => 'pink'][$m[1]] ?? strtolower($m[1]);
            return [[
                "Pinset warna " . strtolower($m[1]) . " untuk membantu menempel bagian stiker 3D yang kecil-kecil.",
                'Dengan pinset, stiker lebih mudah diangkat dan ditempel pas di posisinya tanpa terkena sidik jari.',
            ], [
                "Tweezers in {$colour} for placing the small pieces of 3D room stickers.",
                'They make it easier to lift each piece and place it exactly where it goes, without fingerprints.',
            ]];
        }
        if (preg_match('/^DIY Stiker Ruangan 3D - (.+)$/', $n, $m)) {
            return [[
                "Stiker 3D yang dirakit sendiri menjadi miniatur ruangan bertema {$m[1]}. Bagian stiker dilepas lalu ditempel lapis demi lapis sampai jadi diorama kecil.",
                'Kegiatan santai yang bikin betah, dan hasilnya bisa dipajang di meja. Supaya lebih mudah menempel bagian kecil, pakai pinset.',
            ], [
                "A 3D sticker kit you assemble into a miniature {$m[1]} room. Peel the pieces and layer them up into a small diorama.",
                'A relaxing project, and the result looks nice on a desk. Tweezers make the small pieces much easier.',
            ]];
        }
        if ($n === 'Sticker Your Studio') {
            return [[
                'Stiker logo Your Studio. Bisa ditempel di botol minum, laptop, jurnal, atau kotak alat lukis.',
            ], [
                'Your Studio logo sticker. Stick it on a water bottle, laptop, journal or art supply box.',
            ]];
        }

        // ---------- Cat ----------
        if (preg_match('/^Cat Akrilik (Basic|Pastel) 6x3ml$/', $n, $m)) {
            $id = $m[1] === 'Pastel' ? '6 warna pastel yang lembut' : '6 warna dasar';
            $en = $m[1] === 'Pastel' ? '6 soft pastel colours' : '6 basic colours';
            return [[
                "Cat akrilik mini isi {$id}, 3 ml per warna. Ukurannya kecil, pas untuk mewarnai karya clay, gantungan kunci, atau proyek DIY kecil.",
                'Cepat kering dan tahan air setelah kering. Cuci kuas sebelum catnya mengering.',
            ], [
                "Mini acrylic paint set with {$en}, 3 ml each. Just right for painting clay pieces, keychains and small DIY projects.",
                'Dries fast and is water-resistant once dry. Rinse your brush before the paint sets.',
            ]];
        }
        if (preg_match('/^Cat Akrilik VTEC (Basic|Pastel) 6x12ml$/', $n, $m)) {
            $id = $m[1] === 'Pastel' ? 'warna pastel' : 'warna dasar';
            return [[
                "Cat akrilik VTEC isi 6 {$id}, 12 ml per tube. Cukup untuk beberapa karya clay atau lukisan kanvas ukuran kecil sampai sedang.",
                'Bisa dicampur untuk membuat warna baru. Untuk warna yang lebih transparan, tambahkan sedikit air.',
            ], [
                "VTEC acrylic paint, 6 " . strtolower($m[1]) . " colours in 12 ml tubes. Enough for several clay pieces or small to medium canvases.",
                'Mix them to make new colours. Add a little water for a more transparent look.',
            ]];
        }
        if ($n === 'Cat Akrilik VTEC 12x3ml') {
            return [[
                'Cat akrilik VTEC ukuran mini, 12 warna masing-masing 3 ml. Pilihan warnanya lengkap untuk ukuran yang ringkas.',
                'Pas untuk detail karya clay, kanvas mini, dan dibawa ke kelas atau workshop.',
            ], [
                'Mini VTEC acrylic set, 12 colours at 3 ml each. A full range in a compact size.',
                'Good for details on clay, mini canvases, and taking to classes or workshops.',
            ]];
        }
        if ($n === 'Cat Akrilik VTEC Set 12x5ml') {
            return [[
                'Set cat akrilik VTEC isi 12 warna, 5 ml per tube. Sedikit lebih banyak dari versi 3 ml, jadi lebih awet untuk dipakai berkali-kali.',
                'Bisa untuk kanvas, clay, kayu, dan kertas tebal.',
            ], [
                'VTEC acrylic set, 12 colours in 5 ml tubes. A bit more paint than the 3 ml set, so it lasts longer.',
                'Works on canvas, clay, wood and heavy paper.',
            ]];
        }
        if ($n === 'Cat Akrilik VTEC 6x35ml') {
            return [[
                'Cat akrilik VTEC isi 6 warna dengan tube besar 35 ml. Pas untuk melukis bidang luas di kanvas atau dipakai bersama di kelas.',
                'Warna-warnanya bisa dicampur untuk mendapatkan warna lain sesuai kebutuhan.',
            ], [
                'VTEC acrylic paint, 6 colours in large 35 ml tubes. Good for covering bigger canvas areas or sharing in a class.',
                'The colours can be mixed to get whatever shade you need.',
            ]];
        }
        if ($n === 'Cat Akrilik Set Isi 33') {
            return [[
                'Set cat akrilik isi 33 warna. Pilihan warnanya banyak, jadi bisa langsung pakai tanpa harus mencampur dari warna dasar.',
                'Cocok untuk yang sering melukis kanvas, mewarnai clay, atau mengerjakan banyak proyek DIY sekaligus.',
            ], [
                'A 33-colour acrylic set. With this many colours you can paint straight from the set without mixing from primaries.',
                'Great if you paint canvases often, colour clay, or work on lots of DIY projects.',
            ]];
        }
        if ($n === 'Acrylic Pad Giorgione') {
            return [[
                'Buku kertas khusus cat akrilik dari Giorgione. Permukaannya bertekstur sehingga cat menempel dengan baik.',
                'Praktis untuk latihan, studi warna, atau mencoba komposisi sebelum melukis di kanvas.',
            ], [
                'An acrylic paper pad from Giorgione. The textured surface holds the paint well.',
                'Handy for practice, colour studies, or trying a composition before moving to canvas.',
            ]];
        }
        if ($n === 'Cat Air VTEC 12X3ml') {
            return [[
                'Cat air tube VTEC isi 12 warna, masing-masing 3 ml. Encerkan dengan air sedikit demi sedikit untuk warna transparan, atau pakai lebih pekat untuk warna yang kuat.',
                'Ukurannya kecil, pas untuk latihan dan dibawa bepergian.',
            ], [
                'VTEC tube watercolours, 12 colours at 3 ml each. Add water bit by bit for transparent washes, or use less water for stronger colour.',
                'Small and easy to carry, good for practice and travel.',
            ]];
        }
        if ($n === 'Giotto Acquerelli') {
            return [[
                'Cat air padat Giotto isi 12 warna dalam kotak yang praktis. Basahi kuas, usapkan ke cat, dan warnanya siap dipakai.',
                'Banyak dipakai anak sekolah dan pemula karena mudah digunakan dan gampang dibersihkan.',
            ], [
                'Giotto pan watercolours, 12 colours in a handy box. Wet your brush, rub it on the colour and you are ready.',
                'Popular with students and beginners because it is easy to use and clean up.',
            ]];
        }
        if (preg_match('/^Watercolour Pad Giorgione (A\d)$/', $n, $m)) {
            return [[
                "Buku kertas cat air Giorgione ukuran {$m[1]}. Kertasnya cukup tebal untuk teknik basah, jadi tidak cepat bergelombang.",
                'Bentuk buku memudahkan untuk menyimpan latihan dan hasil karya di satu tempat.',
            ], [
                "Giorgione watercolour pad, {$m[1]}. The paper is thick enough for wet techniques, so it does not buckle quickly.",
                'Keeping your practice and finished pieces together in one pad is easy.',
            ]];
        }

        // ---------- Kuas ----------
        if (preg_match('/^Brush Watercolor - (Datar|Runcing)$/', $n, $m)) {
            return $m[1] === 'Datar' ? [[
                'Kuas cat air bentuk datar dengan bulu yang lembut dan menyerap air dengan baik.',
                'Pas untuk mewarnai bidang luas, membuat latar (wash), atau garis tegas dengan sisi kuas.',
            ], [
                'Flat watercolour brush with soft bristles that hold water well.',
                'Good for large areas, background washes, or crisp lines using the edge.',
            ]] : [[
                'Kuas cat air dengan ujung runcing. Bulunya lembut dan kembali ke bentuk runcing setelah dipakai.',
                'Cocok untuk detail, garis tipis-tebal, serta bentuk daun dan kelopak bunga.',
            ], [
                'Pointed watercolour brush. Soft bristles that spring back to a fine point.',
                'Good for details, thick-and-thin strokes, leaves and petals.',
            ]];
        }
        if (preg_match('/^Kuas (Datar|Runcing) 16cm$/', $n, $m)) {
            $shapeId = $m[1] === 'Datar' ? 'datar, untuk mengecat bidang dan sapuan rata' : 'runcing, untuk detail dan garis';
            $shapeEn = $m[1] === 'Datar' ? 'flat, for filling areas and even strokes' : 'pointed, for details and lines';
            return [[
                "Kuas panjang 16 cm dengan bentuk {$shapeId}. Bisa untuk akrilik maupun cat air.",
                'Harganya terjangkau, jadi pas untuk kelas, workshop, atau kuas cadangan di rumah.',
            ], [
                "A 16 cm brush, {$shapeEn}. Works with acrylic and watercolour.",
                'Affordable, so it suits classes, workshops or as a spare brush at home.',
            ]];
        }
        if ($n === 'Kuas Nylon Multifungsi Isi 10') {
            return [[
                'Set 10 kuas nylon dengan bentuk dan ukuran yang berbeda-beda. Bisa dipakai untuk akrilik, cat air, dan mewarnai clay.',
                'Satu set ini sudah cukup untuk kebutuhan dasar, dari mengecat bidang besar sampai detail kecil.',
            ], [
                'A set of 10 nylon brushes in different shapes and sizes. For acrylic, watercolour and painting clay.',
                'One set covers the basics, from big areas to small details.',
            ]];
        }
        if (preg_match('/^Kuas VTEC 10([12]) /', $n, $m)) {
            return $m[1] === '1' ? [[
                'Set kuas VTEC seri 101 isi 6. Bulunya lebih kaku dan kokoh, enak untuk cat yang kental seperti akrilik dan untuk membuat tekstur.',
                'Tahan dipakai berulang kali selama dibersihkan setelah dipakai.',
            ], [
                'VTEC series 101 brush set, 6 pieces. Firmer bristles that handle thick paint like acrylic and texture work.',
                'Holds up to repeated use as long as you clean it after painting.',
            ]] : [[
                'Set kuas VTEC seri 102 isi 6. Bulunya halus sehingga sapuannya rata dan tidak meninggalkan banyak jejak bulu.',
                'Cocok untuk detail, mewarnai clay, dan cat air.',
            ], [
                'VTEC series 102 brush set, 6 pieces. Soft bristles that give smooth strokes with few brush marks.',
                'Good for details, painting clay and watercolour.',
            ]];
        }

        // ---------- Kanvas & easel ----------
        if (preg_match('/^Kanvas Bulat (\d+) cm$/', $n, $m)) {
            return [[
                "Kanvas bulat diameter {$m[1]} cm yang sudah terpasang di rangka, siap dilukis dengan cat akrilik.",
                'Bentuk bulat cocok untuk lukisan bunga, pemandangan kecil, atau hiasan dinding.',
            ], [
                "A {$m[1]} cm round stretched canvas, ready for acrylic paint.",
                'The round shape suits florals, small landscapes or wall decor.',
            ]];
        }
        if (preg_match('/^Kanvas Persegi (\d+)x(\d+)cm$/', $n, $m)) {
            return [[
                "Kanvas berangka ukuran {$m[1]} x {$m[2]} cm, siap dilukis dengan cat akrilik.",
                'Ukuran yang enak untuk latihan, hadiah, atau dipajang di meja dan dinding.',
            ], [
                "A {$m[1]} x {$m[2]} cm stretched canvas, ready for acrylic paint.",
                'A nice size for practice, gifts, or displaying on a desk or wall.',
            ]];
        }
        if (preg_match('/^Kanvas Panel (\d+)x(\d+)cm Isi (\d+)$/', $n, $m)) {
            return [[
                "Kanvas panel ukuran {$m[1]} x {$m[2]} cm, isi {$m[3]} lembar. Bentuknya papan tipis, lebih ringan dari kanvas berangka.",
                'Enak untuk latihan, studi warna, atau dibawa melukis di luar rumah.',
            ], [
                "Canvas panels, {$m[1]} x {$m[2]} cm, pack of {$m[3]}. Thin boards that are lighter than stretched canvas.",
                'Good for practice, colour studies or painting on the go.',
            ]];
        }
        if ($n === 'Kanvas Mont Marte Easel 10cm') {
            return [[
                'Kanvas mini 10 cm dari Mont Marte, lengkap dengan easel kecil untuk memajangnya.',
                'Lucu untuk lukisan mini, hadiah, atau hiasan meja.',
            ], [
                'A 10 cm mini canvas from Mont Marte with a small easel to display it.',
                'Cute for mini paintings, gifts or desk decor.',
            ]];
        }
        if ($n === 'Kanvas Mont Marte Standing 20x20cm') {
            return [[
                'Kanvas 20 x 20 cm dari Mont Marte yang bisa berdiri sendiri di meja tanpa easel.',
                'Setelah selesai dilukis, langsung bisa dipajang.',
            ], [
                'A 20 x 20 cm Mont Marte canvas that stands on its own without an easel.',
                'Once painted, it is ready to display.',
            ]];
        }
        if ($n === 'Paint By Number 20x20cm') {
            return [[
                'Kanvas 20 x 20 cm yang sudah bergambar garis dan nomor. Tinggal isi setiap bagian dengan warna sesuai nomornya sampai gambarnya jadi.',
                'Tidak perlu jago menggambar, cukup sabar dan teliti. Tersedia banyak motif, pilih di bagian varian.',
            ], [
                'A 20 x 20 cm canvas printed with outlines and numbers. Fill each area with the matching colour until the picture comes together.',
                'No drawing skills needed, just patience. Many designs available, pick one in the variant section.',
            ]];
        }
        if ($n === 'Easel Kayu Mini') {
            return [[
                'Easel kayu kecil untuk memajang kanvas mini, foto, atau kartu.',
                'Sering dipakai bersama kanvas kecil dan paint by number supaya hasilnya bisa langsung dipajang.',
            ], [
                'A small wooden easel for displaying mini canvases, photos or cards.',
                'Often paired with small canvases and paint-by-number so the result can go straight on display.',
            ]];
        }

        // ---------- Palet ----------
        if ($n === 'Palet Lukis Mini') {
            return [[
                'Palet kecil dengan beberapa sekat untuk menampung dan mencampur cat.',
                'Ringan dan mudah dicuci. Pas untuk cat akrilik dan cat air.',
            ], [
                'A small palette with wells for holding and mixing paint.',
                'Light and easy to wash. Good for acrylic and watercolour.',
            ]];
        }
        if (preg_match('/^Palette Knife No\.(\d) (.+)$/', $n, $m)) {
            $shapeEn = ['Trapesium' => 'trapezoid', 'Runcing' => 'pointed', 'Oval' => 'oval', 'Segitiga M' => 'medium triangle', 'Segitiga L' => 'large triangle', 'Segitiga XL' => 'extra large triangle', 'Persegi' => 'square'][$m[2]] ?? strtolower($m[2]);
            $shapeId = preg_replace('/^Segitiga (\w+)$/', 'segitiga ukuran $1', $m[2]);
            $shapeId = $shapeId === $m[2] ? strtolower($m[2]) : $shapeId;
            return [[
                "Pisau palet nomor {$m[1]} dengan mata berbentuk {$shapeId}. Dipakai untuk mencampur cat di palet dan melukis dengan teknik cat tebal (impasto).",
                'Bisa juga untuk meratakan spackling paste atau merapikan permukaan clay.',
            ], [
                "Palette knife no. {$m[1]}, {$shapeEn} blade. For mixing paint and painting thick, textured strokes (impasto).",
                'Also handy for spreading spackling paste or smoothing clay.',
            ]];
        }

        // ---------- Clay ----------
        if (preg_match('/^Clay DAS (\w+) (\d+)gr$/i', $n, $m)) {
            $colour = strtolower($m[1]);
            $grams = (int) $m[2];
            [$colourId, $colourEn, $noteId, $noteEn] = match ($colour) {
                'white' => ['putih', 'white', 'Warnanya putih bersih, jadi gampang diwarnai dengan cat apa saja.', 'Being plain white, it takes any paint colour easily.'],
                'terracotta' => ['terakota', 'terracotta', 'Warnanya cokelat kemerahan seperti gerabah, tetap terlihat bagus walau tidak dicat.', 'Its reddish-brown colour looks like fired clay, so it looks good even unpainted.'],
                'stone' => ['stone', 'stone', 'Warnanya abu-abu seperti batu, cocok untuk karya bernuansa alami.', 'Its stone-grey colour suits natural-looking pieces.'],
                'wood' => ['wood', 'wood', 'Warnanya cokelat kayu, cocok untuk karya bernuansa hangat dan natural.', 'Its wood-brown colour gives a warm, natural look.'],
                'black' => ['hitam', 'black', 'Warnanya hitam pekat, pas untuk karya yang ingin terlihat elegan atau kontras.', 'Its deep black suits elegant or high-contrast pieces.'],
                default => [$colour, $colour, '', ''],
            };
            $sizeId = $grams <= 350 ? 'Ukuran ini pas untuk pemula dan proyek kecil seperti gantungan kunci.' : ($grams >= 1000 ? 'Ukuran besar, cukup untuk proyek besar atau dipakai bersama di kelas.' : 'Ukuran sedang, cukup untuk beberapa karya.');
            $sizeEn = $grams <= 350 ? 'This size suits beginners and small projects like keychains.' : ($grams >= 1000 ? 'A large pack, enough for big projects or a class.' : 'A medium pack, enough for several pieces.');
            return [[
                trim("Clay DAS {$colourId} isi {$grams} gram. Clay ini kering sendiri di udara, tidak perlu dioven. {$noteId}"),
                "Teksturnya halus dan mudah dibentuk. Setelah kering bisa diamplas, diukir, lalu dicat akrilik. {$sizeId}",
                'Simpan sisa clay di plastik yang tertutup rapat supaya tidak mengeras.',
            ], [
                trim("DAS air-dry clay, {$colourEn}, {$grams} g. It dries in the air, no oven needed. {$noteEn}"),
                "Smooth and easy to shape. Once dry you can sand, carve and paint it with acrylics. {$sizeEn}",
                'Keep leftover clay in a sealed plastic bag so it does not harden.',
            ]];
        }
        if (preg_match('/^Foam Clay (Jumbo|Medium|Mini)$/', $n, $m)) {
            $sizeId = ['Jumbo' => 'kemasan jumbo', 'Medium' => 'kemasan sedang', 'Mini' => 'kemasan mini'][$m[1]];
            return [[
                "Foam clay {$sizeId}. Teksturnya ringan dan lembut seperti busa, tidak lengket di tangan, dan kering sendiri di udara.",
                'Aman dan mudah dipakai anak-anak. Cocok untuk hiasan, magnet kulkas, atau bentuk-bentuk kecil.',
            ], [
                'Foam clay, ' . strtolower($m[1]) . ' pack. Light and soft like foam, not sticky, and it air-dries on its own.',
                'Easy for kids to use. Good for decorations, fridge magnets or small figures.',
            ]];
        }
        if ($n === 'Modelling Schoolplast LYRA Isi 10') {
            return [[
                'Plastisin LYRA isi 10 warna. Tidak mengeras, jadi bisa dibentuk ulang berkali-kali.',
                'Cocok untuk anak belajar membentuk dan melatih motorik halus.',
            ], [
                'LYRA modelling plasticine, 10 colours. It does not harden, so it can be reshaped again and again.',
                'Great for kids learning to model and building fine motor skills.',
            ]];
        }

        // ---------- Alat clay & finishing ----------
        if (preg_match('/^Alat Ukir Isi (\d+)$/', $n, $m)) {
            return [[
                "Set alat ukir isi {$m[1]} dengan ujung yang berbeda-beda: untuk menggores garis, membuat lubang, merapikan tepi, dan memberi tekstur.",
                'Bisa dipakai untuk clay, plastisin, atau sabun ukir. Bersihkan sisa clay setelah dipakai supaya ujungnya tetap rapi.',
            ], [
                "A {$m[1]}-piece carving tool set with different tips for scoring lines, making holes, tidying edges and adding texture.",
                'Use it on clay, plasticine or soap. Wipe off clay after use to keep the tips clean.',
            ]];
        }
        if (preg_match('/^Cukil Isi (\d)$/', $n, $m)) {
            return [[
                "Set pisau cukil isi {$m[1]} dengan bentuk mata yang berbeda. Dipakai untuk mencukil karet atau lino saat membuat stempel dan cetak cukil.",
                'Selalu arahkan cukilan menjauhi tangan supaya aman.',
            ], [
                "A {$m[1]}-piece lino cutter set with different blade shapes, for carving rubber or lino blocks for stamps and prints.",
                'Always cut away from your hand to stay safe.',
            ]];
        }
        if ($n === 'Pottery Isi 8') {
            return [[
                'Set alat pottery isi 8 untuk membentuk, mengerok, dan merapikan clay.',
                'Bisa untuk clay DAS maupun jenis clay lainnya. Satu set ini sudah mencakup alat yang paling sering dipakai.',
            ], [
                'An 8-piece pottery tool set for shaping, scraping and smoothing clay.',
                'Works with DAS clay or regular clay, and covers the tools you will use most.',
            ]];
        }
        if ($n === 'Rolling Pin Clay 16cm') {
            return [[
                'Penggiling clay sepanjang 16 cm untuk meratakan clay sampai tebalnya sama.',
                'Hasil gilingan yang rata memudahkan saat mencetak atau memotong bentuk.',
            ], [
                'A 16 cm clay roller for flattening clay to an even thickness.',
                'An even sheet makes cutting and stamping shapes much easier.',
            ]];
        }
        if ($n === 'Scrapper') {
            return [[
                'Alat pengerok untuk mengangkat clay dari alas, memotong lurus, dan merapikan sisi karya.',
                'Juga berguna untuk membersihkan meja dari sisa clay.',
            ], [
                'A scraper for lifting clay off your mat, making straight cuts and tidying edges.',
                'Also handy for cleaning clay off the table.',
            ]];
        }
        if ($n === 'Scrapper Motif') {
            return [[
                'Scraper dengan sisi bergerigi untuk memberi tekstur garis pada permukaan clay.',
                'Gunakan saat clay masih lembap supaya motifnya tercetak jelas.',
            ], [
                'A scraper with a serrated edge for adding line textures to clay.',
                'Use it while the clay is still damp so the pattern shows clearly.',
            ]];
        }
        if ($n === 'Cetakan Akrilik 10cm') {
            return [[
                'Cetakan akrilik bening ukuran 10 cm untuk membantu membentuk dan meratakan clay.',
                'Karena bening, posisi clay di bawahnya tetap terlihat saat ditekan.',
            ], [
                'A clear 10 cm acrylic tool for shaping and flattening clay.',
                'Being clear, you can see the clay underneath while you press.',
            ]];
        }
        if (preg_match('/^Cutter Knife( \+ Refill Isi (\d+))?$/', $n, $m)) {
            $refill = !empty($m[2]);
            return [[
                'Pisau cutter kecil dengan ujung runcing untuk memotong clay, kertas, dan stiker dengan rapi.' . ($refill ? " Sudah termasuk {$m[2]} mata pisau cadangan." : ''),
                'Ganti mata pisau kalau potongan mulai tidak rapi. Simpan dengan penutupnya setelah dipakai.',
            ], [
                'A small precision knife for neat cuts in clay, paper and stickers.' . ($refill ? " Includes {$m[2]} spare blades." : ''),
                'Swap the blade when cuts start to get ragged, and keep the cap on after use.',
            ]];
        }
        if (preg_match('/^Cutting Mat (A\d)$/', $n, $m)) {
            return [[
                "Alas potong ukuran {$m[1]}. Melindungi meja saat memotong dengan cutter, dan garis kotak-kotaknya membantu memotong lurus.",
                'Bisa juga dijadikan alas kerja clay supaya meja tetap bersih.',
            ], [
                "A {$m[1]} cutting mat. Protects your table when using a knife, and the grid helps you cut straight.",
                'Doubles as a clay work surface to keep the table clean.',
            ]];
        }
        if ($n === 'Mirror Standard') {
            return [[
                'Cermin polos ukuran standar yang siap dihias sendiri dengan clay atau cat.',
                'Bisa jadi proyek DIY untuk kamar atau hadiah buatan tangan.',
            ], [
                'A plain standard mirror ready to decorate with clay or paint.',
                'A nice DIY project for your room or a handmade gift.',
            ]];
        }
        if ($n === 'Spackling Paste 250gr') {
            return [[
                'Pasta dempul 250 gram untuk menutup retak halus dan pori-pori di permukaan clay sebelum dicat.',
                'Oleskan tipis, biarkan kering, lalu amplas halus. Bisa juga dipakai untuk membuat tekstur timbul di kanvas.',
            ], [
                'Spackling paste, 250 g, for filling hairline cracks and pores on clay before painting.',
                'Apply a thin layer, let it dry, then sand smooth. Also good for raised textures on canvas.',
            ]];
        }
        if ($n === 'Sponge 10cm') {
            return [[
                'Spons 10 cm untuk menghaluskan permukaan clay yang masih lembap.',
                'Bisa juga untuk menepuk cat supaya hasilnya bertekstur, atau membersihkan sisa cat.',
            ], [
                'A 10 cm sponge for smoothing clay while it is still damp.',
                'Also useful for dabbing on paint for texture or wiping off excess paint.',
            ]];
        }
        if ($n === 'Amplas Grit 1500') {
            return [[
                'Amplas halus grit 1500 untuk merapikan permukaan clay yang sudah benar-benar kering sebelum dicat.',
                'Gosok pelan dengan gerakan memutar, lalu bersihkan debunya dengan kuas kering.',
            ], [
                'Fine 1500-grit sandpaper for smoothing fully dried clay before painting.',
                'Sand gently in small circles, then brush the dust away with a dry brush.',
            ]];
        }
        if (preg_match('/^Varnish (.+?) (\d+)ml$/', $n, $m)) {
            $brand = $m[1];
            return [[
                "Varnish {$brand} {$m[2]} ml untuk melapisi karya clay atau lukisan akrilik yang sudah kering.",
                'Lapisan varnish membuat warna lebih awet dan permukaan lebih tahan terhadap debu dan cipratan air. Oleskan tipis dengan kuas, tunggu kering, baru tambah lapisan kedua bila perlu.',
            ], [
                "{$brand} varnish, {$m[2]} ml, for sealing dried clay pieces or acrylic paintings.",
                'It helps colours last and protects the surface from dust and splashes. Brush on a thin coat, let it dry, then add a second coat if needed.',
            ]];
        }

        // ---------- Paket bundling ----------
        if ($n === 'DIY Bearbrick Pouring - Keychain') {
            return [[
                'Paket DIY pouring untuk membuat gantungan kunci bearbrick. Cat dituang ke figur kecil lalu dibiarkan mengalir sampai membentuk motif.',
                'Motif yang terbentuk selalu berbeda, jadi setiap gantungan kunci punya pola sendiri. Tersedia banyak pilihan karakter dan warna di bagian varian.',
            ], [
                'A DIY pour-painting kit for bearbrick keychains. Pour the paint over the little figure and let it flow into a pattern.',
                'The pattern is different every time, so each keychain is one of a kind. Lots of characters and colours to choose from in the variants.',
            ]];
        }
        if ($n === 'DIY Bearbrick Pouring - Manekin') {
            return [[
                'Paket DIY pouring dengan figur bearbrick model manekin. Cat dituang dan dibiarkan mengalir membentuk motif di seluruh figur.',
                'Hasilnya enak dipajang di meja atau rak. Pilih variannya di bagian varian.',
            ], [
                'A DIY pour-painting kit with a mannequin-style bearbrick figure. Pour the paint and let it flow over the whole figure.',
                'Looks great on a desk or shelf. Pick your version in the variant section.',
            ]];
        }
        if (preg_match('/^DIY Clay Keychain - Paket (Fun|Standard) \(WHITE\) (\d+) Gram$/', $n, $m)) {
            return [[
                "Paket membuat gantungan kunci dari clay putih {$m[2]} gram. Bentuk clay sesuai keinginan, biarkan kering, lalu warnai.",
                'Ada dua pilihan warna cat, Basic atau Pastel. Seru untuk dikerjakan sendiri, bareng teman, atau sebagai kegiatan anak di rumah.',
            ], [
                "A kit for making keychains with {$m[2]} g of white clay. Shape it however you like, let it dry, then paint.",
                'Choose Basic or Pastel paint colours. Fun on your own, with friends, or as a kids activity at home.',
            ]];
        }
        if (preg_match('/^DIY Clay Kit \((WHITE|TERACOTTA)\) (\d+) Gram$/', $n, $m)) {
            $colourId = $m[1] === 'WHITE' ? 'putih' : 'terakota';
            $colourEn = $m[1] === 'WHITE' ? 'white' : 'terracotta';
            return [[
                "Paket untuk mulai membuat karya clay, berisi clay {$colourId} {$m[2]} gram beserta perlengkapan dasarnya.",
                'Clay-nya kering sendiri di udara, jadi tidak perlu oven. Cocok untuk pemula yang mau langsung mencoba tanpa membeli alat satu per satu.',
            ], [
                "A starter kit for clay projects with {$m[2]} g of {$colourEn} clay and the basic tools.",
                'The clay air-dries, so no oven needed. Great for beginners who want to start without buying tools one by one.',
            ]];
        }
        if (preg_match('/^DIY Coaster Painting - Coaster (.+)$/', $n, $m)) {
            return [[
                "Paket melukis tatakan gelas (coaster) dengan bentuk {$m[1]}. Tinggal warnai sesuai selera, lalu pakai sendiri atau jadikan hadiah.",
                'Pilih bentuk dan warna cat (Basic atau Pastel) di bagian varian.',
            ], [
                "A coaster painting kit, {$m[1]} shapes. Paint them your way, then use them or give them as a gift.",
                'Choose the shape and paint colours (Basic or Pastel) in the variant section.',
            ]];
        }
        if ($n === 'DIY Frame Clay - Persegi / Lingkaran') {
            return [[
                'Paket menghias bingkai foto dengan clay. Tersedia bentuk persegi dan lingkaran.',
                'Bentuk hiasan dari clay, tempel di bingkai, lalu warnai. Hasilnya bisa dipakai untuk foto favorit atau dijadikan kado.',
            ], [
                'A kit for decorating a photo frame with clay, in square or round.',
                'Shape clay decorations, stick them on the frame and paint. Use it for a favourite photo or give it as a gift.',
            ]];
        }
        if (preg_match('/^DIY Kanvas Painting - Kanvas (\d+)x(\d+)$/', $n, $m)) {
            return [[
                "Paket melukis di kanvas {$m[1]} x {$m[2]} cm dengan motif yang sudah ditentukan, jadi tinggal mengikuti polanya.",
                'Pilih motif dan warna cat (Basic atau Pastel) di bagian varian. Cocok untuk yang baru mulai melukis.',
            ], [
                "A painting kit with a {$m[1]} x {$m[2]} cm canvas and a set design to follow.",
                'Choose the design and paint colours (Basic or Pastel) in the variant section. Great for first-time painters.',
            ]];
        }
        if (preg_match('/^DIY Kanvas Panels Painting - (\d+)x(\d+)$/', $n, $m)) {
            return [[
                "Paket melukis di kanvas panel {$m[1]} x {$m[2]} cm dengan cat akrilik VTEC.",
                'Kanvas panel lebih ringan dan tipis, enak untuk latihan atau melukis beberapa karya sekaligus.',
            ], [
                "A painting kit with {$m[1]} x {$m[2]} cm canvas panels and VTEC acrylic paint.",
                'The panels are light and thin, good for practice or painting several pieces at once.',
            ]];
        }
        if (preg_match('/^DIY Mirror Clay - (Abstract|Standard)$/', $n, $m)) {
            $shapeId = $m[1] === 'Abstract' ? 'cermin bentuk abstrak' : 'cermin bentuk standar (oval)';
            $shapeEn = $m[1] === 'Abstract' ? 'an abstract-shaped mirror' : 'a standard oval mirror';
            return [[
                "Paket menghias {$shapeId} dengan clay. Bentuk hiasan clay di sekeliling cermin, biarkan kering, lalu warnai.",
                'Hasilnya bisa dipajang di meja rias atau dinding kamar.',
            ], [
                "A kit for decorating {$shapeEn} with clay. Build clay decorations around the edge, let them dry, then paint.",
                'Looks lovely on a dressing table or bedroom wall.',
            ]];
        }
        if (preg_match('/^DIY Mirror Painting - (Abstract|Standard)$/', $n, $m)) {
            $shapeId = $m[1] === 'Abstract' ? 'abstrak' : 'standar';
            $shapeEn = strtolower($m[1]);
            return [[
                "Paket melukis cermin bentuk {$shapeId}. Lukis bingkai atau sisi cermin dengan motif dan warna pilihanmu.",
                'Pilih warna cat Basic atau Pastel di bagian varian.',
            ], [
                "A mirror painting kit, {$shapeEn} shape. Paint the frame or edges with your own designs and colours.",
                'Choose Basic or Pastel paint in the variant section.',
            ]];
        }
        if (preg_match('/^DIY Totebag Painting - (Motif|Polos)$/', $n, $m)) {
            return $m[1] === 'Motif' ? [[
                'Paket melukis totebag dengan pola motif yang tinggal diwarnai.',
                'Tersedia model kantong dan model serut. Setelah selesai, totebag bisa langsung dipakai.',
            ], [
                'A tote bag painting kit with a printed design ready to colour.',
                'Available as a pocket tote or a drawstring bag, and ready to use once finished.',
            ]] : [[
                'Paket melukis totebag polos, jadi desainnya bebas dibuat sendiri dari awal.',
                'Cocok untuk yang ingin totebag dengan gambar atau tulisan sendiri.',
            ], [
                'A plain tote bag painting kit, so the design is entirely yours.',
                'Great if you want a tote with your own drawing or lettering.',
            ]];
        }

        return null;
    }
}
