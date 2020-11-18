@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}} - {{$data->name}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route."/".$data->id }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="form-group">
                                <label for="coefficient">Coefficient</label>
                                @error('coefficient')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="coefficient"
                                       placeholder="Coefficient" name="coefficient" value="{{$data->point}}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save Coefficient
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
