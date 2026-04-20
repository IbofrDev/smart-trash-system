<style>
    /* Styling Card Modal */
    .custom-delete-modal .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    /* Tombol Close (X) di pojok kanan atas */
    .custom-delete-modal .btn-close {
        background-color: #f3f4f6;
        border-radius: 50%;
        padding: 0.6rem;
        opacity: 0.6;
        transition: all 0.2s;
    }
    .custom-delete-modal .btn-close:hover {
        opacity: 1;
        background-color: #e5e7eb;
        transform: rotate(90deg);
    }

    /* Lingkaran Ikon Warning */
    .delete-icon-wrapper {
        width: 80px;
        height: 80px;
        background-color: #fee2e2; /* Merah sangat muda */
        color: #ef4444; /* Merah tajam */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        box-shadow: 0 0 0 8px rgba(239, 68, 68, 0.1); /* Efek cincin di luar lingkaran */
        margin: 0 auto 1.5rem auto;
    }

    /* Tipografi */
    .custom-delete-modal h4 {
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.5px;
    }
    .custom-delete-modal p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Styling Tombol Aksi */
    .custom-delete-modal .btn-light {
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        transition: all 0.2s;
    }
    .custom-delete-modal .btn-light:hover {
        background-color: #e5e7eb;
        color: #1f2937;
        transform: translateY(-2px);
    }

    .custom-delete-modal .btn-danger {
        background-color: #ef4444;
        border-color: #ef4444;
        transition: all 0.3s;
    }
    .custom-delete-modal .btn-danger:hover {
        background-color: #dc2626;
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.35) !important;
        transform: translateY(-2px);
    }
</style>

<div class="modal fade custom-delete-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4 p-md-5 text-center position-relative">
                
                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="delete-icon-wrapper">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <h4>Konfirmasi Hapus</h4>
                <p class="mb-4 mt-3">
                    Apakah Anda yakin ingin menghapus data <br>
                    <strong id="deleteItemName" class="text-dark fs-5">Nama Item</strong><br>
                    <span class="small text-danger fw-medium d-block mt-2">Tindakan ini tidak dapat dibatalkan.</span>
                </p>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                        Batal
                    </button>
                    
                    <form id="deleteForm" action="" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-trash3-fill"></i> Ya, Hapus Data
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(url, name) {
        // 1. Ubah action form sesuai dengan URL data yang diklik
        document.getElementById('deleteForm').action = url;
        
        // 2. Ubah teks nama di dalam pop-up
        document.getElementById('deleteItemName').textContent = name;
        
        // 3. Tampilkan Pop-up Modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush