/**
 * Script publik: modal Order WA (keranjang + lazy load produk).
 * Dipindah dari inline layout agar bisa di-cache browser. Butuh window.orderWaConfig.
 * Navbar scroll & smooth scroll sudah ditangani oleh optimized.js.
 */
document.addEventListener('DOMContentLoaded', function() {

    // Daftar item: setiap baris punya select + qty. Tombol "Tambah item baru" = clone baris.
    var orderItemsList = document.getElementById('order_items_list');
    var selectProduct = document.getElementById('order_select_product');
    var orderProductsLoaded = false;
    var maxOrderRows = 20;
    
    function escapeHtml(s) { return (s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function showSwal(msg, icon) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: icon || 'warning', title: icon === 'success' ? 'Berhasil' : (icon === 'error' ? 'Oops...' : 'Perhatian'), text: msg, confirmButtonText: 'OK', confirmButtonColor: '#d39f69' });
        } else { alert(msg); }
    }
    
    // ─── LocalStorage cart persistence ───────────────────────────────────────
    var CART_KEY = 'ys_order_cart';

    function saveCartToStorage() {
        try {
            var items = collectOrderItems();
            if (items.length > 0) {
                localStorage.setItem(CART_KEY, JSON.stringify(items));
            } else {
                localStorage.removeItem(CART_KEY);
            }
        } catch(e) {}
    }

    function clearCartStorage() {
        try { localStorage.removeItem(CART_KEY); } catch(e) {}
    }

    // Pulihkan keranjang dari localStorage setelah produk selesai di-load
    function restoreCartFromStorage() {
        var saved;
        try { saved = localStorage.getItem(CART_KEY); } catch(e) {}
        if (!saved) return;
        var items;
        try { items = JSON.parse(saved); } catch(e) {}
        if (!items || !items.length) return;
        items.forEach(function(item) {
            var key = item.key || item.id;
            if (!key || !hasOrderOption(key)) return; // produk/varian sudah tidak tersedia
            var rows = orderItemsList.querySelectorAll('.order-item-row');
            var targetRow = null;
            // Cari baris yang sudah punya produk ini
            for (var r = 0; r < rows.length; r++) {
                var s = rows[r].querySelector('.order-select');
                if (s && s.value == key) { targetRow = rows[r]; break; }
            }
            // Pakai baris kosong
            if (!targetRow) {
                for (var r = 0; r < rows.length; r++) {
                    var s = rows[r].querySelector('.order-select');
                    if (s && !s.value) { targetRow = rows[r]; break; }
                }
            }
            // Buat baris baru jika semua penuh
            if (!targetRow) {
                var addBtn = document.getElementById('order_btn_tambah_item');
                if (addBtn) addBtn.click();
                rows = orderItemsList.querySelectorAll('.order-item-row');
                targetRow = rows[rows.length - 1];
            }
            if (targetRow) {
                var sel = targetRow.querySelector('.order-select');
                var qtyIn = targetRow.querySelector('.order-qty');
                if (sel) {
                    for (var i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].value == key) { sel.selectedIndex = i; break; }
                    }
                }
                if (qtyIn) qtyIn.value = item.qty || 1;
            }
        });
        toggleDetailButtons();
    }

    // Apakah key (produk atau produk:varian) ada di daftar pilihan
    function hasOrderOption(key) {
        if (!selectProduct) return false;
        for (var i = 0; i < selectProduct.options.length; i++) {
            if (selectProduct.options[i].value == key) return true;
        }
        return false;
    }
    // ─────────────────────────────────────────────────────────────────────────

    // Helper: isi produk ke baris — cari existing, lalu pakai baris kosong, lalu buat baru
    function addProductToOrderList(pid) {
        if (!orderItemsList || !pid) return;
        pid = String(pid);
        if (!hasOrderOption(pid)) {
            // Produk bervarian dipanggil tanpa varian: minta pembeli memilih varian di daftar
            for (var o = 0; o < selectProduct.options.length; o++) {
                if (selectProduct.options[o].getAttribute('data-product-id') === pid) {
                    showSwal((window.orderWaConfig && window.orderWaConfig.msg_choose_variant) || 'Pilih varian produk ini di daftar barang.', 'info');
                    break;
                }
            }
            return;
        }
        var rows = orderItemsList.querySelectorAll('.order-item-row');
        // 1) Jika produk sudah ada di baris manapun, tambah qty saja
        for (var r = 0; r < rows.length; r++) {
            var sel = rows[r].querySelector('.order-select');
            if (sel && sel.value == pid) {
                var qtyIn = rows[r].querySelector('.order-qty');
                if (qtyIn) qtyIn.value = (parseInt(qtyIn.value, 10) || 0) + 1;
                toggleDetailButtons();
                return;
            }
        }
        // 2) Pakai baris kosong pertama yang ada (select belum dipilih)
        var emptyRow = null;
        for (var r = 0; r < rows.length; r++) {
            var sel = rows[r].querySelector('.order-select');
            if (sel && !sel.value) { emptyRow = rows[r]; break; }
        }
        var targetRow = emptyRow;
        // 3) Jika tidak ada baris kosong, tambah baris baru
        if (!targetRow) {
            var addBtn = document.getElementById('order_btn_tambah_item');
            if (addBtn) addBtn.click();
            rows = orderItemsList.querySelectorAll('.order-item-row');
            targetRow = rows[rows.length - 1];
        }
        if (targetRow) {
            var sel = targetRow.querySelector('.order-select');
            var qtyIn = targetRow.querySelector('.order-qty');
            if (sel) {
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value == pid) { sel.selectedIndex = i; break; }
                }
            }
            if (qtyIn) qtyIn.value = 1;
        }
        toggleDetailButtons();
    }

    // Lazy-load daftar produk saat modal dibuka
    // event.relatedTarget = elemen yang memicu modal (tombol keranjang)
    var orderModal = document.getElementById('orderManualModal');
    if (orderModal && selectProduct) {
        orderModal.addEventListener('show.bs.modal', function(event) {
            // Ambil product id dari tombol pemicu (relatedTarget) jika ada
            var triggerBtn = event.relatedTarget;
            var pendingPid = (triggerBtn && triggerBtn.getAttribute('data-product-id')) || window.pendingAddToOrderProductId || null;
            window.pendingAddToOrderProductId = null;

            if (orderProductsLoaded) {
                if (pendingPid) addProductToOrderList(pendingPid);
                return;
            }
            var url = (window.orderWaConfig && window.orderWaConfig.productsUrl) || '';
            if (!url) { selectProduct.innerHTML = '<option value="">-- Pilih produk --</option>'; return; }
            url += (url.indexOf('?') >= 0 ? '&' : '?') + 'locale=' + encodeURIComponent((window.orderWaConfig && window.orderWaConfig.locale) || 'id');
            selectProduct.innerHTML = '<option value="">-- Memuat... --</option>';
            fetch(url).then(function(r) { return r.json(); }).then(function(arr) {
                orderProductsLoaded = true;
                selectProduct.innerHTML = '<option value="">-- Pilih produk --</option>';
                (arr || []).forEach(function(p) {
                    var opt = document.createElement('option');
                    // key = "produkId" atau "produkId:varianId" (setiap varian = pilihan tersendiri)
                    opt.value = p.key || String(p.id);
                    opt.setAttribute('data-product-id', p.id);
                    if (p.variant_id) opt.setAttribute('data-variant-id', p.variant_id);
                    opt.setAttribute('data-name', p.name || '');
                    if (p.image) opt.setAttribute('data-image', p.image);
                    if (p.category) opt.setAttribute('data-category', p.category);
                    if (p.description) opt.setAttribute('data-description', p.description);
                    opt.textContent = p.name || '';
                    selectProduct.appendChild(opt);
                });
                // Pulihkan keranjang dari sesi sebelumnya (jika ada), lalu tambah produk baru
                restoreCartFromStorage();
                if (pendingPid) addProductToOrderList(pendingPid);
            }).catch(function() {
                selectProduct.innerHTML = '<option value="">-- Gagal memuat. Coba lagi.</option>';
            });
        });
    }
    
    // Tampilkan tombol Detail, kontrol jumlah (+/-), dan Hapus hanya jika produk sudah dipilih
    // Sekaligus simpan state keranjang ke localStorage
    function toggleDetailButtons() {
        if (!orderItemsList) return;
        var allRows = orderItemsList.querySelectorAll('.order-item-row');
        allRows.forEach(function(row) {
            var sel = row.querySelector('.order-select');
            var hasProduct = sel && sel.value;
            // Hapus tampil jika baris berisi produk, atau baris kosong tapi bukan satu-satunya
            var removeBtn = row.querySelector('.order-row-remove');
            if (removeBtn) removeBtn.classList.toggle('d-none', !hasProduct && allRows.length <= 1);
            var btn = row.querySelector('.order-btn-detail');
            if (btn) btn.classList.toggle('d-none', !hasProduct);
            row.querySelectorAll('.order-row-actions').forEach(function(el) {
                if (hasProduct) el.classList.remove('d-none');
                else el.classList.add('d-none');
            });
            // Thumbnail produk di baris
            var thumbImg = row.querySelector('.order-item-thumb__img');
            var thumbIcon = row.querySelector('.order-item-thumb__icon');
            var imgSrc = hasProduct ? (sel.options[sel.selectedIndex].getAttribute('data-image') || '') : '';
            if (thumbImg && thumbImg.getAttribute('src') !== imgSrc) {
                // Gambar gagal dimuat → onerror menyembunyikan img dan menampilkan ikon kembali
                if (imgSrc) { thumbImg.src = imgSrc; thumbImg.classList.remove('d-none'); }
                else { thumbImg.setAttribute('src', ''); thumbImg.classList.add('d-none'); }
                if (thumbIcon) thumbIcon.classList.toggle('d-none', !!imgSrc);
            }
            row.classList.toggle('is-filled', !!hasProduct);
        });
        var total = collectOrderItems().reduce(function(sum, i) { return sum + i.qty; }, 0);
        var countEl = document.getElementById('order_item_count');
        if (countEl) countEl.textContent = total + ' barang';
        var clearBtn = document.getElementById('order_btn_clear_all');
        if (clearBtn) clearBtn.classList.toggle('d-none', total === 0 && allRows.length <= 1);
        saveCartToStorage();
    }
    
    // Simpan product id saat buka modal detail (untuk tombol Masukkan keranjang)
    window.orderDetailCurrentProductId = null;
    
    // Tombol Detail: tampilkan popup nama, kategori, deskripsi singkat (sesuai bahasa)
    function openDetailModal(row) {
        var sel = row && row.querySelector('.order-select');
        if (!sel || !sel.value) return;
        window.orderDetailCurrentProductId = sel.value;
        var opt = sel.options[sel.selectedIndex];
        var name = opt.getAttribute('data-name') || opt.text;
        var category = opt.getAttribute('data-category') || '';
        var desc = opt.getAttribute('data-description') || '';
        var imgSrc = opt.getAttribute('data-image') || '';
        var imgEl = document.getElementById('order_detail_img');
        var noimgEl = document.getElementById('order_detail_noimg');
        var nameEl = document.getElementById('order_detail_name');
        var categoryEl = document.getElementById('order_detail_category');
        var descEl = document.getElementById('order_detail_desc');
        if (nameEl) nameEl.textContent = name;
        var categoryValueEl = document.getElementById('order_detail_category_value');
        if (categoryValueEl) categoryValueEl.textContent = category || '';
        if (categoryEl) categoryEl.style.display = category ? '' : 'none';
        var noDescText = document.getElementById('order_detail_no_desc') ? document.getElementById('order_detail_no_desc').textContent : '';
        if (descEl) descEl.textContent = desc || noDescText;
        if (imgEl && noimgEl) {
            if (imgSrc) { imgEl.src = imgSrc; imgEl.alt = name; imgEl.classList.remove('d-none'); noimgEl.classList.add('d-none'); }
            else { imgEl.src = ''; imgEl.classList.add('d-none'); noimgEl.classList.remove('d-none'); }
        }
        var detailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
        detailModal.show();
    }
    
    // Cek apakah produk sudah dipilih di baris lain (untuk cegah duplikat)
    function getSelectedProductIdsExcept(excludeRow) {
        var ids = [];
        if (!orderItemsList) return ids;
        orderItemsList.querySelectorAll('.order-item-row').forEach(function(row) {
            if (row === excludeRow) return;
            var sel = row.querySelector('.order-select');
            if (sel && sel.value) ids.push(sel.value);
        });
        return ids;
    }
    
    // Tambah item baru: clone baris pertama, reset pilihan & jumlah
    if (document.getElementById('order_btn_tambah_item') && orderItemsList) {
        document.getElementById('order_btn_tambah_item').addEventListener('click', function() {
            var rows = orderItemsList.querySelectorAll('.order-item-row');
            if (rows.length >= maxOrderRows) { showSwal('Maksimal ' + maxOrderRows + ' barang.', 'info'); return; }
            var first = rows[0];
            var clone = first.cloneNode(true);
            clone.removeAttribute('id');
            var cloneSel = clone.querySelector('.order-select');
            if (cloneSel) cloneSel.id = '';
            cloneSel.selectedIndex = 0;
            var qtyIn = clone.querySelector('.order-qty');
            if (qtyIn) { qtyIn.value = 1; qtyIn.setAttribute('min', '1'); }
            clone.querySelectorAll('.order-row-actions').forEach(function(el) { el.classList.add('d-none'); });
            var detailBtn = clone.querySelector('.order-btn-detail');
            if (detailBtn) detailBtn.classList.add('d-none');
            orderItemsList.appendChild(clone);
            toggleDetailButtons();
        });
    }
    
    // Hapus semua item: kosongkan daftar, sisakan satu baris
    if (document.getElementById('order_btn_clear_all') && orderItemsList) {
        document.getElementById('order_btn_clear_all').addEventListener('click', function() {
            var rows = orderItemsList.querySelectorAll('.order-item-row');
            for (var i = rows.length - 1; i >= 1; i--) rows[i].remove();
            var first = orderItemsList.querySelector('.order-item-row');
            if (first) {
                var sel = first.querySelector('.order-select');
                if (sel) sel.selectedIndex = 0;
                var qtyIn = first.querySelector('.order-qty');
                if (qtyIn) qtyIn.value = 1;
            }
            toggleDetailButtons();
        });
    }
    
    // Delegasi: +/- jumlah dan Hapus baris. Semua baris bisa dihapus; jika hanya satu baris, hapus = kosongkan baris
    if (orderItemsList) {
        function clearRow(row) {
            var sel = row.querySelector('.order-select');
            var qtyIn = row.querySelector('.order-qty');
            if (sel) sel.selectedIndex = 0;
            if (qtyIn) qtyIn.value = 1;
            toggleDetailButtons();
        }
        orderItemsList.addEventListener('change', function(e) {
            if (e.target && e.target.classList && e.target.classList.contains('order-select')) {
                var sel = e.target;
                var row = sel.closest('.order-item-row');
                if (sel.value) {
                    var used = getSelectedProductIdsExcept(row);
                    if (used.indexOf(sel.value) >= 0) {
                        showSwal((window.orderWaConfig && window.orderWaConfig.msg_duplicate_product) || 'Produk ini sudah ada di daftar.', 'warning');
                        sel.selectedIndex = 0;
                    }
                }
                toggleDetailButtons();
            }
            if (e.target && e.target.classList && e.target.classList.contains('order-qty')) {
                var row = e.target.closest('.order-item-row');
                if (!row) return;
                var v = parseInt(e.target.value, 10) || 0;
                var rows = orderItemsList.querySelectorAll('.order-item-row');
                if (v < 1 && rows.length > 1) row.remove();
                toggleDetailButtons();
            }
        });
        orderItemsList.addEventListener('click', function(e) {
            var row = e.target.closest('.order-item-row');
            if (!row) return;
            var qtyIn = row.querySelector('.order-qty');
            var rows = orderItemsList.querySelectorAll('.order-item-row');
            if (e.target.closest('.order-qty-minus') && qtyIn) {
                var v = parseInt(qtyIn.value, 10) || 0;
                if (v <= 1) {
                    if (rows.length === 1) {
                        qtyIn.value = Math.max(0, v - 1);
                    } else {
                        row.remove();
                    }
                } else {
                    qtyIn.value = v - 1;
                }
            }
            if (e.target.closest('.order-qty-plus') && qtyIn) {
                var v = parseInt(qtyIn.value, 10) || 0;
                qtyIn.value = v + 1;
            }
            if (e.target.closest('.order-row-remove')) {
                if (rows.length === 1) clearRow(row);
                else row.remove();
            }
            if (e.target.closest('.order-btn-detail')) { openDetailModal(row); return; }
            if (e.target.closest('.order-qty-minus, .order-qty-plus, .order-row-remove')) toggleDetailButtons();
        });
    }
    
    // Kumpulkan item dari semua baris (untuk submit)
    function collectOrderItems() {
        var items = [];
        if (!orderItemsList) return items;
        orderItemsList.querySelectorAll('.order-item-row').forEach(function(row) {
            var sel = row.querySelector('.order-select');
            var qtyIn = row.querySelector('.order-qty');
            if (!sel || !sel.value || !qtyIn) return;
            var qty = parseInt(qtyIn.value, 10) || 0;
            if (qty < 1) return;
            var opt = sel.options[sel.selectedIndex];
            var name = opt ? (opt.getAttribute('data-name') || opt.text) : '';
            var productId = opt ? (opt.getAttribute('data-product-id') || sel.value) : sel.value;
            var variantId = opt ? (opt.getAttribute('data-variant-id') || null) : null;
            items.push({ key: sel.value, id: productId, variant_id: variantId, name: name, qty: qty });
        });
        return items;
    }
    
    function resetOrderRows() {
        if (!orderItemsList) return;
        var rows = orderItemsList.querySelectorAll('.order-item-row');
        for (var i = 1; i < rows.length; i++) rows[i].remove();
        var first = orderItemsList.querySelector('.order-item-row');
        if (first) {
            var sel = first.querySelector('.order-select');
            if (sel) sel.selectedIndex = 0;
            var qtyIn = first.querySelector('.order-qty');
            if (qtyIn) qtyIn.value = 1;
        }
        clearCartStorage();
        toggleDetailButtons();
    }
    
    var formOrder = document.getElementById('formOrderManual');
    var btnKirimOrder = document.getElementById('btnKirimOrder');
    var btnKirimOrderHtml = '<i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp';
    function resetSubmitButton() {
        if (btnKirimOrder) { btnKirimOrder.disabled = false; btnKirimOrder.innerHTML = btnKirimOrderHtml; }
    }
    function fieldValue(id) {
        var el = document.getElementById(id);
        return el ? el.value.trim() : '';
    }

    // ===== Kolom wajib: semua kolom harus diisi (kecuali Catatan); kolom alamat hanya saat "Dikirim ke alamat" =====
    var CONTACT_FIELDS = [['order_nama_pemesan', 'Nama Pemesan'], ['order_no_hp', 'No. HP / WhatsApp']];
    // Urutan sama dengan form: wilayah (dropdown berjenjang) → kode pos → alamat lengkap
    var DELIVERY_FIELDS = [
        ['order_provinsi', 'Provinsi'], ['order_kota', 'Kota / Kabupaten'], ['order_kecamatan', 'Kecamatan'],
        ['order_kelurahan', 'Kelurahan / Desa'], ['order_kode_pos', 'Kode pos'], ['order_alamat', 'Alamat lengkap']
    ];
    function markInvalid(el, invalid) {
        el.classList.toggle('is-invalid', invalid);
        if (invalid) el.setAttribute('aria-invalid', 'true'); else el.removeAttribute('aria-invalid');
    }
    // Kembalikan daftar masalah [{el, msg}] dan tandai kolomnya merah
    function validateOrderFields(isDelivery) {
        var problems = [];
        CONTACT_FIELDS.concat(isDelivery ? DELIVERY_FIELDS : []).forEach(function(f) {
            var el = document.getElementById(f[0]);
            if (!el) return;
            var value = el.value.trim();
            var msg = '';
            if (!value) msg = f[1] + (el.tagName === 'SELECT' ? ' belum dipilih' : ' belum diisi');
            else if (f[0] === 'order_no_hp' && value.replace(/\D/g, '').length < 9) msg = 'No. HP / WhatsApp tidak valid (minimal 9 angka)';
            else if (f[0] === 'order_kode_pos' && !/^\d{5}$/.test(value)) msg = 'Kode pos harus 5 angka';
            markInvalid(el, !!msg);
            if (msg) problems.push({ el: el, msg: msg });
        });
        return problems;
    }
    function warnOrderProblems(problems) {
        var first = problems[0].el;
        var focusFirst = function() {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function() { first.focus({ preventScroll: true }); }, 300);
        };
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi data pesanan',
                html: '<p class="mb-2">Mohon lengkapi kolom berikut:</p><ul class="order-swal-list">' +
                    problems.map(function(p) { return '<li>' + escapeHtml(p.msg) + '</li>'; }).join('') + '</ul>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d39f69'
            }).then(focusFirst);
        } else {
            alert('Mohon lengkapi:\n- ' + problems.map(function(p) { return p.msg; }).join('\n- '));
            focusFirst();
        }
    }
    // Tanda merah hilang begitu pembeli mulai mengisi kolomnya
    CONTACT_FIELDS.concat(DELIVERY_FIELDS).forEach(function(f) {
        var el = document.getElementById(f[0]);
        if (el) el.addEventListener('input', function() { if (el.value.trim()) markInvalid(el, false); });
    });

    // Metode pengiriman: sembunyikan field alamat saat "Ambil di toko"
    var addressFields = document.getElementById('order_address_fields');
    function getShippingMethod() {
        var checked = formOrder && formOrder.querySelector('input[name="metode_pengiriman"]:checked');
        return checked ? checked.value : 'kirim';
    }
    function syncShippingFields() {
        if (!addressFields) return;
        var isDelivery = getShippingMethod() === 'kirim';
        addressFields.classList.toggle('d-none', !isDelivery);
        DELIVERY_FIELDS.forEach(function(f) {
            var el = document.getElementById(f[0]);
            if (!el) return;
            el.required = isDelivery;
            if (!isDelivery) markInvalid(el, false);
        });
        var pickupInfo = document.getElementById('order_pickup_info');
        if (pickupInfo) pickupInfo.classList.toggle('d-none', isDelivery);
        var catatanEl = document.getElementById('order_catatan');
        if (catatanEl) {
            catatanEl.placeholder = isDelivery
                ? 'Patokan lokasi, waktu pengiriman, permintaan khusus, dll.'
                : 'Rencana hari/jam pengambilan, permintaan khusus, dll.';
        }
    }
    if (formOrder) {
        formOrder.querySelectorAll('input[name="metode_pengiriman"]').forEach(function(radio) {
            radio.addEventListener('change', syncShippingFields);
        });
        syncShippingFields();
    }

    // ===== Dropdown wilayah berjenjang: Provinsi → Kota/Kab → Kecamatan → Kelurahan/Desa =====
    // Tiap tingkat hanya berisi wilayah di bawah pilihan sebelumnya (data Kemendagri dari /wilayah/{kode}).
    var orderRegion = (function() {
        var levels = ['order_provinsi', 'order_kota', 'order_kecamatan', 'order_kelurahan'].map(function(id) {
            return { el: document.getElementById(id), seq: 0 };
        });
        if (levels.some(function(l) { return !l.el; })) return null;
        var kodePosEl = document.getElementById('order_kode_pos');
        var baseUrl = ((window.orderWaConfig || {}).wilayahUrl || '/wilayah').replace(/\/$/, '');
        var matchUrl = (window.orderWaConfig || {}).wilayahMatchUrl || '/wilayah-cocokkan';
        var cache = {};       // kode induk ('' = daftar provinsi) → Promise daftar wilayah
        var applySeq = 0;     // membatalkan pengisian otomatis lama bila titik lokasi berganti

        function fetchChildren(kode) {
            var key = kode || '';
            if (!cache[key]) {
                cache[key] = fetch(baseUrl + (key ? '/' + key : ''), { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { if (!r.ok) throw new Error('wilayah'); return r.json(); })
                    .catch(function(err) { delete cache[key]; throw err; });
            }
            return cache[key];
        }

        function setSingleOption(sel, text) {
            sel.innerHTML = '';
            sel.appendChild(new Option(text, ''));
        }

        // Kode pos hasil pilihan kelurahan sebelumnya ikut dikosongkan bila wilayah berganti
        // (kode pos yang diketik sendiri oleh pembeli dibiarkan)
        function clearDerivedKodePos() {
            if (!kodePosEl || !kodePosEl.dataset.fromRegion) return;
            if (kodePosEl.value === kodePosEl.dataset.fromRegion) {
                kodePosEl.value = '';
                delete kodePosEl.dataset.autofill;
                kodePosEl.classList.remove('is-autofilled');
            }
            delete kodePosEl.dataset.fromRegion;
        }

        // Kosongkan tingkat i dan seterusnya (menunggu pilihan di atasnya)
        function resetFrom(i) {
            clearDerivedKodePos();
            for (var j = i; j < levels.length; j++) {
                var sel = levels[j].el;
                levels[j].seq++;
                setSingleOption(sel, sel.dataset.waiting || '');
                sel.disabled = true;
                delete sel.dataset.failed;
                delete sel.dataset.autofill;
                sel.classList.remove('is-autofilled', 'is-invalid');
            }
        }

        // Muat daftar tingkat i dari induk; abaikan hasil lama bila induk sudah diganti lagi
        function loadLevel(i, parentKode) {
            var level = levels[i], sel = level.el, seq = ++level.seq;
            sel.disabled = true;
            setSingleOption(sel, 'Memuat…');
            return fetchChildren(parentKode).then(function(list) {
                if (seq !== level.seq) throw new Error('stale');
                setSingleOption(sel, sel.dataset.placeholder || 'Pilih');
                list.forEach(function(item) {
                    var opt = new Option(item.nama, item.kode);
                    if (item.kodepos) opt.dataset.kodepos = item.kodepos;
                    sel.appendChild(opt);
                });
                delete sel.dataset.failed;
                sel.disabled = false;
                return list;
            }, function(err) {
                if (seq === level.seq) {
                    setSingleOption(sel, 'Gagal memuat — ketuk untuk coba lagi');
                    sel.dataset.failed = parentKode || 'root';
                    sel.disabled = false;
                }
                throw err;
            });
        }

        var provincesPromise = null;
        function ensureProvinces() {
            if (!provincesPromise) {
                provincesPromise = loadLevel(0, '').catch(function(err) { provincesPromise = null; throw err; });
            }
            return provincesPromise;
        }

        function fillKodePos(auto) {
            var sel = levels[3].el;
            var opt = sel.options[sel.selectedIndex];
            var kp = opt && opt.dataset.kodepos;
            if (!kodePosEl || !kp) return false;
            kodePosEl.value = kp;
            kodePosEl.dataset.fromRegion = kp;
            markInvalid(kodePosEl, false);
            if (auto) { kodePosEl.dataset.autofill = kp; kodePosEl.classList.add('is-autofilled'); }
            else { delete kodePosEl.dataset.autofill; kodePosEl.classList.remove('is-autofilled'); }
            return true;
        }

        levels.forEach(function(level, i) {
            level.el.addEventListener('change', function() {
                applySeq++; // pilihan manual menghentikan pengisian otomatis yang masih berjalan
                if (i < levels.length - 1) {
                    resetFrom(i + 1);
                    if (level.el.value) loadLevel(i + 1, level.el.value).catch(function() {});
                } else if (!fillKodePos(false)) {
                    clearDerivedKodePos();
                }
            });
            // Coba muat ulang bila sebelumnya gagal (koneksi putus)
            level.el.addEventListener('focus', function() {
                var failed = level.el.dataset.failed;
                if (!failed) return;
                if (i === 0) ensureProvinces().catch(function() {});
                else loadLevel(i, failed).catch(function() {});
            });
        });

        // ---- Pencocokan nama dari peta (OpenStreetMap) dengan nama resmi Kemendagri ----
        function norm(s) {
            return (s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '')
                .replace(/[^a-z0-9 ]/g, ' ').replace(/\s+/g, ' ').trim();
        }
        function core(s) {
            return norm(s)
                .replace(/^(provinsi|kabupaten administrasi|kota administrasi|kota adm|kabupaten|kab|kota|kecamatan|kec|kelurahan|kel|desa)\s+/, '')
                .replace(/^(daerah khusus ibukota|daerah khusus|daerah istimewa|dki|di)\s+/, '')
                .trim();
        }
        function findOption(sel, name, preferType) {
            if (!name) return null;
            var target = norm(name), targetCore = core(name);
            var options = Array.prototype.filter.call(sel.options, function(o) { return o.value; });
            var pick = function(list) { return list.length === 1 ? list[0] : null; };
            var exact = options.filter(function(o) { return norm(o.text) === target; });
            if (exact.length === 1) return exact[0];
            var byCore = options.filter(function(o) { return core(o.text) === targetCore; });
            if (byCore.length > 1 && preferType) {
                byCore = byCore.filter(function(o) { return norm(o.text).indexOf(preferType) === 0; });
            }
            if (byCore.length) return pick(byCore);
            // Terakhir: nama memuat satu sama lain, hanya bila hasilnya tunggal (hindari salah pilih)
            return pick(options.filter(function(o) {
                var c = core(o.text);
                return c.length > 3 && targetCore.length > 3 && (c.indexOf(targetCore) >= 0 || targetCore.indexOf(c) >= 0);
            }));
        }

        // Pilih otomatis berurutan dari provinsi; berhenti di tingkat yang tidak cocok (pembeli memilih sendiri).
        // Bila kecamatan/desa tidak ketemu dari nama, server mencarikan berdasarkan nama-nama lain + kode pos.
        function applyFromNames(v) {
            var token = ++applySeq;
            var result = { matched: 0, kodepos: false };
            var alive = function() { return token === applySeq; };
            function choose(i, opt) {
                var sel = levels[i].el;
                sel.value = opt.value;
                sel.dataset.autofill = opt.value;
                sel.classList.add('is-autofilled');
                markInvalid(sel, false);
                result.matched++;
                return opt.value;
            }
            function byName(i, name, type) {
                var opt = findOption(levels[i].el, name, type);
                return opt ? choose(i, opt) : '';
            }
            function byCode(i, kode) {
                var opt = kode && Array.prototype.find.call(levels[i].el.options, function(o) { return o.value === kode; });
                return opt ? choose(i, opt) : '';
            }
            function finishVillage() { result.kodepos = fillKodePos(true); return result; }

            return ensureProvinces().then(function() {
                if (!alive()) return result;
                var prov = byName(0, v.provinsi);
                if (!prov) return result;
                resetFrom(1);
                return loadLevel(1, prov).then(function() {
                    if (!alive()) return result;
                    var kab = byName(1, v.kota, v.kotaType);
                    if (!kab) return result;
                    resetFrom(2);
                    return loadLevel(2, kab).then(function() {
                        if (!alive()) return result;
                        var kec = byName(2, v.kecamatan);
                        var direct = kec
                            ? loadLevel(3, kec).then(function() { return alive() && byName(3, v.kelurahan) ? true : false; })
                            : Promise.resolve(false);
                        return direct.then(function(done) {
                            if (!alive()) return result;
                            if (done) return finishVillage();
                            var names = (v.candidates || []).concat([v.kelurahan, v.kecamatan]).filter(Boolean);
                            if (!names.length) return result;
                            var qs = 'kab=' + encodeURIComponent(kab) + (kec ? '&kec=' + encodeURIComponent(kec) : '') +
                                (v.kodepos ? '&kodepos=' + encodeURIComponent(v.kodepos) : '') +
                                names.map(function(n) { return '&nama[]=' + encodeURIComponent(n); }).join('');
                            return fetch(matchUrl + '?' + qs, { headers: { 'Accept': 'application/json' } })
                                .then(function(r) { return r.ok ? r.json() : null; })
                                .then(function(m) {
                                    if (!alive() || !m || !m.kecamatan) return result;
                                    if (!kec) {
                                        if (!byCode(2, m.kecamatan)) return result;
                                        resetFrom(3);
                                        return loadLevel(3, m.kecamatan).then(function() {
                                            return alive() && m.kelurahan && byCode(3, m.kelurahan) ? finishVillage() : result;
                                        });
                                    }
                                    return m.kelurahan && byCode(3, m.kelurahan) ? finishVillage() : result;
                                });
                        });
                    });
                });
            }).catch(function() { return result; });
        }

        function selectedName(i) {
            var sel = levels[i].el;
            return sel.value ? sel.options[sel.selectedIndex].text : '';
        }

        function reset() {
            applySeq++;
            levels[0].el.value = '';
            levels[0].el.classList.remove('is-autofilled', 'is-invalid');
            delete levels[0].el.dataset.autofill;
            resetFrom(1);
        }

        // Daftar provinsi dimuat saat form pesanan dibuka (bukan di setiap halaman)
        var modalEl = document.getElementById('orderManualModal');
        if (modalEl) modalEl.addEventListener('show.bs.modal', function() { ensureProvinces().catch(function() {}); });

        return {
            applyFromNames: applyFromNames,
            reset: reset,
            names: function() {
                return { provinsi: selectedName(0), kota: selectedName(1), kecamatan: selectedName(2), kelurahan: selectedName(3) };
            },
            villageCode: function() { return levels[3].el.value; }
        };
    })();

    // ===== Titik lokasi pengiriman: GPS pembeli / pin di peta → link Google Maps di pesan WA =====
    // Peta: Leaflet + OpenStreetMap, alamat: Nominatim (gratis, tanpa API key). Leaflet hanya dimuat saat dipakai.
    // Kelurahan/kecamatan/kota/provinsi/kode pos terisi otomatis dari titik, dan tetap bisa diedit pembeli.
    var orderLocation = (function() {
        function byId(id) { return document.getElementById(id); }
        var latInput = byId('order_latitude');
        var lngInput = byId('order_longitude');
        var panel = byId('order_location_panel');
        var mapEl = byId('order_location_map');
        var hintEl = byId('order_location_hint');
        var statusEl = byId('order_location_status');
        var coordsEl = byId('order_location_coords');
        var geocodeEl = byId('order_location_geocode');
        var linkEl = byId('order_location_link');
        var btnLocate = byId('order_btn_locate');
        var btnPick = byId('order_btn_pick_map');
        var btnClear = byId('order_btn_location_clear');
        var searchInput = byId('order_location_search');
        var btnSearch = byId('order_btn_location_search');
        var resultsEl = byId('order_location_results');
        var noteEl = byId('order_autofill_note');
        if (!latInput || !lngInput || !mapEl || !panel) return null;

        var NOMINATIM = 'https://nominatim.openstreetmap.org';
        var INDONESIA = { center: [-2.5, 118], zoom: 5 };
        var MIN_PIN_ZOOM = 14; // di bawah zoom ini ketukan peta = memperbesar, bukan menaruh pin
        var ADDRESS_FIELDS = ['order_kelurahan', 'order_kecamatan', 'order_kota', 'order_provinsi', 'order_kode_pos'];
        var map = null, marker = null, leafletPromise = null, storeCenterPromise = null;
        var geocodeTimer = null, geocodeSeq = 0;
        var btnLocateHtml = btnLocate ? btnLocate.innerHTML : '';

        function loadLeaflet() {
            if (window.L) return Promise.resolve(window.L);
            if (leafletPromise) return leafletPromise;
            leafletPromise = new Promise(function(resolve, reject) {
                var css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                css.crossOrigin = '';
                document.head.appendChild(css);
                var js = document.createElement('script');
                js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                js.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                js.crossOrigin = '';
                js.onload = function() { resolve(window.L); };
                js.onerror = function() { leafletPromise = null; reject(new Error('leaflet')); };
                document.head.appendChild(js);
            });
            return leafletPromise;
        }

        function current() {
            var lat = parseFloat(latInput.value), lng = parseFloat(lngInput.value);
            return isFinite(lat) && isFinite(lng) ? { lat: lat, lng: lng } : null;
        }
        function mapsUrl(p) { return 'https://www.google.com/maps?q=' + p.lat + ',' + p.lng; }
        function wait(ms) { return new Promise(function(r) { setTimeout(r, ms); }); }
        function getJson(url) { return fetch(url).then(function(r) { return r.ok ? r.json() : null; }); }

        // Titik awal peta = alamat toko (Pengaturan → company_address), disimpan di localStorage agar tidak dicari ulang
        function storeCenter() {
            if (storeCenterPromise) return storeCenterPromise;
            var addr = (((window.orderWaConfig || {}).store_address) || '').replace(/\s+/g, ' ').trim();
            var cacheKey = 'ys_store_center';
            try {
                var cached = JSON.parse(localStorage.getItem(cacheKey) || 'null');
                if (cached && cached.addr === addr) return (storeCenterPromise = Promise.resolve(cached.center));
            } catch (e) { /* abaikan */ }
            if (!addr) return (storeCenterPromise = Promise.resolve(null));
            // Alamat lengkap sering tak dikenali → coba lagi tanpa bagian pertama (nama jalan)
            var parts = addr.split(',');
            var queries = [addr];
            if (parts.length > 2) queries.push(parts.slice(1).join(',').trim());
            storeCenterPromise = queries.reduce(function(prev, q, i) {
                return prev.then(function(found) {
                    if (found) return found;
                    return (i ? wait(1100) : Promise.resolve())
                        .then(function() { return getJson(NOMINATIM + '/search?format=jsonv2&limit=1&countrycodes=id&q=' + encodeURIComponent(q)); })
                        .then(function(list) { return list && list[0] ? [parseFloat(list[0].lat), parseFloat(list[0].lon)] : null; });
                });
            }, Promise.resolve(null)).catch(function() { return null; }).then(function(center) {
                try { localStorage.setItem(cacheKey, JSON.stringify({ addr: addr, center: center })); } catch (e) { /* abaikan */ }
                return center;
            });
            return storeCenterPromise;
        }

        function updateHint() {
            if (!hintEl || !map) return;
            hintEl.textContent = map.getZoom() < MIN_PIN_ZOOM
                ? 'Cari alamat di atas, atau perbesar peta (ketuk area / tombol +) sampai jalan terlihat.'
                : 'Ketuk lokasi Anda di peta atau geser pin untuk menyesuaikan titik pengiriman.';
        }

        // Peta di modal/panel yang baru tampil perlu dihitung ulang ukurannya agar tidak abu-abu/terpotong
        function refresh() {
            if (!map) return;
            setTimeout(function() { if (mapEl.offsetWidth) map.invalidateSize(); }, 80);
        }

        function ensureMap() {
            return loadLeaflet().then(function(L) {
                panel.classList.remove('d-none');
                if (map) { refresh(); return map; }
                var p = current();
                map = L.map(mapEl, { scrollWheelZoom: false });
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
                }).addTo(map);
                if (p) {
                    map.setView([p.lat, p.lng], 17);
                    placeMarker(p);
                } else {
                    map.setView(INDONESIA.center, INDONESIA.zoom);
                    storeCenter().then(function(c) { if (c && !current()) map.setView(c, 13); });
                }
                map.on('click', function(e) {
                    if (map.getZoom() < MIN_PIN_ZOOM) {
                        map.setView(e.latlng, Math.min(map.getZoom() + 4, 17));
                        return;
                    }
                    setPoint(e.latlng.lat, e.latlng.lng, { pan: false });
                });
                // Roda mouse hanya men-zoom peta setelah peta diklik, agar scroll form tidak "tersangkut" di peta
                map.on('focus click', function() { map.scrollWheelZoom.enable(); });
                map.on('blur mouseout', function() { map.scrollWheelZoom.disable(); });
                map.on('zoomend', updateHint);
                updateHint();
                refresh();
                return map;
            });
        }

        function placeMarker(p) {
            if (!map || !window.L || !p) return;
            if (!marker) {
                marker = window.L.marker([p.lat, p.lng], { draggable: true, autoPan: true }).addTo(map);
                marker.on('dragend', function() {
                    var ll = marker.getLatLng();
                    setPoint(ll.lat, ll.lng, { pan: false });
                });
            } else {
                marker.setLatLng([p.lat, p.lng]);
            }
        }

        function setGeocodeStatus(state, text) {
            if (!geocodeEl) return;
            geocodeEl.className = 'order-location__geocode' + (state ? ' is-' + state : '');
            geocodeEl.innerHTML = state === 'loading' ? '<span class="spinner-border spinner-border-sm"></span> ' : '';
            geocodeEl.appendChild(document.createTextNode(text || ''));
        }

        function setPoint(lat, lng, opts) {
            opts = opts || {};
            var p = { lat: Math.round(lat * 1e6) / 1e6, lng: Math.round(lng * 1e6) / 1e6 };
            latInput.value = p.lat;
            lngInput.value = p.lng;
            if (statusEl) statusEl.classList.remove('d-none');
            if (coordsEl) {
                var acc = opts.accuracy ? Math.round(opts.accuracy) : 0;
                coordsEl.textContent = p.lat + ', ' + p.lng + (acc ? ' · akurasi ±' + acc + ' m' + (acc > 150 ? ' (geser pin bila kurang tepat)' : '') : '');
            }
            if (linkEl) linkEl.href = mapsUrl(p);
            placeMarker(p);
            if (map && opts.pan !== false) map.setView([p.lat, p.lng], Math.max(map.getZoom(), opts.zoom || 17));
            scheduleReverseGeocode(p, opts.immediate ? 0 : 500);
        }

        // Hasil format "geocodejson" Nominatim memakai tingkat administrasi OSM Indonesia:
        // level4 = provinsi, level5 = kota/kabupaten, level6 = kecamatan, level7 = kelurahan/desa
        function parseAddress(g) {
            var a = g.admin || {};
            var kota = a.level5 || g.county || g.city || '';
            var kecamatan = a.level6 || '';
            var kelurahan = a.level7 || a.level8 || '';
            if (!kelurahan) {
                // Sebagian desa hanya tercatat sebagai "city"/"district" (tanpa level7)
                var c = g.district || g.city || '';
                if (c && c !== kota && c !== kecamatan && c !== g.county) kelurahan = c;
            }
            // Petunjuk "Kota X" vs "Kabupaten X" (nama inti sama, mis. Bogor/Malang/Bandung)
            var kotaType = /^kab/i.test(kota) ? 'kabupaten' : /^kota/i.test(kota) ? 'kota'
                : (g.county && g.county === kota ? 'kabupaten' : (g.city && g.city === kota ? 'kota' : ''));
            // Nama-nama lain di sekitar titik (paling spesifik dulu) untuk mencari kecamatan/desa
            // di server bila data kecamatan di peta tidak lengkap
            var exclude = [kota, a.level4, g.state, g.county];
            var candidates = [a.level7, a.level8, g.district, g.city, a.level6, g.locality].filter(function(n, i, arr) {
                return n && exclude.indexOf(n) < 0 && !/^(RT|RW)\b/i.test(n) && arr.indexOf(n) === i;
            });
            return {
                candidates: candidates,
                provinsi: a.level4 || g.state || '',
                kota: kota,
                kotaType: kotaType,
                kecamatan: kecamatan,
                kelurahan: kelurahan,
                kodepos: (g.postcode || '').replace(/\D/g, '').slice(0, 5)
            };
        }

        function markAutofilled(el, value) {
            el.dataset.autofill = value;
            el.classList.add('is-autofilled');
            markInvalid(el, false);
        }

        // Hanya wilayah (dropdown) & kode pos yang diisi dari titik lokasi. "Alamat lengkap" (jalan, no. rumah,
        // RT/RW) sengaja tidak diisi otomatis: nama tempat terdekat di peta belum tentu alamat pembeli.
        function applyAddress(values) {
            var regionDone = orderRegion ? orderRegion.applyFromNames(values) : Promise.resolve({ matched: 0, kodepos: false });
            return regionDone.then(function(res) {
                // Kode pos dari data kelurahan; bila kelurahan tak cocok, pakai kode pos dari peta
                var kp = byId('order_kode_pos');
                if (!res.kodepos && values.kodepos && kp && (kp.value.trim() === '' || kp.value === kp.dataset.autofill)) {
                    kp.value = values.kodepos;
                    markAutofilled(kp, values.kodepos);
                }
                if (noteEl) noteEl.classList.toggle('d-none', !(res.matched || (kp && kp.classList.contains('is-autofilled'))));
                return res;
            });
        }

        function scheduleReverseGeocode(p, delay) {
            clearTimeout(geocodeTimer);
            var seq = ++geocodeSeq;
            setGeocodeStatus('loading', 'Mencari alamat dari titik…');
            // Jeda saat pin digeser berkali-kali (batas Nominatim 1 permintaan/detik)
            geocodeTimer = setTimeout(function() {
                getJson(NOMINATIM + '/reverse?format=geocodejson&addressdetails=1&zoom=18&accept-language=id&lat=' + p.lat + '&lon=' + p.lng)
                    .then(function(d) {
                        if (seq !== geocodeSeq) return;
                        var g = d && d.features && d.features[0] && d.features[0].properties && d.features[0].properties.geocoding;
                        if (!g) throw new Error('empty');
                        return applyAddress(parseAddress(g)).then(function(res) {
                            if (seq !== geocodeSeq) return;
                            if (res.matched === 4) {
                                setGeocodeStatus('ok', 'Wilayah terisi otomatis — lengkapi Alamat lengkap di bawah.');
                            } else if (res.matched > 0) {
                                setGeocodeStatus('warn', 'Sebagian wilayah terpilih otomatis. Pilih sisanya pada daftar di bawah.');
                            } else {
                                setGeocodeStatus('warn', 'Wilayah titik ini tidak dapat dipilih otomatis, silakan pilih manual.');
                            }
                        });
                    })
                    .catch(function() {
                        if (seq !== geocodeSeq) return;
                        setGeocodeStatus('warn', 'Alamat tidak dapat dibaca otomatis, silakan isi manual.');
                    });
            }, delay);
        }

        function getPosition() {
            return new Promise(function(resolve, reject) {
                navigator.geolocation.getCurrentPosition(resolve, function(err) {
                    if (err && err.code === 1) { reject(err); return; }
                    // GPS presisi gagal/terlalu lama (umum di laptop) → coba lagi dengan lokasi jaringan
                    navigator.geolocation.getCurrentPosition(resolve, reject, { enableHighAccuracy: false, timeout: 15000, maximumAge: 120000 });
                }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
            });
        }

        function openMapOrWarn() {
            ensureMap().catch(function() { showSwal('Peta gagal dimuat. Periksa koneksi internet lalu coba lagi.', 'error'); });
        }

        function locate() {
            if (!navigator.geolocation || !window.isSecureContext) {
                showSwal(!navigator.geolocation
                    ? 'Browser Anda tidak mendukung deteksi lokasi. Silakan tandai titik di peta.'
                    : 'Deteksi lokasi otomatis hanya berfungsi bila situs dibuka lewat HTTPS. Silakan tandai titik di peta.', 'warning');
                openMapOrWarn();
                return;
            }
            if (btnLocate) { btnLocate.disabled = true; btnLocate.innerHTML = '<span class="spinner-border spinner-border-sm"></span><span>Mencari lokasi…</span>'; }
            var done = function() { if (btnLocate) { btnLocate.disabled = false; btnLocate.innerHTML = btnLocateHtml; } };
            // Muat peta bersamaan dengan pencarian GPS agar lebih cepat tampil
            var mapReady = ensureMap().catch(function() { return null; });
            getPosition().then(function(pos) {
                done();
                var c = pos.coords;
                setPoint(c.latitude, c.longitude, { accuracy: c.accuracy, pan: false, immediate: true });
                mapReady.then(function(m) {
                    if (!m) return;
                    m.setView([c.latitude, c.longitude], c.accuracy > 500 ? 15 : 17);
                    placeMarker(current());
                });
            }, function(err) {
                done();
                showSwal(err && err.code === 1
                    ? 'Izin lokasi ditolak. Izinkan akses lokasi untuk situs ini (ikon gembok di samping alamat web) dan pastikan GPS/Lokasi HP aktif, atau tandai titik di peta.'
                    : 'Lokasi tidak dapat ditemukan. Pastikan GPS/Lokasi aktif, atau cari & tandai titik di peta.', 'warning');
            });
        }

        function renderResults(list) {
            resultsEl.innerHTML = '';
            if (!list.length) {
                resultsEl.innerHTML = '<div class="order-location__empty">Tempat tidak ditemukan. Coba kata kunci lain (mis. nama jalan + kota), atau tandai langsung di peta.</div>';
            }
            list.forEach(function(item) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'order-location__result';
                b.setAttribute('role', 'option');
                b.innerHTML = '<i class="bi bi-geo-alt"></i><span></span>';
                b.querySelector('span').textContent = item.display_name;
                b.addEventListener('click', function() {
                    resultsEl.classList.add('d-none');
                    setPoint(parseFloat(item.lat), parseFloat(item.lon), { zoom: 17, immediate: true });
                });
                resultsEl.appendChild(b);
            });
            resultsEl.classList.remove('d-none');
        }

        function search() {
            var q = searchInput ? searchInput.value.trim() : '';
            if (q.length < 3) { showSwal('Ketik minimal 3 huruf nama jalan / tempat.', 'warning'); return; }
            if (btnSearch) btnSearch.disabled = true;
            ensureMap()
                .then(function() { return getJson(NOMINATIM + '/search?format=jsonv2&limit=6&countrycodes=id&accept-language=id&q=' + encodeURIComponent(q)); })
                .then(function(list) { renderResults(Array.isArray(list) ? list : []); })
                .catch(function() { showSwal('Pencarian lokasi gagal. Periksa koneksi lalu coba lagi.', 'error'); })
                .then(function() { if (btnSearch) btnSearch.disabled = false; });
        }

        function forgetAutofill() {
            ADDRESS_FIELDS.forEach(function(id) {
                var el = byId(id);
                if (!el) return;
                delete el.dataset.autofill;
                el.classList.remove('is-autofilled');
            });
            if (noteEl) noteEl.classList.add('d-none');
        }

        // Hapus titik: isi alamat dibiarkan (sudah bisa diedit), hanya titik & penanda otomatis yang dihapus
        function clear() {
            latInput.value = '';
            lngInput.value = '';
            geocodeSeq++;
            clearTimeout(geocodeTimer);
            if (statusEl) statusEl.classList.add('d-none');
            setGeocodeStatus('', '');
            if (marker && map) map.removeLayer(marker);
            marker = null;
            forgetAutofill();
        }

        function reset() {
            clear();
            panel.classList.add('d-none');
            if (resultsEl) { resultsEl.classList.add('d-none'); resultsEl.innerHTML = ''; }
            if (searchInput) searchInput.value = '';
        }

        // Pembeli mengedit kolom → kolom itu tidak lagi dianggap hasil otomatis (tidak akan ditimpa/ditandai)
        ADDRESS_FIELDS.forEach(function(id) {
            var el = byId(id);
            if (!el) return;
            el.addEventListener('input', function() {
                if (el.value !== el.dataset.autofill) {
                    delete el.dataset.autofill;
                    el.classList.remove('is-autofilled');
                }
            });
        });

        if (btnLocate) btnLocate.addEventListener('click', locate);
        if (btnPick) btnPick.addEventListener('click', openMapOrWarn);
        if (btnClear) btnClear.addEventListener('click', clear);
        if (btnSearch) btnSearch.addEventListener('click', search);
        if (searchInput) searchInput.addEventListener('keydown', function(e) {
            // Enter di kotak cari jangan sampai mengirim form order
            if (e.key === 'Enter') { e.preventDefault(); search(); }
        });
        var modalEl = byId('orderManualModal');
        if (modalEl) modalEl.addEventListener('shown.bs.modal', refresh);
        document.querySelectorAll('input[name="metode_pengiriman"]').forEach(function(radio) {
            radio.addEventListener('change', refresh);
        });

        return { get: current, mapsUrl: mapsUrl, reset: reset };
    })();

    // Tombol kirim selalu kembali normal saat modal ditutup (Batal / X / klik luar)
    if (orderModal) orderModal.addEventListener('hidden.bs.modal', resetSubmitButton);

    if (formOrder) {
        formOrder.addEventListener('submit', function(e) {
            e.preventDefault();
            var orderCart = collectOrderItems();
            if (orderCart.length === 0) { showSwal('Pilih minimal satu barang dan isi jumlah.', 'warning'); return; }
            var nama = fieldValue('order_nama_pemesan');
            var hp = fieldValue('order_no_hp');
            var metode = getShippingMethod();
            var isDelivery = metode === 'kirim';
            var problems = validateOrderFields(isDelivery);
            if (problems.length) { warnOrderProblems(problems); return; }
            var alamat = fieldValue('order_alamat');
            // Dropdown wilayah berisi kode; nama untuk pesan WA diambil dari teks pilihan
            var region = orderRegion ? orderRegion.names() : {};
            var kelurahan = region.kelurahan || '';
            var kecamatan = region.kecamatan || '';
            var kota = region.kota || '';
            var provinsi = region.provinsi || '';
            var kodePos = fieldValue('order_kode_pos');
            var catatan = fieldValue('order_catatan');
            var point = isDelivery && orderLocation ? orderLocation.get() : null;
            var cfg = window.orderWaConfig || {};
            var num = (cfg.number || '').replace(/\D/g, '');
            if (!num) { showSwal('Nomor WhatsApp untuk order belum diatur. Silakan hubungi admin.', 'warning'); return; }
            if (num.startsWith('0')) num = '62' + num.slice(1);
            else if (!num.startsWith('62')) num = '62' + num;
            // Pesan WA tanpa emoji: daftar bernomor, jumlah di baris sendiri (nama produk sering memuat angka)
            var totalQty = orderCart.reduce(function(sum, i) { return sum + (parseInt(i.qty, 10) || 0); }, 0);
            var itemsText = orderCart.map(function(i, idx) {
                return (idx + 1) + '. ' + i.name + '\n    Jumlah: ' + i.qty;
            }).join('\n') + '\n_Total: ' + orderCart.length + ' produk, ' + totalQty + ' item_';
            var companyName = (cfg.company_name || 'Toko').trim();
            var shippingText;
            if (isDelivery) {
                var areaLine = [kelurahan && 'Kel. ' + kelurahan, kecamatan && 'Kec. ' + kecamatan].filter(Boolean).join(', ');
                var cityLine = [kota, [provinsi, kodePos].filter(Boolean).join(' ')].filter(Boolean).join(', ');
                shippingText = ['Metode: Dikirim ke alamat', 'Alamat: ' + alamat, areaLine, cityLine]
                    .concat(point ? ['Titik lokasi: ' + orderLocation.mapsUrl(point)] : [])
                    .filter(Boolean).join('\n');
            } else {
                shippingText = 'Metode: Ambil di toko';
            }
            var tplRaw = cfg.template || '';
            // Template lama tanpa {pengiriman}: sisipkan setelah baris yang memuat {no_hp}
            if (tplRaw.indexOf('{pengiriman}') < 0) {
                var lines = tplRaw.split('\n');
                var hpIdx = lines.findIndex(function(l) { return l.indexOf('{no_hp}') >= 0; });
                if (hpIdx >= 0) lines.splice(hpIdx + 1, 0, '{pengiriman}');
                else lines.push('', '{pengiriman}');
                tplRaw = lines.join('\n');
            }
            // Pengganti berupa fungsi agar karakter "$" dari ketikan pembeli tidak dianggap pola khusus
            var placeholders = {
                company_name: companyName, items: itemsText, nama_pemesan: nama,
                no_hp: hp, pengiriman: shippingText, catatan: catatan || '-'
            };
            var tpl = tplRaw.replace(/\{(company_name|items|nama_pemesan|no_hp|pengiriman|catatan)\}/g, function(m, key) {
                return placeholders[key];
            });
            var storeUrl = cfg.storeUrl;
            var csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var payload = {
                nama_pemesan: nama, no_hp: hp, metode_pengiriman: metode,
                alamat: isDelivery ? alamat : '', kode_pos: isDelivery ? kodePos : '',
                kode_wilayah: isDelivery && orderRegion ? orderRegion.villageCode() : '',
                latitude: point ? point.lat : null, longitude: point ? point.lng : null,
                catatan: catatan, items: orderCart, _token: csrf
            };
            var openWa = function() {
                window.open('https://wa.me/' + num + '?text=' + encodeURIComponent(tpl), '_blank');
                var modal = bootstrap.Modal.getInstance(document.getElementById('orderManualModal'));
                if (modal) modal.hide();
                resetOrderRows();
                formOrder.reset();
                formOrder.querySelectorAll('.is-invalid').forEach(function(el) { markInvalid(el, false); });
                if (orderLocation) orderLocation.reset();
                if (orderRegion) orderRegion.reset();
                syncShippingFields();
            };
            if (storeUrl && csrf) {
                if (btnKirimOrder) { btnKirimOrder.disabled = true; btnKirimOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...'; }
                fetch(storeUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(payload) })
                    .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
                    .then(function(res) {
                        resetSubmitButton();
                        if (res.ok) {
                            openWa();
                        } else {
                            showSwal((res.data && res.data.message) || 'Order tidak dapat disimpan. Silakan coba lagi.', 'error');
                        }
                    })
                    .catch(function() {
                        resetSubmitButton();
                        showSwal('Koneksi gagal. Order belum dikirim, silakan coba lagi.', 'error');
                    });
            } else {
                openWa();
            }
        });
    }
});
