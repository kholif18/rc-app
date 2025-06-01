@extends('partial.master')

@section('title')
    Customers
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Customers
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="fas fa-plus-circle me-2"></i>Tambah Order Baru</h2>
                <p class="text-muted">Formulir untuk menambahkan order jasa ketik, desain, dan percetakan</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-outline-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </button>
            </div>
        </div>

        <form id="orderForm">
            <div class="row">
                <!-- Data Pelanggan -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Data Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="customerName" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customerPhone" class="form-label">No. Telepon</label>
                                    <input type="tel" class="form-control" id="customerPhone" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customerEmail" class="form-label">Email (opsional)</label>
                                    <input type="email" class="form-control" id="customerEmail">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Alamat (opsional)</label>
                                <textarea class="form-control" id="customerAddress" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-file-upload me-2"></i>Upload File</h5>
                        </div>
                        <div class="card-body">
                            <div id="fileUploadArea" class="file-upload-area">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted"></i>
                                <h5>Seret file ke sini atau klik untuk memilih</h5>
                                <p class="text-muted">Format file: DOC, DOCX, PDF, JPG, PNG, PSD, AI (Maks. 10MB)</p>
                                <input type="file" id="fileInput" multiple style="display: none;">
                            </div>
                            <div id="filePreview" class="file-preview">
                                <!-- File yang diupload akan muncul di sini -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Order -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i>Detail Order</h5>
                        </div>
                        <div class="card-body">
                            <!-- Jenis Layanan -->
                            <div class="mb-4">
                                <label class="form-label">Jenis Layanan</label>
                                <div class="form-check">
                                    <input class="form-check-input service-checkbox" type="checkbox" id="serviceATK" value="ATK">
                                    <label class="form-check-label" for="serviceATK">
                                        <span class="service-tag service-atk"><i class="fas fa-pencil-ruler me-1"></i>ATK</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input service-checkbox" type="checkbox" id="serviceKetik" value="Ketik">
                                    <label class="form-check-label" for="serviceKetik">
                                        <span class="service-tag service-ketik"><i class="fas fa-keyboard me-1"></i>Ketik Dokumen</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input service-checkbox" type="checkbox" id="serviceDesain" value="Desain">
                                    <label class="form-check-label" for="serviceDesain">
                                        <span class="service-tag service-desain"><i class="fas fa-palette me-1"></i>Desain Grafis</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input service-checkbox" type="checkbox" id="serviceCetak" value="Cetak">
                                    <label class="form-check-label" for="serviceCetak">
                                        <span class="service-tag service-cetak"><i class="fas fa-print me-1"></i>Percetakan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Detail Layanan -->
                            <div id="serviceDetails" class="mb-4">
                                <!-- Detail akan muncul berdasarkan jenis layanan yang dipilih -->
                                <div id="atkDetails" class="service-detail" style="display: none;">
                                    <div class="mb-3">
                                        <label for="atkType" class="form-label">Jenis ATK</label>
                                        <select class="form-select" id="atkType">
                                            <option selected disabled>Pilih jenis ATK</option>
                                            <option>Amplop</option>
                                            <option>Map Folder</option>
                                            <option>Buku Nota</option>
                                            <option>Alat Tulis</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="ketikDetails" class="service-detail" style="display: none;">
                                    <div class="mb-3">
                                        <label for="docType" class="form-label">Jenis Dokumen</label>
                                        <select class="form-select" id="docType">
                                            <option selected disabled>Pilih jenis dokumen</option>
                                            <option>Surat</option>
                                            <option>Makalah</option>
                                            <option>Skripsi/Tesis</option>
                                            <option>Laporan</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pageCount" class="form-label">Perkiraan Jumlah Halaman</label>
                                        <input type="number" class="form-control" id="pageCount" min="1">
                                    </div>
                                </div>

                                <div id="desainDetails" class="service-detail" style="display: none;">
                                    <div class="mb-3">
                                        <label for="designType" class="form-label">Jenis Desain</label>
                                        <select class="form-select" id="designType">
                                            <option selected disabled>Pilih jenis desain</option>
                                            <option>Logo</option>
                                            <option>Brosur</option>
                                            <option>Poster</option>
                                            <option>Spanduk</option>
                                            <option>Kartu Nama</option>
                                            <option>Undangan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="designSize" class="form-label">Ukuran Desain</label>
                                        <input type="text" class="form-control" id="designSize" placeholder="Contoh: A4, 50x120cm">
                                    </div>
                                </div>

                                <div id="cetakDetails" class="service-detail" style="display: none;">
                                    <div class="mb-3">
                                        <label for="printType" class="form-label">Jenis Cetakan</label>
                                        <select class="form-select" id="printType">
                                            <option selected disabled>Pilih jenis cetakan</option>
                                            <option>Brosur</option>
                                            <option>Poster</option>
                                            <option>Spanduk</option>
                                            <option>Stiker</option>
                                            <option>Undangan</option>
                                            <option>Kartu Nama</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="printQuantity" class="form-label">Jumlah Cetak</label>
                                            <input type="number" class="form-control" id="printQuantity" min="1" value="1">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="printMaterial" class="form-label">Bahan</label>
                                            <select class="form-select" id="printMaterial">
                                                <option selected disabled>Pilih bahan</option>
                                                <option>Art Paper</option>
                                                <option>HVS</option>
                                                <option>Vinyl</option>
                                                <option>Canvas</option>
                                                <option>Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deadline & Estimasi -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="deadline" class="form-label">Deadline Penyelesaian</label>
                                    <input type="datetime-local" class="form-control" id="deadline" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="estimateTime" class="form-label">Estimasi Pengerjaan</label>
                                    <select class="form-select" id="estimateTime">
                                        <option value="1">1 Hari</option>
                                        <option value="2">2 Hari</option>
                                        <option value="3" selected>3 Hari</option>
                                        <option value="5">5 Hari</option>
                                        <option value="7">1 Minggu</option>
                                        <option value="14">2 Minggu</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Status & Prioritas -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="orderStatus" class="form-label">Status Order</label>
                                    <select class="form-select" id="orderStatus">
                                        <option value="Menunggu" selected>Menunggu</option>
                                        <option value="Dikerjakan">Dikerjakan</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Diambil">Diambil</option>
                                        <option value="Batal">Batal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="priority" class="form-label">Prioritas</label>
                                    <select class="form-select" id="priority">
                                        <option value="normal" selected>Normal</option>
                                        <option value="high">Prioritas Tinggi</option>
                                        <option value="express">Express (+50% biaya)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Catatan Khusus -->
                            <div class="mb-3">
                                <label for="specialNotes" class="form-label">Catatan Khusus</label>
                                <textarea class="form-control" id="specialNotes" rows="3" placeholder="Masukkan catatan khusus untuk order ini..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection