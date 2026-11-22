{{--
    Kelola varian produk: kode, nama tampilan, gambar per varian.
    Props: $product (opsional, null di halaman tambah)
    Field: variants[<key>][id|code|name|image|remove_image|_delete]; urutan baris = urutan tampil.
--}}
@php
    $variantRows = isset($product) && $product ? $product->variants : collect();
@endphp
<div class="card mb-4 variant-admin">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Varian Produk</h6>
            <small class="text-muted">Opsional. Isi bila produk punya beberapa pilihan (warna, motif, ukuran). Gambar varian tampil di slider saat pembeli memilih varian tersebut.</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddVariant">
            <i class="bi bi-plus-lg me-1"></i>Tambah Varian
        </button>
    </div>
    <div class="card-body">
        @error('variants')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror
        @foreach($errors->getMessages() as $field => $messages)
            @if(str_starts_with($field, 'variants.'))
                <div class="alert alert-danger py-2">{{ $messages[0] }}</div>
                @break
            @endif
        @endforeach

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:90px">Gambar</th>
                        <th style="min-width:160px">Kode / SKU <span class="text-danger">*</span></th>
                        <th style="min-width:180px">Nama tampilan</th>
                        <th style="min-width:220px">Ganti gambar</th>
                        <th style="width:110px" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="variantRows">
                    @foreach($variantRows as $variant)
                        @php $key = 'e' . $variant->id; @endphp
                        <tr class="variant-row" data-key="{{ $key }}">
                            <td>
                                <input type="hidden" name="variants[{{ $key }}][id]" value="{{ $variant->id }}">
                                <input type="hidden" name="variants[{{ $key }}][_delete]" value="0" class="variant-delete-flag">
                                @if($variant->image_url)
                                    <img src="{{ $variant->image_url }}" alt="" class="rounded border" style="width:64px;height:64px;object-fit:cover">
                                @else
                                    <div class="rounded border d-flex align-items-center justify-content-center text-muted" style="width:64px;height:64px"><i class="bi bi-image"></i></div>
                                @endif
                            </td>
                            <td><input type="text" class="form-control form-control-sm" name="variants[{{ $key }}][code]" value="{{ old("variants.$key.code", $variant->code) }}" maxlength="100" required></td>
                            <td><input type="text" class="form-control form-control-sm" name="variants[{{ $key }}][name]" value="{{ old("variants.$key.name", $variant->name) }}" maxlength="255" placeholder="Mis. Motif Kucing"></td>
                            <td>
                                <input type="file" class="form-control form-control-sm" name="variants[{{ $key }}][image]" accept="image/jpeg,image/png,image/gif,image/webp">
                                @if($variant->image)
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" value="1" name="variants[{{ $key }}][remove_image]" id="rmimg{{ $variant->id }}">
                                        <label class="form-check-label small" for="rmimg{{ $variant->id }}">Hapus gambar</label>
                                    </div>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-light variant-up" title="Naikkan"><i class="bi bi-arrow-up"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger variant-remove" title="Hapus varian"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-muted small mb-0 mt-2 {{ $variantRows->isEmpty() ? '' : 'd-none' }}" id="variantEmpty">Belum ada varian. Produk dijual sebagai satu pilihan.</p>
        <div class="form-text">Gambar: JPG, PNG, GIF, WEBP, maks. 2MB per varian. Urutan baris = urutan tampil di website.</div>
    </div>
</div>

<template id="variantRowTemplate">
    <tr class="variant-row" data-key="__KEY__">
        <td><div class="rounded border d-flex align-items-center justify-content-center text-muted" style="width:64px;height:64px"><i class="bi bi-image"></i></div></td>
        <td><input type="text" class="form-control form-control-sm" name="variants[__KEY__][code]" maxlength="100" required placeholder="Mis. PBN-F.1"></td>
        <td><input type="text" class="form-control form-control-sm" name="variants[__KEY__][name]" maxlength="255" placeholder="Mis. Motif Kucing"></td>
        <td><input type="file" class="form-control form-control-sm" name="variants[__KEY__][image]" accept="image/jpeg,image/png,image/gif,image/webp"></td>
        <td class="text-end text-nowrap">
            <button type="button" class="btn btn-sm btn-light variant-up" title="Naikkan"><i class="bi bi-arrow-up"></i></button>
            <button type="button" class="btn btn-sm btn-outline-danger variant-remove" title="Hapus varian"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tbody = document.getElementById('variantRows');
    var tpl = document.getElementById('variantRowTemplate');
    var emptyText = document.getElementById('variantEmpty');
    if (!tbody || !tpl) return;

    function refreshEmpty() {
        var visible = tbody.querySelectorAll('.variant-row:not(.d-none)').length;
        if (emptyText) emptyText.classList.toggle('d-none', visible > 0);
    }

    // Tambah baris baru (key unik agar file input tidak tertukar)
    document.getElementById('btnAddVariant').addEventListener('click', function () {
        var key = 'n' + Date.now() + Math.floor(Math.random() * 1000);
        tbody.insertAdjacentHTML('beforeend', tpl.innerHTML.replace(/__KEY__/g, key));
        var input = tbody.lastElementChild.querySelector('input[type=text]');
        if (input) input.focus();
        refreshEmpty();
    });

    tbody.addEventListener('click', function (e) {
        var row = e.target.closest('.variant-row');
        if (!row) return;
        if (e.target.closest('.variant-remove')) {
            var flag = row.querySelector('.variant-delete-flag');
            if (flag) {
                // Varian tersimpan: tandai untuk dihapus saat form disimpan
                flag.value = '1';
                row.classList.add('d-none');
                row.querySelectorAll('input[required]').forEach(function (i) { i.removeAttribute('required'); });
            } else {
                row.remove();
            }
            refreshEmpty();
        }
        if (e.target.closest('.variant-up')) {
            var prev = row.previousElementSibling;
            while (prev && prev.classList.contains('d-none')) prev = prev.previousElementSibling;
            if (prev) tbody.insertBefore(row, prev);
        }
    });
});
</script>
