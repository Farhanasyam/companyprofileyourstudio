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
        ['order_alamat', 'order_kota'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.required = isDelivery;
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

    // Tombol kirim selalu kembali normal saat modal ditutup (Batal / X / klik luar)
    if (orderModal) orderModal.addEventListener('hidden.bs.modal', resetSubmitButton);

    if (formOrder) {
        formOrder.addEventListener('submit', function(e) {
            e.preventDefault();
            var orderCart = collectOrderItems();
            if (orderCart.length === 0) { showSwal('Pilih minimal satu barang dan isi jumlah.', 'warning'); return; }
            var nama = fieldValue('order_nama_pemesan');
            var hp = fieldValue('order_no_hp');
            if (!nama || !hp) { showSwal('Isi Nama Pemesan dan No. HP/WhatsApp.', 'warning'); return; }
            var metode = getShippingMethod();
            var isDelivery = metode === 'kirim';
            var alamat = fieldValue('order_alamat');
            var kecamatan = fieldValue('order_kecamatan');
            var kota = fieldValue('order_kota');
            var kodePos = fieldValue('order_kode_pos');
            if (isDelivery && (!alamat || !kota)) { showSwal('Isi Alamat lengkap dan Kota/Kabupaten untuk pengiriman.', 'warning'); return; }
            var catatan = fieldValue('order_catatan');
            var cfg = window.orderWaConfig || {};
            var num = (cfg.number || '').replace(/\D/g, '');
            if (!num) { showSwal('Nomor WhatsApp untuk order belum diatur. Silakan hubungi admin.', 'warning'); return; }
            if (num.startsWith('0')) num = '62' + num.slice(1);
            else if (!num.startsWith('62')) num = '62' + num;
            var itemsText = orderCart.map(function(i) { return '• ' + i.name + ' × ' + i.qty; }).join('\n');
            var companyName = (cfg.company_name || 'Toko').trim();
            var shippingText;
            if (isDelivery) {
                var region = [kecamatan, kota, kodePos].filter(Boolean).join(', ');
                shippingText = '🚚 *Pengiriman:* Dikirim ke alamat\n🏠 *Alamat:* ' + alamat + (region ? '\n📍 ' + region : '');
            } else {
                shippingText = '🏪 *Pengiriman:* Ambil di toko';
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
            var tpl = tplRaw.replace(/\{company_name\}/g, companyName).replace(/\{items\}/g, itemsText).replace(/\{nama_pemesan\}/g, nama).replace(/\{no_hp\}/g, hp).replace(/\{pengiriman\}/g, shippingText).replace(/\{catatan\}/g, catatan || '-');
            var storeUrl = cfg.storeUrl;
            var csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var payload = {
                nama_pemesan: nama, no_hp: hp, metode_pengiriman: metode,
                alamat: isDelivery ? alamat : '', kecamatan: isDelivery ? kecamatan : '', kota: isDelivery ? kota : '', kode_pos: isDelivery ? kodePos : '',
                catatan: catatan, items: orderCart, _token: csrf
            };
            var openWa = function() {
                window.open('https://wa.me/' + num + '?text=' + encodeURIComponent(tpl), '_blank');
                var modal = bootstrap.Modal.getInstance(document.getElementById('orderManualModal'));
                if (modal) modal.hide();
                resetOrderRows();
                formOrder.reset();
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
