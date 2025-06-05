<div class="modal fade" id="addDebtModal" tabindex="-1" aria-labelledby="addDebtModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDebtModalLabel">Catat Hutang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('debts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-select select2" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah Hutang</label>
                        <input type="number" id="amount" name="amount" class="form-control" required step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan (opsional)</label>
                        <textarea name="note" id="note" class="form-control"></textarea>
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
    $(document).ready(function() {
        $('#addDebtModal').on('shown.bs.modal', function () {
            $('#customer_id').select2({
                dropdownParent: $('#addDebtModal'),
                placeholder: 'Pilih pelanggan',
                allowClear: true
            });
        });
    });
</script>
