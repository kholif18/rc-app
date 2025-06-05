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
<div class="bs-toast toast toast-placement-ex top-0 end-0 m-2">
    @if(session('success'))
        <div
            class="bs-toast toast fade show bg-success"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Sukses</div>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bs-toast toast fade show bg-danger"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Gagal</div>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>
<div class="card">
    <div class="row">
        <div class="col-6">
            <h5 class="card-header">Tambah Order Baru</h5>
        </div>
        <div class="col-6 text-end ">
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form id="orderForm" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <!-- Data Pelanggan -->
            <div class="col-md-6">
                <div class="card shadow-none bg-transparent border border-success">
                    <h5 class="section-title mb-4"><i class="bx bx-user me-2"></i>Data Pelanggan</h5>
                    <div class="mb-3">
                        <div class="form-group">
                            <label class="form-label" for="customer_search">Cari Pelanggan</label>
                            <input type="text" 
                                id="customer_search" 
                                list="customers_data" 
                                class="form-control" autofocus
                                placeholder="Ketik nama pelanggan..."
                                value="{{ old('customer_name') }}"
                                autocomplete="off"
                                required>
                            <datalist id="customers_data">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->name }}" data-id="{{ $customer->id }}">
                                @endforeach
                            </datalist>
                            <input type="hidden" 
                                name="customer_id" 
                                id="customer_id" 
                                value="{{ old('customer_id') }}">
                        </div>
                    </div>
                    <figcaption class="blockquote-footer mt-1">Jika pelanggan belum ada/terdaftar, silakan klik tombol Add Customer</figcaption>
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
                        <button id="cancelUploadBtn" style="display: none;">Batal Upload</button>
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
                                    <label for="bahanCetakId" class="form-label">Bahan</label>
                                    <select class="form-control" name="bahanCetakId" id="bahanCetakId" required>
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" {{ old('bahanCetakId') == $material->id ? 'selected' : '' }}>
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
                <button class="btn btn-secondary text-white me-2" id="btn-batal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Order</button>
            </div>
        </div>
    </form>
</div>

    <!-- Modal Tambah Bahan -->
    @include('order.modal-tambah')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.service-checkbox');
            const orderData = {
                services: @json($order->services ?? []),
                printMaterial: @json($order->bahan_cetak_id ?? '')
            };

            // Map detail div dan input yang wajib
            const serviceMap = {
                serviceKetik: {
                    detail: document.getElementById('ketikDetails'),
                    required: ['docType', 'pageCount']
                },
                serviceDesain: {
                    detail: document.getElementById('desainDetails'),
                    required: ['designType', 'designSize']
                },
                serviceCetak: {
                    detail: document.getElementById('cetakDetails'),
                    required: ['printType', 'printQuantity', 'bahanCetakId']
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
                // Set status awal berdasarkan orderData.services
                if (orderData.services && orderData.services.length > 0) {
                    const serviceName = cb.id.replace('service', '').toLowerCase();
                    cb.checked = orderData.services.includes(serviceName);
                }

                cb.addEventListener('change', function () {
                    toggleServiceDetail(cb);
                });

                // Inisialisasi saat halaman dimuat
                toggleServiceDetail(cb);
            });

            // Set bahan cetak jika ada data
            if (orderData.printMaterial) {
                const bahanSelect = document.getElementById('bahanCetakId');
                if (bahanSelect) {
                    // Tunggu sampai options tersedia
                    const checkInterval = setInterval(() => {
                        if ([...bahanSelect.options].some(opt => opt.value == orderData.printMaterial)) {
                            bahanSelect.value = orderData.printMaterial;
                            clearInterval(checkInterval);
                        }
                    }, 100);
                }
            }
        });

        // File Upload dengan tombol close dan cancel
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const cancelUploadBtn = document.getElementById('cancelUploadBtn');

        // Array untuk menyimpan file yang dipilih user
        let selectedFiles = [];

        // Ketika area diklik, buka file picker
        fileUploadArea.addEventListener('click', () => fileInput.click());

        // Ketika file dipilih
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Tambahkan file baru ke array
                Array.from(this.files).forEach(file => {
                    selectedFiles.push(file);
                });

                // Update preview dan input
                updateFilePreview();
                
                // Tampilkan tombol cancel
                cancelUploadBtn.style.display = 'inline-block';
            }
        });

        // Format ukuran file
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Tampilkan preview file
        function updateFilePreview() {
            filePreview.innerHTML = '';

            if (selectedFiles.length === 0) {
                filePreview.innerHTML = '<div class="text-muted">Belum ada file dipilih</div>';
                cancelUploadBtn.style.display = 'none';
                return;
            }

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item d-flex align-items-center mb-2';

                // Icon file
                const fileIcon = document.createElement('i');
                if (file.type.includes('image')) {
                    fileIcon.className = 'bx bx-image me-2';
                } else if (file.type.includes('pdf')) {
                    fileIcon.className = 'bx bxs-file-pdf me-2 text-danger';
                } else if (file.type.includes('word') || file.name.match(/\.(doc|docx)$/i)) {
                    fileIcon.className = 'fas fa-file-word me-2 text-primary';
                } else {
                    fileIcon.className = 'bx bx-file me-2';
                }

                // Nama file
                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                // Ukuran file
                const fileSize = document.createElement('small');
                fileSize.className = 'text-muted ms-2';
                fileSize.textContent = formatFileSize(file.size);

                // Tombol hapus file
                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'btn btn-sm btn-link text-danger ms-2';
                closeBtn.innerHTML = '<i class="bx bx-x"></i>';
                closeBtn.title = 'Hapus file';
                closeBtn.onclick = () => removeFile(index);

                // Susun item
                fileItem.appendChild(fileIcon);
                fileItem.appendChild(fileName);
                fileItem.appendChild(fileSize);
                fileItem.appendChild(closeBtn);

                filePreview.appendChild(fileItem);
            });

            // Update input file
            updateFileInput();
        }

        // Update input file agar hanya file di selectedFiles yang dikirim
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }

        // Hapus file dari list
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFilePreview(); // otomatis update input juga
        }

        // tombol batal
        document.getElementById('btn-batal').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin batal?',
                text: "Data belum disimpan akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('order.index') }}";
                }
            })
        });

        // input datalist
        document.addEventListener('DOMContentLoaded', function() {
            const customerSearch = document.getElementById('customer_search');
            const customerIdInput = document.getElementById('customer_id');
            const datalist = document.getElementById('customers_data');
            
            // Handle ketika user memilih/menginput
            customerSearch.addEventListener('change', function() {
                const selectedName = this.value;
                const options = datalist.querySelectorAll('option');
                
                // Cari customer yang sesuai
                let found = false;
                for (let option of options) {
                    if (option.value === selectedName) {
                        customerIdInput.value = option.getAttribute('data-id');
                        found = true;
                        break;
                    }
                }
                
                // Jika tidak ditemukan, reset nilai
                if (!found) {
                    customerIdInput.value = '';
                }
            });
            
            // Validasi sebelum form submit
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!customerIdInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih pelanggan dari daftar yang tersedia');
                    customerSearch.focus();
                }
            });
        });
    </script>
@endpush