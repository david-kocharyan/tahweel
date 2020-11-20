@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="en">Name EN</label>
                                @error('en')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="en"
                                       placeholder="City name" name="en" value="{{old('en')}}">
                            </div>

                            <div class="form-group">
                                <label for="ar">Name AR</label>
                                @error('ar')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="ar"
                                       placeholder="City name" name="ar" value="{{old('ar')}}">
                            </div>

                            <div class="form-group">
                                <label for="ur">Name UR</label>
                                @error('ur')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="ur"
                                       placeholder="City name" name="ur" value="{{old('ur')}}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save City
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
