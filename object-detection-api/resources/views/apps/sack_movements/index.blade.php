@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<!-- DataTables -->
<link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')
<div class="col-xl-8">
    <div class="row">
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Karung Diangkut</p>
                            <h4 class="mb-0">{{ $countIn }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Karung Dikeluarkan</p>
                            <h4 class="mb-0">{{ $countOut }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center ">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-archive-in font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Raw Data Sack Movements</h4>
                <p class="card-title-desc">
                </p>

                <table id="sackMovementsTable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Camera</th>
                            <th>Location</th>
                            <th>Direction</th>
                            <th>Detected At</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('scripts')
<!-- Required datatable js --><!-- jQuery + DataTables core -->
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- DataTables Buttons -->
<script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

<!-- Export dependencies -->
<script src="{{ asset('libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/build/vfs_fonts.js') }}"></script>

<!-- Buttons HTML5 / Print / ColVis -->
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>


<!-- Responsive examples -->
<script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatables init -->
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#sackMovementsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 10, // default 10
            lengthMenu: [[10, 50, 100, 1000, -1], [10, 50, 100, 1000, "All"]],
            ajax: "{{ route('apps.sack_movements.index') }}", // Ganti dengan route kamu
            columns: [
                { data: 'camera.name', name: 'camera.name' },
                { data: 'camera.location', name: 'camera.location' },
                { data: 'direction', name: 'direction' },
                { data: 'detected_at', name: 'detected_at' }
            ],
            dom: 'lBfrtip', // penting supaya tombol muncul
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        }).buttons().container().appendTo('#sackMovementsTable_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
