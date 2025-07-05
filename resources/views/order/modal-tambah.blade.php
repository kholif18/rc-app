<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customers.store') }}" id="addCustomerForm">
                @csrf
                <div class="modal-body">
                    <div class="form-floating py-1">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Ravaa Creative"
                            aria-describedby="floatingInputHelp" autofocus required/>
                        <label for="name">Nama Pelanggan</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="6281234xxxxxx"
                            aria-describedby="floatingInputHelp"/>
                        <label for="phone">No HP</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="email" class="form-control" name="email" id="email" placeholder="mail@example.com"
                            aria-describedby="floatingInputHelp"/>
                        <label for="email">E-mail</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control" name="address" id="address" placeholder="Ds. Ngluyu"
                            aria-describedby="floatingInputHelp"/>
                        <label for="address">Alamat</label>
                    </div>
                    <div id="floatingInputHelp" class="form-text">
                        We'll never share your details with anyone else.
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addCustomerForm = document.getElementById('addCustomerForm');
        
        if (!addCustomerForm) return; // Exit if form doesn't exist
        
        addCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Show loading indicator
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
                    // Add new customer to datalist
                    addNewCustomerToDatalist(data.customer);
                    
                    // Close modal and reset form
                    $('#addCustomerModal').modal('hide');
                    this.reset();
                    
                    // Show success toast
                    showToast('Sukses', 'Pelanggan berhasil ditambahkan', 'success');
                } else {
                    // Show validation errors
                    displayValidationErrors(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal', 'Terjadi kesalahan saat menyimpan', 'danger');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
        
        function addNewCustomerToDatalist(customer) {
            const datalist = document.getElementById('customers_data');
            const customerSearch = document.getElementById('customer_search');
            const customerIdInput = document.getElementById('customer_id');
            
            if (!datalist || !customerSearch || !customerIdInput) return;
            
            // Create new option for datalist
            const newOption = document.createElement('option');
            newOption.value = `${customer.name} (${customer.phone || '-'})`;
            newOption.dataset.id = customer.id;
            datalist.appendChild(newOption);
            
            // Set the search input value and hidden ID
            customerSearch.value = newOption.value;
            customerIdInput.value = customer.id;
            
            // Trigger input event in case there are any listeners
            customerSearch.dispatchEvent(new Event('input'));
        }

        function displayValidationErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
            
            // Show new errors
            for (const field in errors) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = errors[field][0];
                    input.insertAdjacentElement('afterend', errorDiv);
                }
            }
        }

        function showToast(title, message, type) {
            const toastContainer = document.querySelector('.toast-placement-ex');
            const toastEl = document.createElement('div');
            
            toastEl.className = `bs-toast toast fade show bg-${type}`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            toastEl.innerHTML = `
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">${title}</div>
                    <small>Baru saja</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            toastContainer.appendChild(toastEl);
            
            // Auto remove toast after 5 seconds
            setTimeout(() => {
                toastEl.classList.remove('show');
                setTimeout(() => toastEl.remove(), 300);
            }, 5000);
            
            // Add click handler for close button
            toastEl.querySelector('.btn-close').addEventListener('click', () => {
                toastEl.classList.remove('show');
                setTimeout(() => toastEl.remove(), 300);
            });
        }
    });
</script>