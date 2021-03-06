@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Add
                    Plumber</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Points</th>
                            <th>Role</th>
                            <th>Approved</th>
                            <th>Options</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->full_name}}</td>
                                <td>{{$val->username}}</td>
                                <td>{{$val->email ?? "Empty"}}</td>
                                <td>{{$val->phone->phone ?? 'Empty'}}</td>
                                <td>{{$val->city->name}}</td>
                                <td>{{$val->point}}</td>
                                <td>Plumber</td>
                                <td>
                                    @if($val->approved == 0)
                                        <span class="badge badge-warning">Waiting</span>
                                    @else
                                        <span class="badge badge-success">Approved</span>
                                    @endif
                                </td>
                                <td>
                                    <a data-toggle="modal" data-target="#myModal" data-id="{{$val->id}}" class="btn btn-success btn-circle pointsAdd">
                                        <i class="fas fa-plus"></i>
                                    </a>

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


    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Points For Plumber</h4>
                </div>
                <div class="modal-body">
                    <form action="/admin/add-plumber-points" method="post">
                        @csrf
                        <input type="hidden" name="plumber_id" class="plumber_id">
                        <div class="form-group">
                            <label for="point">Points</label>
                            @error('point')
                            <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                            @enderror
                            <input type="number" min="0" autocomplete="off" class="form-control" id="point"
                                   placeholder="Point" name="point" value="{{old('point')}}">
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>

                    </form>
                </div>
            </div>

        </div>
    </div>



@endsection

@push('head')
    <!--This is a datatable style -->
    <link href="{{asset('assets/plugins/datatables/media/css/dataTables.bootstrap.css')}}" rel="stylesheet"
          type="text/css"/>
@endpush

@push('foot')
    <!--Datatable js-->
    <script src="{{asset('assets/plugins/datatables/datatables.min.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var col = $('col', sheet);
                        $('row:first c', sheet).attr('s', '32');
                        col.each(function () {
                            $(this).attr('width', 25);
                        });
                    }
                },
            ]
        });
        $('.buttons-excel').addClass('btn btn-primary m-r-10');

        $('.pointsAdd').click(function () {
            $('.plumber_id').val($(this).data('id'))
        })
    </script>
@endpush




