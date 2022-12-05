@extends('layouts.master')

@section('title')
    Pembelian
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">@yield('title')</h4>
            </div>
            <div class="card-body">
                <button onclick="addForm()" class="btn btn-primary btn-sm">Transaksi Baru</button>
                @if(session('id_supplier'))
                    <a href="{{ route('purchase_details.index') }}" class="btn btn-info btn-sm">Transaksi Aktif</a>
                @endif
                <div class="table-responsive mt-4">
                    <table class="table table-purchase" id="table_id">
                        <thead class="text-primary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Total Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('purchases.supplier');
@includeIf('purchases.detail');
@endsection

@push('scripts')
    <script>
        let table, table1;
        $(function () {
            table = $('.table-purchase').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('purchase.data') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'tanggal'},
                    {data: 'supplier'},
                    {data: 'total_items'},
                    {data: 'total_price'},
                    {data: 'discount'},
                    {data: 'pay'},
                    {data: 'aksi', searchable: false, sortable: false},
                ]
            });
            $('.table-supplier').DataTable();
            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'code'},
                    {data: 'name'},
                    {data: 'original_price'},
                    {data: 'amount'},
                    {data: 'subtotal'},
                ]
            })
        });

        function addForm() {
        $('#modal-supplier').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');
            table1.ajax.url(url);
            table1.ajax.reload();
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }
    </script>
@endpush