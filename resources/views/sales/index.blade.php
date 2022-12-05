@extends('layouts.master')

@section('title')
    Penjualan
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">@yield('title')</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-4">
                    <table class="table table-sale" id="table_id">
                        <thead class="text-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Total Bayar</th>
                                <th>Kasir</th>
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
@includeIf('sales.detail');
@endsection

@push('scripts')
    <script>
        let table, table1;
        $(function () {
            table = $('.table-sale').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('sales.data') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'tanggal'},
                    {data: 'total_items'},
                    {data: 'price'},
                    {data: 'discount'},
                    {data: 'pay'},
                    {data: 'kasir'},
                    {data: 'aksi', searchable: false, sortable: false},
                ]
            });
            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'code'},
                    {data: 'name'},
                    {data: 'selling_price'},
                    {data: 'amount'},
                    {data: 'subtotal'},
                ]
            })
        });
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