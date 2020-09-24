@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Inspector Name</th>
                            <th>Customer Name</th>
                            <th>Warranty Name</th>
                            <th>Phase</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{ $val->inspector->full_name }}</td>
                                <td>{{ $val->customer->full_name }}</td>
                                <td>{{ array_search($val->warranty_type,\App\Model\Certificate::TYPE,true) }}</td>
                                <td>{{ $val->phase }}</td>
                                <td>
                                    @if($val->customer->email == null)
                                        No Email
                                    @else
                                        {{ \App\Model\CastomerWarrantySave::TYPE[$val->status] }}
                                    @endif
                                </td>
                                <td>
                                    @if($val->status != 1 AND $val->customer->email != null)
                                        <form style="display: inline-block" action="{{ $route."/".$val->id }}"
                                              method="post">
                                            @csrf
                                            @method("PUT")
                                            <a href="javascript:void(0)">
                                                <button data-toggle="tooltip"
                                                        data-placement="top" title="Send"
                                                        class="btn btn-danger btn-circle tooltip-danger"><i
                                                        class="fas fa-envelope"></i></button>
                                            </a>
                                        </form>
                                    @endif
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
    <!--Datepicker js-->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();

        });
    </script>
@endpush




