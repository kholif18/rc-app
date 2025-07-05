<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" method="POST">
                @csrf
                @method('PUT')
                    <div class="modal-body">
                        <div class="form-floating py-1">
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="Ravaa Creative"
                            aria-describedby="floatingInputHelp" required/>
                        <label for="edit_name">Nama Pelanggan</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control" name="phone" id="edit_phone" placeholder="6281234xxxxxx"
                            aria-describedby="floatingInputHelp"/>
                        <label for="edit_phone">No HP</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="email" class="form-control" name="email" id="edit_email" placeholder="email@example.com"
                            aria-describedby="floatingInputHelp"/>
                        <label for="edit_email">E-mail</label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control" name="address" id="edit_address" placeholder="Ds. Ngluyu"
                            aria-describedby="floatingInputHelp"/>
                        <label for="edit_address">Alamat</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editBahanModal = document.getElementById('editCustomerModal');
        editBahanModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var modal = this;
            
            // Set form action URL
            modal.querySelector('#editCustomerForm').action = '/customers/' + id;
            
            // Populate form fields
            modal.querySelector('#edit_name').value = button.getAttribute('data-name');
            modal.querySelector('#edit_phone').value = button.getAttribute('data-phone');
            modal.querySelector('#edit_email').value = button.getAttribute('data-email');
            modal.querySelector('#edit_address').value = button.getAttribute('data-address');
        });
    });
</script>