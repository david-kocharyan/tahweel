@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route."/".$issue->id }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="form-group">
                                <label for="category">Category Name</label>
                                @error('category')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="category" id="category" class="select2 form-control">
                                    @foreach($category as $key)
                                        <option value="{{$key->id}}" @if($key->id == $issue->category_id) selected @endif>{{$key->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Issue</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name"
                                       placeholder="Category name" name="name" value="{{$issue->name}}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save Issue
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <!--This is a Select 2 style -->
    <link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('foot')
    <!--Select2 js-->
    <script src="{{asset('assets/plugins/select2/dist/js/select2.min.js')}}"></script>
    <!--Select2 js-->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script !src="">
        $('#category').select2();
    </script>
@endpush
