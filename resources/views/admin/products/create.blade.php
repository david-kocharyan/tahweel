@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route}}@if(isset($product)){{"/".$product->id }}@endif" enctype="multipart/form-data">
                            @csrf

                            @if(isset($product))
                                @method("PUT")
                            @endif

                            <div class="form-group">
                                <label for="name">Product Name</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name ?? old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="point">Product Point</label>
                                @error('point')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="point" name="point" value="{{ $product->point ?? old('point')}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Product Image</label>
                                @error('image')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="file" id="input-file-now" data-default-file="{{ isset($product->image) ? asset("uploads/$product->image") : '' }}" class="dropify" name="image" />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                            </div>
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


@endpush
