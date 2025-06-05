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
            <div class="col-5">
                <h5 class="card-header">Daftar Pelanggan</h5>
            </div>
            <div class="col-4 mt-3">
                <form class="d-flex" method="GET" action="{{ route('customers.index') }}">
                    <input class="form-control me-2" name="search" type="search" placeholder="Cari nama / No HP / alamat" aria-label="Search" value="{{ request('search') }}" />
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
            <div class="col-3 text-center">
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Tambah Pelanggan</button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="customers-table-body">
                @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        {{-- <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                        <button class="btn btn-sm btn-warning me-1" title="Edit" 
                            data-bs-toggle="modal" data-bs-target="#editCustomerModal"
                            data-id="{{ $customer->id }}"
                            data-name="{{ $customer->name }}"
                            data-phone="{{ $customer->phone }}"
                            data-email="{{ $customer->email }}"
                            data-address="{{ $customer->address }}">
                            Edit
                        </button>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
            @php
                $currentPage = $customers->currentPage();
                $lastPage = $customers->lastPage();
            @endphp

            @if ($lastPage > 1)
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{-- Tombol First --}}
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $customers->url(1) }}" aria-label="First"
                        ><i class="tf-icon bx bx-chevrons-left"></i
                        ></a>
                    </li>
                    {{-- Tombol Previous --}}
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $customers->url($currentPage - 1) }}" aria-label="Previous">
                            <i class="tf-icon bx bx-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Nomor halaman --}}
                    @for ($page = 1; $page <= $lastPage; $page++)
                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $customers->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor

                    {{-- Tombol Next --}}
                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $customers->url($currentPage + 1) }}" aria-label="Next">
                            <i class="tf-icon bx bx-chevron-right"></i>
                        </a>
                    </li>
                    {{-- Tombol Last --}}
                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $customers->url($lastPage) }}" aria-label="Last"
                        ><i class="tf-icon bx bx-chevrons-right"></i
                        ></a>
                    </li>
                </ul>
            </nav>
            @endif
        </div>
    </div>
    <!-- Modal Tambah Bahan -->
    @include('customers.modal-tambah')
    <!-- Modal Edit Bahan -->
    @include('customers.modal-edit')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 detik
                toast.show();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addCustomerForm = document.getElementById('addCustomerForm');
            
            addCustomerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                
                // Tampilkan loading indicator
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tambahkan pelanggan baru ke select/datalist
                        if (document.getElementById('customers_data')) {
                            const datalist = document.getElementById('customers_data');
                            const newOption = document.createElement('option');
                            newOption.value = `${data.customer.name} (${data.customer.phone || '-'})`;
                            newOption.dataset.id = data.customer.id;
                            datalist.appendChild(newOption);
                            
                            // Set nilai input
                            document.getElementById('customer_search').value = newOption.value;
                            document.getElementById('customer_id').value = data.customer.id;
                        }
                        
                        // Tutup modal dan reset form
                        $('#addCustomerModal').modal('hide');
                        this.reset();
                        
                        window.location.reload();
                        // Tampilkan notifikasi sukses
                        showToast('success', 'Pelanggan berhasil ditambahkan');
                    } else {
                        // Tampilkan error validasi
                        displayValidationErrors(data.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan saat menyimpan');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            });
            
            function displayValidationErrors(errors) {
                // Hapus error sebelumnya
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                // Tampilkan error baru
                for (const field in errors) {
                    const input = $(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
            
            function showToast(type, message) {
                // Implementasi toast notification sesuai library yang digunakan
                // Contoh menggunakan Bootstrap Toast
                const toast = new bootstrap.Toast(document.getElementById('toastNotification'));
                document.getElementById('toastMessage').innerHTML = message;
                document.getElementById('toastNotification').classList.remove('bg-success', 'bg-danger');
                document.getElementById('toastNotification').classList.add(`bg-${type}`);
                toast.show();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection