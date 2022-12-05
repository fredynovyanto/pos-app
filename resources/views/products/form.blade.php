<!-- Button trigger modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select class="form-control selectpicker" data-style="btn btn-link" name="category_id" id="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($category as $key => $item)
                                <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand">Merk</label>
                        <input type="text" class="form-control" id="brand" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label for="original_price">Harga Beli</label>
                        <input type="number" class="form-control" id="original_price" name="original_price" required>
                    </div>
                    <div class="form-group">
                        <label for="selling_price">Harga Jual</label>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                    </div>
                    <div class="form-group">
                        <label for="discount">Diskon</label>
                        <input type="number" class="form-control" id="discount" name="discount" required value="0">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary btn-sm">Simpan</button>
                </div>
                </div>
        </form>
    </div>
</div>