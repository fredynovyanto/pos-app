@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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
    .table-sale tbody tr:last-child {
        display: none;
    }
    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 30px;
            height: 100px;
            padding-top: 30px;
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
                {{-- <a class="btn btn-sm btn-primary" href="{{route('transactions.index')}}">
                    Transaksi Lama
                </a> --}}
                <form class="form-product">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_sale" id="id_sale" value="{{ $id_sale }}">
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
                    <table class="table table-sale" id="table_id">
                        <thead class="text-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                                <th>Diskon</th>
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
                            <form action="{{ route('transactions.simpan') }}" class="form-sale" method="post">
                                @csrf
                                <input type="hidden" name="id_sale" value="{{ $id_sale }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_items" id="total_items">
                                <input type="hidden" name="pay" id="pay">
    
                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-4">Total</label>
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="totalrp" id="totalrp" class="form-control" readonly>
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
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" id="payrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="received" class="col-lg-4">Diterima</label>
                                    <div class="col-lg-12">
                                        <input type="number" id="received" name="received" class="form-control" value="{{ $sale->received ?? 0 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kembali" class="col-lg-4">Kembali</label>
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
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
@includeIf('sale_details.product');
@endsection

@push('scripts')
    <script>
        let table, table2;
        $(function () {
            $('body').addClass('sidebar-collapse');
            table = $('.table-sale').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('transactions.data', $id_sale) }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'code'},
                    {data: 'name'},
                    {data: 'selling_price'},
                    {data: 'amount'},
                    {data: 'discount'},
                    {data: 'subtotal'},
                    {data: 'aksi', searchable: false, sortable: false},
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false
            })
            .on('draw.dt', function () {
                loadForm($('#discount').val());
                setTimeout(() => {
                    $('#received').trigger('input');
                }, 300);
            });
            table2 = $('.table-product').DataTable();
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
                $.post(`{{ url('/transactions') }}/${id}`, {
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
                    if(errors.status == 400) {
                        alert(`Tidak dapat menyimpan data, ${errors.responseJSON.message}`);
                    } else {
                        alert('Tidak dapat menyimpan data');
                    }
                    return;
                });
            });
            $(document).on('input', '#discount', function () {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($(this).val());
            });
            $('#received').on('input', function () {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($('#discount').val(), $(this).val());
            }).focus(function () {
                $(this).select();
            });
            $('.btn-simpan').on('click', function () {
                $('.form-sale').submit();
            });
        });
        function tampilProduk() {
            $('#modal-product').modal('show');
        }
        function hideProduk() {
            $('#modal-product').modal('hide');
        }
        function pilihProduk(id, kode) {
            $('#product_id').val(id);
            $('#code').val(kode);
            hideProduk();
            tambahProduk();
        }
        function tambahProduk() {
            $.post('{{ route('transactions.store') }}', $('.form-product').serialize())
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
        function loadForm(discount = 0, received = 0) {
            $('#total').val($('.total').text());
            $('#total_items').val($('.total_items').text());
            $.get(`{{ url('/transactions/loadform') }}/${discount}/${$('.total').text()}/${received}`)
                .done(response => {
                    $('#totalrp').val('Rp. '+ response.totalrp);
                    $('#payrp').val('Rp. '+ response.payrp);
                    $('#pay').val(response.pay);
                    $('.tampil-bayar').text('Bayar: Rp. '+ response.payrp);
                    $('.tampil-terbilang').text(response.terbilang);
                    $('#kembali').val('Rp.'+ response.kembalirp);
                    if ($('#received').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush