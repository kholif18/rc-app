@extends('partial.master')

@section('title')
    #{{ $order->id }}
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('order.index') }}">Manajemen Order</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            #{{ $order->id }}
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
    <div class="row">
        <!-- Informasi Utama -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                        @php
                            $statusClass = match($order->status) {
                                'Menunggu' => 'badge bg-secondary',
                                'Dikerjakan' => 'badge bg-warning text-dark',
                                'Selesai' => 'badge bg-success',
                                'Diambil' => 'badge bg-primary',
                                'Batal' => 'badge bg-danger',
                                default => 'badge bg-light text-dark',
                            };

                            $statusIcon = match($order->status) {
                                'Menunggu' => 'fas fa-clock',
                                'Dikerjakan' => 'fas fa-spinner',
                                'Selesai' => 'fas fa-check-circle',
                                'Diambil' => 'fas fa-motorcycle',
                                'Batal' => 'fas fa-times',
                                default => '',
                            };
                        @endphp
                        <span class="{{ $statusClass }}">
                            @if ($order->status == 'Menunggu') <i class="fas fa-clock me-1"></i> @endif
                            @if ($order->status == 'Dikerjakan') <i class="fas fa-spinner me-1"></i> @endif
                            @if ($order->status == 'Selesai') <i class="fas fa-check-circle me-1"></i> @endif
                            @if ($order->status == 'Diambil') <i class="fas fa-motorcycle me-1"></i> @endif
                            @if ($order->status == 'Batal') <i class="fas fa-close me-1"></i> @endif
                            {{ $order->status }}
                        </span>
                    </div>
                    @if($order->status === 'Batal')
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-ban me-1"></i> Order ini telah dibatalkan dan tidak bisa diubah.
                        </div>
                    @elseif($order->status === 'Diambil')
                        <div class="alert alert-success mt-3">
                            <i class="fas fa-check-circle me-1"></i> Order ini telah diambil dan tidak bisa diubah.
                        </div>
                    @endif
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Data Pelanggan</h6>
                        <table class="table table-sm">
                            <tr>
                                <th width="120">Nama</th>
                                <td>{{ $order->customer->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $order->customer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $order->customer->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $order->customer->address ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Detail Order</h6>
                        <table class="table table-sm">
                            @php
                                switch ($order->priority) {
                                    case 'express':
                                        $priorityClass = 'text-danger';
                                        break;
                                    case 'high':
                                        $priorityClass = 'text-warning';
                                        break;
                                    default:
                                        $priorityClass = 'text-success';
                                }
                            @endphp
                            <tr>
                                <th width="120">Tanggal</th>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Deadline</th>
                                <td class="fw-bold text-danger">{{ $order->deadline ? $order->deadline->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Layanan</th>
                                <td>
                                    @foreach ($order->services as $svc)
                                        <span class="service-tag service-{{ strtolower($svc) }}">
                                            @if ($svc == 'Ketik') <i class="fas fa-keyboard me-1"></i> @endif
                                            @if ($svc == 'Desain') <i class="fas fa-palette me-1"></i> @endif
                                            @if ($svc == 'Cetak') <i class="fas fa-print me-1"></i> @endif
                                            {{ $svc }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Prioritas</th>
                                <td class="{{ $priorityClass }} fw-bold">{{ $order->priority }}</td>
                            </tr>
                            <tr>
                                <th>Estimasi</th>
                                <td>{{ $order->estimate_time ?? '-' }} hari</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6><i class="fas fa-file-alt me-2"></i>Detail Layanan</h6>
                <div class="mb-4 p-3 bg-light rounded">
                    @if (in_array('Ketik', $order->services))
                        <p class="mb-2"><strong>Jumlah Halaman:</strong> {{ $order->page_count }} halaman</p>
                        <p class="mb-2"><strong>Format:</strong> {{ $order->doc_type }}</p>
                    @endif

                    @if (in_array('Desain', $order->services))
                        <p class="mb-2"><strong>Jenis Desain:</strong> {{ $order->design_type }}</p>
                        <p class="mb-2"><strong>Ukuran Desain:</strong> {{ $order->design_size }}</p>
                    @endif

                    @if (in_array('Cetak', $order->services))
                        <p class="mb-2"><strong>Jenis Cetak:</strong> {{ $order->print_type }}</p>
                        <p class="mb-2"><strong>Jumlah Cetak:</strong> {{ $order->print_quantity }} lembar</p>
                        <p class="mb-2"><strong>Bahan Cetak:</strong> {{ $order->bahanCetak->nama_bahan }}</p>
                    @endif

                    <p class="mb-0"><strong>Catatan Khusus:</strong> {{ $order->special_notes }}</p>
                </div>

                @if ($order->files && $order->files->count() > 0)
                    <h6><i class="fas fa-paperclip me-2"></i>File Terlampir</h6>
                    <!-- tampilkan daftar file di sini -->
                    <div class="mb-3">
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
                            <div class="file-item d-flex align-items-center mb-2">
                                <i class="{{ $icon }} me-2"></i>
                                <span>{{ $file->name }}</span>
                                <small class="text-muted ms-2">{{ $sizeMB }} MB</small>
                                <a href="{{ asset($file->filename) }}" class="ms-auto text-primary" download="{{ $file->name }}">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger"
                            data-bs-toggle="modal" data-bs-target="#cancelModal"
                            @if($order->status === 'Batal' || $order->status === 'Diambil') disabled @endif>
                        <i class="fas fa-times-circle me-1"></i> Batalkan Order
                    </button>

                    <div>
                        <button type="button" class="btn btn-success"
                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                @if($order->status === 'Batal' || $order->status === 'Diambil') disabled @endif>
                            <i class="fas fa-edit me-1"></i> Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline dan Catatan -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Timeline Order</h5>
                </div>
                <div class="timeline p-4">
                    {{-- Entri awal --}}
                    <div class="timeline-item">
                        <h6>Order Dibuat</h6>
                        <p class="text-muted small mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p class="small">
                            Order dibuat oleh {{ $order->user->name }}
                            <span class="badge bg-secondary">{{ $order->user->role }}</span>
                        </p>
                    </div>

                    @foreach($order->progress->sortBy('created_at') as $progress)
                        <div class="timeline-item">
                            <h6>
                                @switch($progress->status)
                                    @case('Menunggu')
                                        <i class="fas fa-clock me-1 text-warning"></i>Menunggu
                                        @break
                                    @case('Dikerjakan')
                                        <i class="fas fa-spinner me-1 text-primary"></i>Dalam Pengerjaan
                                        @break
                                    @case('Selesai')
                                        <i class="fas fa-check-circle me-1 text-success"></i>Selesai
                                        @break
                                    @case('Diambil')
                                        <i class="fas fa-motorcycle me-1 text-info"></i>Sudah Diambil
                                        @break
                                    @case('Batal')
                                        <i class="fas fa-times-circle me-1 text-danger"></i>Dibatalkan
                                        @break
                                    @default
                                        <i class="fas fa-info-circle me-1"></i>{{ $progress->status }}
                                @endswitch
                            </h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($progress->created_at)->format('d M Y, H:i') }}</p>
                                <p class="small">
                                    @if($progress->note)
                                        {{ $progress->note }} - 
                                    @endif
                                    Diubah oleh: {{ $progress->user->name ?? 'Admin' }}
                                    @if($progress->user)
                                        ({{ $progress->user->role->name ?? $progress->user->role }})
                                    @endif
                                </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-comment-dots me-2"></i>Catatan Internal</h5>
                </div>
                <form action="{{ route('orders.notes.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" name="note" placeholder="Tambah catatan internal..."></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary">Simpan Catatan</button>
                </form>
                <div class="internal-note mt-3">
                    @foreach ($order->internalNotes()->latest()->get() as $note)
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0">
                                <img src="{{ isset($note->user->avatar) && $note->user->avatar !== 'avatar.png' ? asset('storage/avatars/' . $note->user->avatar) : asset('avatar.png') }}" alt="{{ $note->user->name }}"
                                class="rounded-circle"
                                width="40"
                                height="40">
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $note->user->role ?? 'User' }} ({{ $note->user->name ?? 'Anonim' }})</h6>
                                <p class="small text-muted">{{ $note->created_at->format('d M Y, H:i') }}</p>
                                <p class="mb-0">{{ $note->note }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
    @csrf
    @method('PUT')
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-success" id="statusModalLabel">Update Status Order</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Status Saat Ini: <span class="{{ $statusClass }}">
                            @if ($order->status == 'Menunggu') <i class="fas fa-clock me-1"></i> @endif
                            @if ($order->status == 'Dikerjakan') <i class="fas fa-spinner me-1"></i> @endif
                            @if ($order->status == 'Selesai') <i class="fas fa-check-circle me-1"></i> @endif
                            @if ($order->status == 'Diambil') <i class="fas fa-motorcycle me-1"></i> @endif
                            @if ($order->status == 'Batal') <i class="fas fa-close me-1"></i> @endif{{ $order->status }}</span></label>
                            <select name="status" class="form-select" id="statusSelect" required>
                                <option value="Menunggu" {{ $order->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Dikerjakan" {{ $order->status == 'Dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Diambil" {{ $order->status == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                                <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Catatan Progress</label>
                            <textarea class="form-control" id="note" name="note" rows="3" placeholder="Tambahkan catatan progress...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
        
    <!-- Modal Batalkan Order -->
    <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
    @csrf
    @method('PUT')
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="cancelModalLabel">Batalkan Order</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan order ini?</p>
                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">Alasan Pembatalan</label>
                            <select class="form-select" id="cancelReason" name="cancel_reason" required>
                                <option selected disabled value="">Pilih alasan...</option>
                                <option value="Pelanggan membatalkan">Pelanggan membatalkan</option>
                                <option value="Tidak bisa memenuhi permintaan">Tidak bisa memenuhi permintaan</option>
                                <option value="Keterlambatan pembayaran">Keterlambatan pembayaran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cancelNotes" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" id="cancelNotes" name="cancelNotes" rows="3" placeholder="Tambahkan catatan..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Pembatalan order tidak dapat diurungkan.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Batalkan Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 detik
            toast.show();
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
    const timelines = document.querySelectorAll('.timeline');
    
    timelines.forEach(timeline => {
        const timelineItems = timeline.querySelectorAll('.timeline-item');
        let totalHeight = 0;
        
        timelineItems.forEach(item => {
        totalHeight += item.offsetHeight;
        });
        
        // Atur tinggi garis timeline
        const timelineLine = timeline;
        timelineLine.style.setProperty('--timeline-height', totalHeight + 'px');
        
        // Tambahkan CSS dinamis
        const style = document.createElement('style');
        style.textContent = `
        .timeline::before {
            height: var(--timeline-height, 100%);
        }
        `;
        document.head.appendChild(style);
    });
    });
</script>
@endpush