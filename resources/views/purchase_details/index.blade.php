@extends('layouts.master')

@section('title')
    Detail Pembelian
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 120px;
        padding-top: 40px;
        color:#f0f0f0;
    }
    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }
    .table-purchase tbody tr:last-child {
        display: none;
    }
    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">@yield('title')</h4>
            </div>
            <div class="card-body">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $supplier->name }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $supplier->address }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $supplier->phone }}</td>
                    </tr>
                </table>
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_purchase" id="id_purchase" value="{{ $id_purchase }}">
                                <input type="hidden" name="product_id" id="product_id">
                                <input type="text" class="form-control" name="code" id="code" placeholder="Kode Produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-just-icon btn-sm" type="button"><span class="material-icons">arrow_forward</span></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-purchase" id="table_id">
                        <thead class="text-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-lg-8 mt-4">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang h5"></div>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <form action="{{ route('purchases.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_purchase" value="{{ $id_purchase }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">
    
                                <div class="form-group row">
                                    <label for="total_price" class="col-lg-4">Total</label>
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="total_price" id="total_price" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-lg-4">Diskon</label>
                                    <div class="col-lg-12">
                                        <input type="number" name="discount" id="discount" class="form-control" value="{{ $discount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pay" class="col-lg-4">Bayar</label>
                                    <div class="col-lg-12">
                                        <input type="text" id="pay" name="pay" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right btn-simpan">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('purchase_details.product');
@endsection

@push('scripts')
    <script>
        let table, table2;
        $(function () {
            $('body').addClass('sidebar-collapse');
            table = $('.table-purchase').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('purchase_details.data', $id_purchase) }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'code'},
                    {data: 'name'},
                    {data: 'original_price'},
                    {data: 'amount'},
                    {data: 'subtotal'},
                    {data: 'aksi', searchable: false, sortable: false},
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false
            })
            .on('draw.dt', function () {
                loadForm($('#discount').val());
            });
            table2 = $('.table-produk').DataTable();
            $(document).on('input', '.quantity', function () {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());
                if (jumlah < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }
                $.post(`{{ url('/purchase-details') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'amount': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function () {
                            table.ajax.reload(() => loadForm($('#discount').val()));
                        });
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });
            $(document).on('input', '#discount', function () {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($(this).val());
            });
            $('.btn-simpan').on('click', function () {
                $('.form-pembelian').submit();
            });
        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, code) {
            $('#product_id').val(id);
            $('#code').val(code);
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('purchase_details.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#code').focus();
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        function loadForm(discount = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());
            $.get(`{{ url('/purchase-details/loadform') }}/${discount}/${$('.total').text()}`)
                .done(response => {
                    $('#total_price').val('Rp. '+ response.total_price);
                    $('#pay').val('Rp. '+ response.pay);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Rp. '+ response.pay);
                    $('.tampil-terbilang').text(response.terbilang);
                })  
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush