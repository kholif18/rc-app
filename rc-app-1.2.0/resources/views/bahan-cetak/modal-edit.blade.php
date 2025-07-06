<div class="modal fade" id="editBahanModal" tabindex="-1" aria-labelledby="editBahanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBahanModalLabel">Edit Bahan Cetak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBahanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_bahan" class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" id="edit_nama_bahan" name="nama_bahan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenis_bahan" class="form-label">Jenis Bahan</label>
                        <select class="form-select" id="edit_jenis_bahan" name="jenis_bahan" required>
                            <option value="Kertas">Kertas</option>
                            <option value="Plastik">Plastik</option>
                            <option value="Media Outdoor">Media Outdoor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_gramatur" class="form-label">Gramatur</label>
                            <input type="text" class="form-control" id="edit_gramatur" name="gramatur" placeholder="Contoh: 150 gsm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_ukuran" class="form-label">Ukuran</label>
                            <input type="text" class="form-control" id="edit_ukuran" name="ukuran" placeholder="Contoh: A4, A3, Roll" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editBahanModal = document.getElementById('editBahanModal');
        editBahanModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var modal = this;
            
            // Set form action URL
            modal.querySelector('#editBahanForm').action = '/bahan-cetak/' + id;
            
            // Populate form fields
            modal.querySelector('#edit_nama_bahan').value = button.getAttribute('data-nama');
            modal.querySelector('#edit_jenis_bahan').value = button.getAttribute('data-jenis');
            modal.querySelector('#edit_gramatur').value = button.getAttribute('data-gramatur');
            modal.querySelector('#edit_ukuran').value = button.getAttribute('data-ukuran');
        });
    });
</script>