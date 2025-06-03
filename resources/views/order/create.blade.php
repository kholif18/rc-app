@extends('partial.master')

@section('title')
    Buat Pesanan
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('order.index') }}">Manajemen Order</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Buat Pesanan
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Tambah Order Baru</h2>
        </div>
        <div class="col-md-6 text-end">
            <a class="btn btn-secondary" href="{{ route('order.index') }}"><i class="bx bx-arrow-to-left me-2"></i>Kembali</a>
        </div>
    </div>

    <form id="orderForm" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <!-- Data Pelanggan -->
            <div class="col-md-6">
                <div class="card shadow-none bg-transparent border border-success">
                    <h5 class="section-title mb-4"><i class="bx bx-user me-2"></i>Data Pelanggan</h5>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Upload File -->
                <div class="card shadow-none bg-transparent border border-info mt-4">
                    <h5 class="section-title mb-3"><i class="bx bx-upload me-2"></i>Upload File</h5>
                    <div id="fileUploadArea" class="file-upload-area">
                        <i class="bx bx-cloud-upload fa-3x mb-3 text-muted"></i>
                        <h5>Seret file ke sini atau klik untuk memilih</h5>
                        <p class="text-muted">Format file: DOC, DOCX, PDF, JPG, PNG, PSD, AI (Maks. 10MB)</p>
                        <input type="file" id="fileInput" name="order_files[]" multiple style="display: none;">
                    </div>
                    <div id="filePreview" class="file-preview">
                        <!-- File yang diupload akan muncul di sini -->
                    </div>
                </div>
            </div>

            <!-- Detail Order -->
            <div class="col-md-6">
                <div class="card shadow-none bg-transparent border border-primary">
                    <h5 class="section-title mb-3"><i class="bx bx-clipboard me-2"></i>Detail Order</h5>
                    <!-- Jenis Layanan -->
                    <div class="mb-4">
                        <label class="form-label d-block">Jenis Layanan</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Ketik" id="serviceKetik">
                            <label class="form-check-label" for="serviceKetik">
                                <span class="service-tag service-ketik"><i class="bx bxs-keyboard me-1"></i>Ketik Dokumen</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Desain" id="serviceDesain">
                            <label class="form-check-label" for="serviceDesain">
                                <span class="service-tag service-desain"><i class="bx bx-palette me-1"></i>Desain Grafis</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Cetak" id="serviceCetak">
                            <label class="form-check-label" for="serviceCetak">
                                <span class="service-tag service-cetak"><i class="bx bx-printer me-1"></i>Percetakan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Detail Layanan -->
                    <div id="serviceDetails" class="mb-4">
                        <!-- Detail akan muncul berdasarkan jenis layanan yang dipilih -->
                        <div id="ketikDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="docType" class="form-label">Jenis Dokumen</label>
                                <input type="text" class="form-control" name="docType" id="docType" placeholder="Jenis Dokument" required>
                            </div>
                            <div class="mb-3">
                                <label for="pageCount" class="form-label">Perkiraan Jumlah Halaman</label>
                                <input type="number" class="form-control" id="pageCount" name="pageCount" min="1">
                            </div>
                        </div>

                        <div id="desainDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="designType" class="form-label">Jenis Desain</label>
                                <input type="text" class="form-control" name="designType" id="designType" name="designType" placeholder="Jenis Desain" required>
                            </div>
                            <div class="mb-3">
                                <label for="designSize" class="form-label">Ukuran Desain</label>
                                <input type="text" class="form-control" id="designSize" name="designSize" placeholder="Contoh: A4, 50x120cm">
                            </div>
                        </div>

                        <div id="cetakDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="printType" class="form-label">Jenis Cetakan</label>
                                <input type="text" class="form-control" name="printType" id="printType" placeholder="Jenis Cetak" required>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="printQuantity" class="form-label">Jumlah Cetak</label>
                                    <input type="number" class="form-control" id="printQuantity" name="printQuantity" min="1" value="1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="printMaterial" class="form-label">Bahan</label>
                                    <input type="text" class="form-control" name="printMaterial" id="printMaterial" placeholder="Jenis Bahan">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deadline & Estimasi -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="deadline" class="form-label">Deadline Penyelesaian</label>
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estimateTime" class="form-label">Estimasi Pengerjaan</label>
                            <select class="form-select" id="estimateTime" name="estimateTime">
                                <option value="1">1 Hari</option>
                                <option value="2">2 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="5">5 Hari</option>
                                <option value="7">1 Minggu</option>
                                <option value="14">2 Minggu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status & Prioritas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Order</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Menunggu">Menunggu</option>
                                <option value="Dikerjakan">Dikerjakan</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Diambil">Diambil</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="normal" selected>Normal</option>
                                <option value="high">Prioritas Tinggi</option>
                                <option value="express">Express (+50% biaya)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Catatan Khusus -->
                    <div class="mb-3">
                        <label for="specialNotes" class="form-label">Catatan Khusus</label>
                        <textarea class="form-control" id="specialNotes" name="specialNotes" rows="3" placeholder="Masukkan catatan khusus untuk order ini..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 text-end">
                <button type="button" class="btn btn-secondary me-2">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Order</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.service-checkbox');

            // Map detail div dan input yang wajib
            const serviceMap = {
                serviceKetik: {
                    detail: document.getElementById('ketikDetails'),
                    required: ['docType']
                },
                serviceDesain: {
                    detail: document.getElementById('desainDetails'),
                    required: ['designType']
                },
                serviceCetak: {
                    detail: document.getElementById('cetakDetails'),
                    required: ['printType', 'printMaterial']
                }
            };

            function toggleServiceDetail(checkbox) {
                const service = serviceMap[checkbox.id];
                if (!service) return;

                const { detail, required } = service;

                if (checkbox.checked) {
                    detail.style.display = 'block';
                    required.forEach(id => {
                        const input = document.getElementById(id);
                        if (input) input.setAttribute('required', true);
                    });
                } else {
                    detail.style.display = 'none';
                    required.forEach(id => {
                        const input = document.getElementById(id);
                        if (input) input.removeAttribute('required');
                    });
                }
            }

            // Pasang event listener ke setiap checkbox
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    toggleServiceDetail(cb);
                });

                // Inisialisasi saat halaman dimuat (jika sudah tercentang)
                toggleServiceDetail(cb);
            });
        });
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');

        fileUploadArea.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function() {
            filePreview.innerHTML = '';
            if (this.files.length > 0) {
                Array.from(this.files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    
                    const fileIcon = document.createElement('i');
                    if (file.type.includes('image')) {
                        fileIcon.className = 'bx bx-image me-2';
                    } else if (file.type.includes('pdf')) {
                        fileIcon.className = 'bx bxs-file-pdf me-2';
                    } else if (file.type.includes('word')) {
                        fileIcon.className = 'fas fa-file-word me-2';
                    } else {
                        fileIcon.className = 'bx bx-file me-2';
                    }
                    
                    const fileName = document.createElement('span');
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('small');
                    fileSize.className = 'text-muted ms-2';
                    fileSize.textContent = formatFileSize(file.size);
                    
                    fileItem.appendChild(fileIcon);
                    fileItem.appendChild(fileName);
                    fileItem.appendChild(fileSize);
                    filePreview.appendChild(fileItem);
                });
            }
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

    </script>
@endpush