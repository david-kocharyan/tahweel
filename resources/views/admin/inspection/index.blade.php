@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Add
                    Inspectors</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Plumber</th>
                            <th>Inspector</th>
                            <th>Requested Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        <tr id="forFilters">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="date">
                                <input type="text" class="start d-inline" placeholder="From">
                                -
                                <input type="text" class="end d-inline" placeholder="To">
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->plumber->full_name}}</td>
                                <td>{{$val->inspector->full_name}}</td>
                                <td>{{$val->date}}</td>
                                <td>{{$val->location}}</td>
                                <td>{{$val->status}}</td>
                                <td>

                                    <a href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Edit" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form
                                        onsubmit="if(confirm('Do You Really Want To Delete The Plumber?') == false) return false;"
                                        style="display: inline-block" action="{{ $route."/".$val->id }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0)">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Delete"
                                                    class="btn btn-danger btn-circle tooltip-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <!--This is a datatable style -->
    <link href="{{asset('assets/plugins/datatables/media/css/dataTables.bootstrap.css')}}" rel="stylesheet"
          type="text/css"/>
    <!--This is a Select 2 style -->
    <link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <!--This is a Date Picker style -->
    <link href="{{asset('assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .date {
            width: 150px;
        }

        .date input {
            color: #444;
            line-height: 24px;
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
        }
    </style>
@endpush

@push('foot')
    <!--Datatable js-->
    <script src="{{asset('assets/plugins/datatables/datatables.min.js')}}"></script>
    <!--Select2 js-->
    <script src="{{asset('assets/plugins/select2/dist/js/select2.min.js')}}"></script>
    <!--Select2 js-->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                bSort: false,
                initComplete: function () {
                    this.api().columns([0, 1, 2, 4, 5]).every(function (i) {
                        var column = this;
                        var select = $('<select class="select2  form-control"><option value="">All</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });
            $('.select2').select2();
            $('.start').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('.end').datepicker({
                format: 'yyyy-mm-dd'
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = Date.parse($(".start").val());
                    var max = Date.parse($(".end").val());

                    var date = Date.parse(data[3]) || 0;

                    if ((isNaN(min) && isNaN(max)) ||
                        (isNaN(min) && date <= max) ||
                        (min <= date && isNaN(max)) ||
                        (min <= date && date <= max)) {
                        return true;
                    }
                    return false;
                }
            );

            $('.start, .end').on("changeDate", function () {
                table.draw();
            });

        });
    </script>
@endpush




