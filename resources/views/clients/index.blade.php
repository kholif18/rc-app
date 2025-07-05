<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - Ravaa Creative</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ $setting?->favicon ? asset('storage/favicon/' . $setting->favicon) : asset('favicon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: white;
            overflow-x: hidden;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .upload-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 30px;
            transition: all 0.3s ease;
        }
        
        .upload-container:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }
        
        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 25px;
        }
        
        .upload-area:hover {
            border-color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.05);
        }
        
        .upload-area.active {
            border-color: #4CAF50;
            background: rgba(76, 175, 80, 0.05);
        }
        
        .upload-icon {
            font-size: 64px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .upload-text h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        
        .upload-text p {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 20px;
        }
        
        .browse-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .browse-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        #file-input {
            display: none;
        }
        
        .file-info {
            text-align: center;
            margin-top: 15px;
            min-height: 24px;
        }
        
        .file-info p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .instructions {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .instructions h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instructions ul {
            list-style: none;
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
        }
        
        .instructions li i {
            color: #4CAF50;
        }
        
        .file-types {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }
        
        .file-type {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 8px 15px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .progress-container {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
            display: none;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            width: 0%;
            transition: width 0.4s ease;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-btn {
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .upload-btn {
            background: #4CAF50;
            color: white;
        }
        
        .upload-btn:hover {
            background: #43A047;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .reset-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        .reset-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        /* Responsiveness */
        @media (max-width: 768px) {
            .upload-container {
                padding: 20px;
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            .upload-text h2 {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-print"></i> Ravaa Creative</h1>
            <p>Upload file Anda melalui jaringan lokal tanpa menggunakan internet</p>
        </header>
        
        <main class="upload-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('client.upload.store') }}" enctype="multipart/form-data" id="upload-form">
                @csrf
                <div class="upload-area" id="upload-area">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <h2>Seret & Lepaskan File</h2>
                        <p>Atau klik tombol di bawah untuk memilih file dari perangkat Anda</p>
                        <input type="file" id="file-input" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" required>
                        <button type="button" class="browse-btn">Pilih File</button>
                    </div>
                    <div class="file-info" id="file-info">
                        <p>Format yang didukung: PDF, JPG, PNG, DOC, DOCX</p>
                    </div>
                </div>
                
                <div class="file-types">
                    <div class="file-type"><i class="far fa-file-pdf"></i> PDF</div>
                    <div class="file-type"><i class="far fa-file-image"></i> JPG</div>
                    <div class="file-type"><i class="far fa-file-image"></i> PNG</div>
                    <div class="file-type"><i class="far fa-file-word"></i> DOC</div>
                    <div class="file-type"><i class="far fa-file-word"></i> DOCX</div>
                </div>
                
                <div class="progress-container" id="progress-container">
                    <div class="progress-bar" id="progress-bar"></div>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="action-btn upload-btn" id="upload-btn"><i class="fas fa-upload"></i> Upload File</button>
                    <button type="button" class="action-btn reset-btn" id="reset-btn"><i class="fas fa-redo"></i> Reset</button>
                </div>
            </form>
            <div class="instructions">
                <h3><i class="fas fa-info-circle"></i> Petunjuk Upload</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Pastikan file dalam format yang didukung</li>
                    <li><i class="fas fa-check-circle"></i> Maksimal ukuran file: 50MB</li>
                    <li><i class="fas fa-check-circle"></i> Untuk hasil terbaik, gunakan format PDF</li>
                    <li><i class="fas fa-check-circle"></i> File akan diproses secara lokal tanpa internet</li>
                </ul>
            </div>
        </main>
        
        <footer>
            <p>Sistem Upload File Lokal &copy; <script>
            document.write(new Date().getFullYear());
            </script> Ravaa Creative</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('file-input');
            const fileInfo = document.getElementById('file-info');
            const browseBtn = document.querySelector('.browse-btn');
            const uploadForm = document.getElementById('upload-form');
            const resetBtn = document.getElementById('reset-btn');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');

            let selectedFiles = [];

            const validTypes = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            // === Drag and Drop Handling ===
            uploadArea.addEventListener('dragover', e => {
                e.preventDefault();
                uploadArea.classList.add('active');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('active');
            });

            uploadArea.addEventListener('drop', e => {
                e.preventDefault();
                uploadArea.classList.remove('active');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFiles(e.dataTransfer.files);
                }
            });

            // === File Browse Handling ===
            browseBtn.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    handleFiles(fileInput.files);
                }
            });

            // === Reset Button ===
            resetBtn.addEventListener('click', resetUpload);

            // === File Handling & Validation ===
            function handleFiles(files) {
                selectedFiles = Array.from(files);

                const invalid = selectedFiles.filter(file =>
                    !validTypes.includes(file.type) || file.size > 50 * 1024 * 1024
                );

                if (invalid.length > 0) {
                    showError('Beberapa file tidak valid. Pastikan format dan ukuran file benar!');
                    fileInput.value = '';
                    selectedFiles = [];
                    return;
                }

                if (selectedFiles.length === 1) {
                    const file = selectedFiles[0];
                    fileInfo.innerHTML = `<p>File terpilih: <strong>${file.name}</strong> (${formatFileSize(file.size)})</p>`;
                } else {
                    const totalSize = selectedFiles.reduce((acc, file) => acc + file.size, 0);
                    fileInfo.innerHTML = `<p>${selectedFiles.length} file terpilih (Total: ${formatFileSize(totalSize)})</p>`;
                }
            }

            // === Form Submit with Progress ===
            uploadForm.addEventListener('submit', function (e) {
                e.preventDefault();

                if (selectedFiles.length === 0) {
                    showError('Silakan pilih file terlebih dahulu!');
                    return;
                }

                const formData = new FormData();
                selectedFiles.forEach(file => formData.append('files[]', file));

                const xhr = new XMLHttpRequest();
                xhr.open('POST', uploadForm.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';

                xhr.upload.addEventListener('progress', e => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = `${percent}%`;
                    }
                });

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Upload Berhasil',
                            text: 'File berhasil diupload!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        resetUpload();
                    } else {
                        showError('Terjadi kesalahan saat mengupload file.');
                    }
                    progressContainer.style.display = 'none';
                };

                xhr.onerror = () => {
                    showError('Upload gagal karena kesalahan jaringan.');
                    progressContainer.style.display = 'none';
                };

                xhr.send(formData);
            });


            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            }

            // === Reset Upload Form ===
            function resetUpload() {
                selectedFiles = [];
                fileInput.value = '';
                fileInfo.innerHTML = '<p>Format yang didukung: PDF, JPG, PNG, DOC, DOCX</p>';
                progressContainer.style.display = 'none';
                progressBar.style.width = '0%';
            }

            // === Format File Size ===
            function formatFileSize(bytes) {
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // === Show Error Message ===
            function showError(message) {
                fileInfo.innerHTML = `<p style="color:#ff6b6b;">${message}</p>`;
            }
        });

    </script>

    <script src="{{ asset('template/assets/js/sweetalert2.all.min.js') }}"></script>

</body>
</html>