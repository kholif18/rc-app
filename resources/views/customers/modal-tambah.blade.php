<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST" id="addCustomerForm">
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