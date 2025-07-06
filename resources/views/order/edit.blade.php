@extends('partial.master')

@section('title')
    Edit Pesanan
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('order.index') }}">Manajemen Order</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Edit Pesanan
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="mb-2">
        <h5 class="card-header">Edit Order</h5>
    </div>

    <form id="orderForm" action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
        <div class="row">
            <!-- Data Pelanggan -->
            <div class="col-md-6">
                <div class="card shadow-none bg-transparent border border-success">
                    <h5 class="section-title mb-4"><i class="bx bx-user me-2"></i>Data Pelanggan</h5>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-select" disabled>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                    </div>
                </div>

                <!-- Upload File -->
                <div class="card shadow-none bg-transparent border border-info mt-4">
                    <h5 class="section-title mb-3"><i class="bx bx-upload me-2"></i>Upload File</h5>
                    <div id="fileUploadArea" class="file-upload-area">
                        <i class="bx bx-cloud-upload fa-3x mb-3 text-muted"></i>
                        <h5>Seret file ke sini atau klik untuk memilih</h5>
                        <p id="file-info" class="text-muted">Format file: DOC, DOCX, PDF, JPG, PNG, PSD, AI (Maks. 50MB)</p>
                        <input type="file" id="fileInput" name="order_files[]" multiple style="display: none;">
                    </div>
                    <div id="filePreview" class="file-preview">
                        @foreach ($order->files ?? [] as $file)
                            @php
                                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                                $icon = match(strtolower($ext)) {
                                    'pdf' => 'fas fa-file-pdf text-danger',
                                    'doc', 'docx' => 'fas fa-file-word text-primary',
                                    'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                    'jpg', 'jpeg', 'png' => 'fas fa-file-image text-warning',
                                    default => 'fas fa-file'
                                };
                                $sizeMB = number_format($file['size'] / 1048576, 1); // byte to MB
                            @endphp
                            <div class="file-item d-flex align-items-center mb-2" data-file-id="{{ $file['id'] }}">
                                <i class="{{ $icon }} me-2"></i>
                                <span>{{ $file['name'] }}</span>
                                <small class="text-muted ms-2">{{ number_format($file['size'] / 1048576, 1) }} MB</small>
                                <button type="button" class="btn btn-sm btn-danger ms-2 remove-file" title="Hapus file">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        @endforeach
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
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Ketik" id="serviceKetik" {{ in_array('Ketik', $order->services ?? []) ? 'checked' : '' }}>
                            
                            <label class="form-check-label" for="serviceKetik">
                                <span class="service-tag service-ketik"><i class="bx bxs-keyboard me-1"></i>Ketik Dokumen</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Desain" id="serviceDesain" {{ in_array('Desain', $order->services ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="serviceDesain">
                                <span class="service-tag service-desain"><i class="bx bx-palette me-1"></i>Desain Grafis</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="Cetak" id="serviceCetak" {{ in_array('Cetak', $order->services ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="serviceCetak">
                                <span class="service-tag service-cetak"><i class="bx bx-printer me-1"></i>Percetakan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Detail Layanan -->
                    <div id="serviceDetails" class="mb-4">
                        <!-- Ketik Dokumen -->
                        <div id="ketikDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="docType" class="form-label">Jenis Dokumen</label>
                                <input type="text" class="form-control" name="docType" id="docType" 
                                    value="{{ old('docType', $order->docType) }}">
                            </div>
                            <div class="mb-3">
                                <label for="pageCount" class="form-label">Perkiraan Jumlah Halaman</label>
                                <input type="number" class="form-control" id="pageCount" name="pageCount" min="1" 
                                    value="{{ old('pageCount', $order->pageCount) }}">
                            </div>
                        </div>

                        <!-- Desain Grafis -->
                        <div id="desainDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="designType" class="form-label">Jenis Desain</label>
                                <input type="text" class="form-control" name="designType" id="designType" 
                                    value="{{ old('designType', $order->designType) }}">
                            </div>
                            <div class="mb-3">
                                <label for="designSize" class="form-label">Ukuran Desain</label>
                                <input type="text" class="form-control" name="designSize" id="designSize" 
                                    value="{{ old('designSize', $order->designSize) }}">
                            </div>
                        </div>

                        <!-- Percetakan -->
                        <div id="cetakDetails" class="service-detail" style="display: none;">
                            <div class="mb-3">
                                <label for="printType" class="form-label">Jenis Cetakan</label>
                                <input type="text" class="form-control" name="printType" id="printType" 
                                    value="{{ old('printType', $order->printType) }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="printQuantity" class="form-label">Jumlah Cetak</label>
                                    <input type="number" class="form-control" id="printQuantity" name="printQuantity" min="1" 
                                        value="{{ old('printQuantity', $order->printQuantity) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bahanCetakId" class="form-label">Bahan</label>
                                    <select class="form-control" name="bahanCetakId" id="bahanCetakId" required>
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" {{ old('bahanCetakId', $order->bahan_cetak_id ?? '') == $material->id ? 'selected' : '' }}>
                                                {{ $material->nama_bahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deadline & Estimasi -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="deadline" class="form-label">Deadline Penyelesaian</label>
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="{{ \Carbon\Carbon::parse($order->deadline)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estimateTime" class="form-label">Estimasi Pengerjaan</label>
                            <select class="form-select" id="estimateTime" name="estimateTime">
                                <option value="1" {{ $order->estimate_time == 1 ? 'selected' : '' }}>1 Hari</option>
                                <option value="2" {{ $order->estimate_time == 2 ? 'selected' : '' }}>2 Hari</option>
                                <option value="3" {{ $order->estimate_time == 3 ? 'selected' : '' }}>3 Hari</option>
                                <option value="5" {{ $order->estimate_time == 5 ? 'selected' : '' }}>5 Hari</option>
                                <option value="7" {{ $order->estimate_time == 7 ? 'selected' : '' }}>1 Minggu</option>
                                <option value="14" {{ $order->estimate_time == 14 ? 'selected' : '' }}>2 Minggu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status & Prioritas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Order</label>
                            <select class="form-select" id="status" name="status" disabled>
                                <option value="Menunggu" {{ $order->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Dikerjakan" {{ $order->status == 'Dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Diambil" {{ $order->status == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                                <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <input type="hidden" name="status" value="{{ $order->status }}">
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="normal" {{ $order->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ $order->priority == 'high' ? 'selected' : '' }}>Prioritas Tinggi</option>
                                <option value="express" {{ $order->priority == 'express' ? 'selected' : '' }}>Express (+50% biaya)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Catatan Khusus -->
                    <div class="mb-3">
                        <label for="specialNotes" class="form-label">Catatan Khusus</label>
                        <textarea class="form-control" id="specialNotes" name="specialNotes" rows="3" placeholder="Masukkan catatan khusus untuk order ini...">{{ $order->special_notes ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 text-end">
                <a class="btn btn-secondary text-white me-2" id="btn-batal">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Order</button>
            </div>
        </div>
    </form>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="uploadToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                <!-- Pesan akan diisi lewat JS -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        // Data dari database
        const orderData = {
            services: @json($order->services ?? []),
            docType: @json($order->docType ?? ''),
            pageCount: @json($order->pageCount ?? ''),
            designType: @json($order->designType ?? ''),
            designSize: @json($order->designSize ?? ''),
            printType: @json($order->printType ?? ''),
            printQuantity: @json($order->printQuantity ?? ''),
            printMaterial: @json($order->bahan_cetak_id ?? '')
        };

        // Checkbox & Show/Hide Section
        function initCheckboxBehavior() {
            const serviceMap = {
                ketik: 'ketikDetails',
                desain: 'desainDetails',
                cetak: 'cetakDetails'
            };

            Object.keys(serviceMap).forEach(service => {
                const cb = document.getElementById('service' + service.charAt(0).toUpperCase() + service.slice(1));
                if (cb && !cb.checked) {
                    hideServiceDetail(service); // Sembunyikan dan hilangkan required
                }
            });

            function showServiceDetail(service) {
                const detailId = serviceMap[service];
                const detailElement = document.getElementById(detailId);
                if (detailElement) {
                    detailElement.style.display = 'block';
                    detailElement.querySelectorAll('input, select').forEach(el => el.setAttribute('required', 'required'));
                }
            }

            function hideServiceDetail(service) {
                const detailId = serviceMap[service];
                const detailElement = document.getElementById(detailId);
                if (detailElement) {
                    detailElement.style.display = 'none';
                    detailElement.querySelectorAll('input, select').forEach(el => el.removeAttribute('required'));
                }
            }

            // Fungsi untuk memeriksa dan mengatur bahan cetak
            function setInitialBahanCetak() {
                const bahanSelect = document.getElementById('bahanCetakId');
                if (bahanSelect && orderData.printMaterial) {
                    // Tunggu sebentar untuk memastikan options sudah terload
                    setTimeout(() => {
                        const optionExists = [...bahanSelect.options].some(opt => opt.value == orderData.printMaterial);
                        if (optionExists) {
                            bahanSelect.value = orderData.printMaterial;
                        }
                    }, 100);
                }
            }

            document.querySelectorAll('.service-checkbox').forEach(cb => {
                const service = cb.id.replace('service', '').toLowerCase();

                cb.addEventListener('change', () => {
                    if (cb.checked) {
                        showServiceDetail(service);
                        if (service === 'cetak') setInitialBahanCetak();
                    } else {
                        hideServiceDetail(service);
                    }
                });

                // Inisialisasi jika sudah tercentang (misalnya pada edit)
                if (cb.checked) {
                    showServiceDetail(service);
                    if (service === 'cetak') {
                        // Panggil setInitialBahanCetak() saat halaman pertama kali dimuat
                        setInitialBahanCetak();
                    }
                }
            });

            // Jika halaman edit dan cetak adalah salah satu service yang dipilih
            if (orderData.services && orderData.services.includes('cetak')) {
                const cetakCheckbox = document.getElementById('serviceCetak');
                if (cetakCheckbox) {
                    cetakCheckbox.checked = true;
                    showServiceDetail('cetak');
                    setInitialBahanCetak();
                }
            }
        }

        // Panggil fungsi init saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            initCheckboxBehavior();
        });

        // Handle file removal dengan pengecekan null
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-file') || e.target.closest('.remove-file')) {
                const fileItem = e.target.closest('.file-item');
                if (!fileItem) return;
                
                // Jika ini file yang sudah ada di database
                if (fileItem.dataset.fileId) {
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_files[]';
                    deleteInput.value = fileItem.dataset.fileId;
                    
                    const orderForm = document.getElementById('orderForm');
                    if (orderForm) {
                        orderForm.appendChild(deleteInput);
                    }
                }
                fileItem.remove();
            }
        });

        //Upload File
        function initFileUpload() {
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const fileInfo = document.getElementById('file-info');
            const cancelUploadBtn = document.getElementById('cancelUploadBtn');

            let selectedFiles = [];

            const validTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/vnd.adobe.photoshop',
                'application/postscript'
            ];

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function updateFilePreview() {
                filePreview.innerHTML = '';
                if (selectedFiles.length === 0) {
                    filePreview.innerHTML = '<div class="text-muted">Belum ada file dipilih</div>';
                    cancelUploadBtn.style.display = 'none';
                    if (fileInfo) {
                        fileInfo.innerHTML = 'Format file: DOC, DOCX, PDF, JPG, PNG, PSD, AI (Maks. 10MB)';
                    }
                    return;
                }

                selectedFiles.forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = 'file-item d-flex align-items-center mb-2';

                    const icon = document.createElement('i');
                    if (file.type.includes('image')) icon.className = 'bx bx-image me-2';
                    else if (file.type.includes('pdf')) icon.className = 'bx bxs-file-pdf me-2 text-danger';
                    else if (file.type.includes('word')) icon.className = 'fas fa-file-word me-2 text-primary';
                    else icon.className = 'bx bx-file me-2';

                    const name = document.createElement('span');
                    name.textContent = file.name;

                    const size = document.createElement('small');
                    size.className = 'text-muted ms-2';
                    size.textContent = formatFileSize(file.size);

                    const closeBtn = document.createElement('button');
                    closeBtn.className = 'btn btn-sm btn-link text-danger ms-2 remove-file';
                    closeBtn.innerHTML = '<i class="bx bx-x"></i>';
                    closeBtn.addEventListener('click', () => removeFile(index));

                    item.append(icon, name, size, closeBtn);
                    filePreview.appendChild(item);
                });

                updateFileInput();
                updateFileInfo();
                cancelUploadBtn.style.display = 'inline-block';
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            function updateFileInfo() {
                if (!fileInfo) return;
                if (selectedFiles.length === 1) {
                    const file = selectedFiles[0];
                    fileInfo.innerHTML = `<p>File terpilih: <strong>${file.name}</strong> (${formatFileSize(file.size)})</p>`;
                } else {
                    const totalSize = selectedFiles.reduce((acc, file) => acc + file.size, 0);
                    fileInfo.innerHTML = `<p>${selectedFiles.length} file terpilih (Total: ${formatFileSize(totalSize)})</p>`;
                }
            }

            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFilePreview();
            }

            function handleFiles(files) {
                const newFiles = Array.from(files);
                let hasInvalid = false;

                const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'psd', 'ai'];
                const validFiles = newFiles.filter(file => {
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!validTypes.includes(file.type) && !allowedExtensions.includes(extension)) {
                        showToast(`File "${file.name}" tidak didukung.`);
                        hasInvalid = true;
                        return false;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        showToast(`Ukuran "${file.name}" melebihi 10 MB.`);
                        hasInvalid = true;
                        return false;
                    }

                    return true;
                });

                if (validFiles.length > 0) {
                    selectedFiles.push(...validFiles);
                    updateFilePreview(); // <- ini juga update input & info
                }

                // reset agar tidak mengandung file invalid
                fileInput.value = '';
            }

            if (fileUploadArea && fileInput && filePreview) {
                // Klik untuk pilih file
                fileUploadArea.addEventListener('click', () => fileInput.click());

                // Drag & drop
                fileUploadArea.addEventListener('dragover', e => {
                    e.preventDefault();
                    fileUploadArea.classList.add('active');
                });

                fileUploadArea.addEventListener('dragleave', () => {
                    fileUploadArea.classList.remove('active');
                });

                fileUploadArea.addEventListener('drop', e => {
                    e.preventDefault();
                    fileUploadArea.classList.remove('active');
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        handleFiles(e.dataTransfer.files);
                    }
                });

                // File input change
                fileInput.addEventListener('change', () => {
                    handleFiles(fileInput.files);
                });

                // Cancel upload button
                if (cancelUploadBtn) {
                    cancelUploadBtn.addEventListener('click', () => {
                        selectedFiles = [];
                        fileInput.value = '';
                        updateFilePreview();
                    });
                }
            }
            document.getElementById('orderForm').addEventListener('submit', function (e) {
                const totalSize = selectedFiles.reduce((acc, file) => acc + file.size, 0);
                const maxTotalSize = 50 * 1024 * 1024; // max total size 10MB

                if (totalSize > maxTotalSize) {
                    e.preventDefault();
                    showToast('Total ukuran file melebihi 50MB.');
                }
            });
        }

        function showToast(message) {
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;

            const toastElement = document.getElementById('uploadToast');
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        //Tombol Batal
        function initCancelButton() {
            const btnBatal = document.getElementById('btn-batal');
            if (!btnBatal) return;

            btnBatal.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin batal?',
                    text: "Data belum disimpan akan hilang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, batalkan',
                    cancelButtonText: 'Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('order.index') }}";
                    }
                });
            });
        }

        //Inisialisasi Semua
        document.addEventListener('DOMContentLoaded', function () {
            initCheckboxBehavior();
            initFileUpload();
            initCancelButton();
        });
    </script>
@endpush