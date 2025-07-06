@extends('partial.master')

@section('title')
    File Manager
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            File Manager
        </a>
    </li>
@endsection

@section('content')
    <div class="row mb-4">
        {{-- Statistik Card --}}
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Total File</h3>
                    <div class="card-value">{{ $totalFiles ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Ukuran</h3>
                    <div class="card-value">{{ $totalSize ?? '0 MB' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Upload Terakhir</h3>
                    <div class="card-value">{{ $latestUpload ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="row">
            <div class="col-3">
                <h5 class="card-header">File Manager</h5>
            </div>
            <div class="col-4 mt-3">
                <form class="d-flex" method="GET" action="{{ route('files') }}">
                    <input class="form-control me-2" name="search" type="search" id="search-input" placeholder="Cari File" value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" id="search-btn" type="submit">Search</button>
                </form>
            </div>
            <div class="col-5 text-center mt-3">
                <button class="btn btn-primary" id="download-all">
                    <i class="fas fa-download"></i> Download Semua (ZIP)
                </button>
                <button class="btn btn-danger" id="delete-all">
                    <i class="fas fa-trash"></i> Hapus Semua
                </button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-4">
                <thead>
                    <tr>
                        <th style="width: 40%;">File</th>
                        <th style="width: 15%;">Size</th>
                        <th style="width: 10%;">Type</th>
                        <th style="width: 20%;">Upload Date</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
            <div id="pagination-container" class="mt-3 d-flex justify-content-center flex-wrap"></div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="feedbackToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
                    <!-- Pesan akan diganti via JS -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let lastCount = 0;
            let firstLoad = true;
            let currentPage = 1;

            function loadFilesToTable(page = currentPage) {
                currentPage = page;
                fetch(`{{ route('files.json') }}?page=${page}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Fetch error');
                        return response.json();
                    })
                    .then(res => {
                        const files = res.data;
                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        const tbody = document.querySelector('tbody.table-border-bottom-0');
                        tbody.innerHTML = '';

                        if (files.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Belum ada file yang diupload</td></tr>`;
                            lastCount = 0;
                            return;
                        }

                        lastCount = files.length;

                        files.forEach(file => {
                            const row = document.createElement('tr');
                            row.setAttribute('data-name', file.stored_name);
                            row.setAttribute('data-display', file.name); // Untuk sweetalert nama asli

                            row.innerHTML = `
                                <td><i class="fas fa-${file.icon} me-2"></i> <span class="name">${file.name}</span></td>
                                <td>${file.size}</td>
                                <td>${file.extension}</td>
                                <td>${file.uploaded_at}</td>
                                <td>
                                    <a href="/files/download/${file.stored_name}" class="btn btn-icon btn-outline-success" title="Download Langsung">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="${file.url}" target="_blank" class="btn btn-icon btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-icon btn-outline-danger delete-file-btn" title="Hapus" data-filename="${file.stored_name}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>`;
                            tbody.appendChild(row);
                        });

                        renderPagination(currentPage, lastPage); // ← Panggil render pagination
                        registerDeleteHandlers(); // ← Tombol hapus
                    })
                    .catch((error) => {
                        console.error(error); // ← tampilkan detail error
                        showToast('Gagal memuat file.', 'danger');
                    });
            }

            function renderPagination(currentPage, lastPage) {
                const container = document.getElementById('pagination-container');
                container.innerHTML = '';

                const createButton = (label, page, disabled = false, active = false) => {
                    const btn = document.createElement('button');
                    btn.className = 'btn btn-sm btn-outline-primary mx-1';
                    if (active) btn.classList.add('active');
                    if (disabled) btn.disabled = true;
                    btn.textContent = label;
                    btn.addEventListener('click', () => loadFilesToTable(page));
                    return btn;
                };

                // << Tombol First
                container.appendChild(createButton('<<', 1, currentPage === 1));

                let range = 2;
                let start = Math.max(2, currentPage - range);
                let end = Math.min(lastPage - 1, currentPage + range);

                // Halaman pertama
                container.appendChild(createButton('1', 1, false, currentPage === 1));

                // Ellipsis awal
                if (start > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.classList.add('mx-1');
                    container.appendChild(ellipsis);
                }

                // Halaman tengah
                for (let i = start; i <= end; i++) {
                    container.appendChild(createButton(i.toString(), i, false, i === currentPage));
                }

                // Ellipsis akhir
                if (end < lastPage - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.classList.add('mx-1');
                    container.appendChild(ellipsis);
                }

                // Halaman terakhir
                if (lastPage > 1) {
                    container.appendChild(createButton(lastPage.toString(), lastPage, false, currentPage === lastPage));
                }

                // >> Tombol End
                container.appendChild(createButton('>>', lastPage, currentPage === lastPage));
            }

            function showToast(message, type = 'info') {
                const toastEl = document.getElementById('feedbackToast');
                const toastBody = document.getElementById('toastBody');

                if (toastEl && toastBody) {
                    toastBody.textContent = message;

                    // Reset warna background
                    toastEl.classList.remove('bg-info', 'bg-danger', 'bg-success');
                    toastEl.classList.add(type === 'danger' ? 'bg-danger' : (type === 'success' ? 'bg-success' : 'bg-info'));

                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            }

            function registerDeleteHandlers() {
                document.querySelectorAll('.delete-file-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const tr = this.closest('tr');
                        const storedName = tr.dataset.name;
                        const originalName = tr.dataset.display;

                        Swal.fire({
                            title: 'Yakin ingin menghapus file ini?',
                            text: `"${originalName}" akan dihapus secara permanen.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteFile(storedName, () => {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `File "${originalName}" telah dihapus.`,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                });
                            }
                        });
                    });
                });
            }

            function deleteFile(filename, callback = null) {
                fetch("{{ route('files.delete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ filename })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const el = document.querySelector(`tr[data-name="${filename}"]`);
                        if (el) el.remove();
                        lastCount--;
                        if (typeof callback === 'function') callback();
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Kesalahan', 'Terjadi kesalahan saat menghapus file.', 'error'));
            }

            document.getElementById('delete-all')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus Semua File?',
                    text: "Semua file yang diupload akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus Semua',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan ke backend untuk hapus semua
                        fetch("{{ route('files.delete-all') }}", {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Semua file berhasil dihapus.', 'success');
                                loadFilesToTable(); // reload halaman 1
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus semua file.', 'error'));
                    }
                });
            });


            // 🔁 Jalankan saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function () {
                loadFilesToTable();
            });
            
            // Search
            document.getElementById('search-input')?.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('tr[data-name]').forEach(item => {
                    const fileName = item.querySelector('.name').textContent.toLowerCase();
                    item.style.display = fileName.includes(searchTerm) ? '' : 'none';
                });
            });

            // Download All
            document.getElementById('download-all')?.addEventListener('click', function () {
                window.location.href = "{{ route('files.download-all') }}";
            });

            // ⏱️ Jalankan pertama kali dan ulangi tiap 10 detik
            loadFilesToTable();
            setInterval(loadFilesToTable, 10000);
        });
    </script>

@endsection