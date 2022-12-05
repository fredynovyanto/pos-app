<!-- Button trigger modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('laporan.index') }}" method="get" data-toggle="validator">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Periode Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="text" class="form-control datetimepicker-input" id="dateTime" name="tanggal_awal" required autofocus
                        value="{{ request('tanggal_awal') }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="text" class="form-control datetimepicker-input" id="dateTime" name="tanggal_akhir" required value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
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