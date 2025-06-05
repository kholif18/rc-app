<div class="modal fade" id="tambahBahanModal" tabindex="-1" aria-labelledby="tambahBahanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBahanModalLabel">Tambah Bahan Cetak Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('bahan-cetak.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bahan" class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_bahan" class="form-label">Jenis Bahan</label>
                        <select class="form-select" id="jenis_bahan" name="jenis_bahan" required>
                            <option value="" selected disabled>Pilih Jenis Bahan</option>
                            <option value="Kertas">Kertas</option>
                            <option value="Plastik">Plastik</option>
                            <option value="Media Outdoor">Media Outdoor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gramatur" class="form-label">Gramatur</label>
                            <input type="text" class="form-control" id="gramatur" name="gramatur" placeholder="Contoh: 150 gsm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ukuran" class="form-label">Ukuran</label>
                            <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Contoh: A4, A3, Roll" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>