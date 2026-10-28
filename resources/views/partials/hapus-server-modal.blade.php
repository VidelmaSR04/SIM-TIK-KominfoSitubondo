{{-- Form hidden untuk delete server --}}
<form id="hapus-server-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Modal Server Side Error (Ditengah Layar) jika ada session('hapus_error') --}}
@if(session('hapus_error'))
<div id="modal-session-error" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-outline-variant">
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">error</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak Dapat Menghapus Server</h3>
            <p class="text-sm text-gray-600 leading-relaxed mb-6">
                {{ session('hapus_error') }}
            </p>
            <button type="button" onclick="document.getElementById('modal-session-error')?.remove()" class="w-full py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium text-sm rounded-xl transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif

{{-- POPUP 1 (DITENGAH): Peringatan Server Belum Non-Aktif & Diarahkan untuk Menonaktifkan --}}
<div id="modal-perlu-nonaktif" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="perlu-nonaktif-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-outline-variant">
        <div class="p-6">
            <div class="flex items-center gap-3 text-amber-600 mb-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl text-amber-600">warning</span>
                </div>
                <div>
                    <h3 id="perlu-nonaktif-title" class="text-lg font-bold text-gray-900 leading-tight">Server Belum Dapat Dihapus</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Status perangkat harus dinonaktifkan</p>
                </div>
            </div>

            <p class="text-sm text-gray-700 leading-relaxed">
                Server <strong id="perlu-nonaktif-nama" class="text-gray-900"></strong> saat ini masih berstatus <span id="perlu-nonaktif-status-badge" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800"></span>. Sesuai aturan, data server tidak dapat dihapus selama masih aktif.
            </p>

            {{-- Peringatan khusus Server Titipan (Colocation) --}}
            <div id="perlu-nonaktif-colocation-box" class="mt-4 p-3.5 bg-amber-50/90 border border-amber-200 rounded-xl text-amber-900 text-xs hidden">
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-amber-600 text-base shrink-0 mt-0.5">info</span>
                    <div>
                        <strong class="font-semibold block mb-0.5 text-amber-950">Perangkat Colocation (Server Titipan)</strong>
                        <span>Perangkat ini merupakan server yang dititipkan oleh instansi/OPD lain. Harap koordinasikan pengembalian fisik perangkat sebelum menonaktifkan dan menghapusnya.</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-blue-50/70 border border-blue-200/80 rounded-xl text-blue-900 text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-base shrink-0">arrow_forward</span>
                <span>Silakan klik tombol di bawah untuk diarahkan ke halaman edit dan mengubah status menjadi <strong>Non-Aktif</strong>.</span>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-2.5">
            <button type="button" id="perlu-nonaktif-btn-batal" class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <a href="#" id="perlu-nonaktif-btn-edit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">edit_note</span>
                <span>Buka Form Edit & Nonaktifkan</span>
            </a>
        </div>
    </div>
</div>

{{-- POPUP 2 (DITENGAH): Konfirmasi Hapus Server (Jika sudah Non-Aktif) --}}
<div id="hapus-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden" aria-labelledby="hapus-modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-outline-variant">
        <div class="p-6">
            <div class="flex items-center gap-3 text-red-600 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl text-red-600">delete_forever</span>
                </div>
                <div>
                    <h3 id="hapus-modal-title" class="text-lg font-bold text-gray-900 leading-tight">Konfirmasi Hapus Server</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            
            <p class="text-sm text-gray-700 leading-relaxed">
                Apakah Anda yakin ingin menghapus server <span id="hapus-nama-server" class="font-bold text-gray-900"></span>?
            </p>

            <div id="hapus-colocation-warning" class="mt-4 p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs hidden">
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-amber-600 text-base shrink-0 mt-0.5">warning</span>
                    <div>
                        <strong class="font-semibold block mb-0.5 text-amber-950">Server Titipan (Colocation)</strong>
                        <span>Perangkat ini milik instansi lain. Pastikan koordinasi pengembalian fisik perangkat sebelum menghapus data dari sistem.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-2.5">
            <button type="button" id="hapus-modal-cancel" class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" id="hapus-modal-confirm" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                <span>Hapus Sekarang</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hapusForm = document.getElementById('hapus-server-form');
    
    // Elements Modal Non-Aktif (Arahkan ke Edit)
    const modalPerluNonaktif = document.getElementById('modal-perlu-nonaktif');
    const perluNonaktifNama = document.getElementById('perlu-nonaktif-nama');
    const perluNonaktifBadge = document.getElementById('perlu-nonaktif-status-badge');
    const perluNonaktifColocation = document.getElementById('perlu-nonaktif-colocation-box');
    const perluNonaktifBtnEdit = document.getElementById('perlu-nonaktif-btn-edit');
    const perluNonaktifBtnBatal = document.getElementById('perlu-nonaktif-btn-batal');

    // Elements Modal Konfirmasi Hapus
    const hapusModal = document.getElementById('hapus-modal');
    const hapusNamaServer = document.getElementById('hapus-nama-server');
    const colocationWarning = document.getElementById('hapus-colocation-warning');
    const cancelBtn = document.getElementById('hapus-modal-cancel');
    const confirmBtn = document.getElementById('hapus-modal-confirm');

    function openModal(modal) {
        if (!modal) return;
        modal.classList.remove('hidden');
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add('hidden');
    }

    // Event delegation pada document agar tetap bekerja setelah pagination / AJAX / reload
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-hapus-server');
        if (!btn) return;

        e.preventDefault();

        const nama = btn.dataset.nama || btn.getAttribute('data-nama') || 'Server';
        const status = btn.dataset.status || btn.getAttribute('data-status') || 'Pending';
        const kepemilikan = (btn.dataset.kepemilikan || btn.getAttribute('data-kepemilikan') || '').trim();
        let editUrl = btn.dataset.editUrl || btn.getAttribute('data-edit-url') || '';
        if (editUrl) {
            editUrl += (editUrl.indexOf('?') !== -1 ? '&' : '?') + 'mode=nonaktifkan#status-section';
        }
        const actionUrl = btn.dataset.action || btn.getAttribute('data-action') || btn.dataset.deleteUrl || btn.getAttribute('data-delete-url') || '';

        const isColocation = kepemilikan.toLowerCase() === 'colocation';

        // 1. Jika status belum Non-Aktif: Buka popup notif di tengah untuk diarahkan menonaktifkan server
        if (status !== 'Non-Aktif') {
            if (perluNonaktifNama) perluNonaktifNama.textContent = '"' + nama + '"';
            if (perluNonaktifBadge) perluNonaktifBadge.textContent = status;
            if (perluNonaktifBtnEdit) {
                perluNonaktifBtnEdit.href = editUrl;
                perluNonaktifBtnEdit.onclick = function (event) {
                    if (editUrl) {
                        window.location.href = editUrl;
                    }
                };
            }

            if (perluNonaktifColocation) {
                if (isColocation) {
                    perluNonaktifColocation.classList.remove('hidden');
                } else {
                    perluNonaktifColocation.classList.add('hidden');
                }
            }

            openModal(modalPerluNonaktif);
            return;
        }

        // 2. Jika status SUDAH Non-Aktif: Buka popup konfirmasi hapus server
        if (hapusForm) {
            hapusForm.action = actionUrl;
        }

        if (hapusNamaServer) {
            hapusNamaServer.textContent = '"' + nama + '"';
        }

        if (colocationWarning) {
            if (isColocation) {
                colocationWarning.classList.remove('hidden');
            } else {
                colocationWarning.classList.add('hidden');
            }
        }

        openModal(hapusModal);
    });

    // Close handlers untuk modal perlu non-aktif
    if (perluNonaktifBtnBatal) {
        perluNonaktifBtnBatal.addEventListener('click', function () {
            closeModal(modalPerluNonaktif);
        });
    }

    if (modalPerluNonaktif) {
        modalPerluNonaktif.addEventListener('click', function (e) {
            if (e.target === modalPerluNonaktif) {
                closeModal(modalPerluNonaktif);
            }
        });
    }

    // Close handlers untuk modal konfirmasi hapus
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            closeModal(hapusModal);
        });
    }

    if (hapusModal) {
        hapusModal.addEventListener('click', function (e) {
            if (e.target === hapusModal) {
                closeModal(hapusModal);
            }
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (hapusForm && hapusForm.action) {
                hapusForm.submit();
            }
        });
    }

    // Escape key listener untuk menutup modal yang sedang terbuka
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal(modalPerluNonaktif);
            closeModal(hapusModal);
        }
    });
});
</script>
