@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route."/".$issueCategory->id }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="form-group">
                                <label for="name">Category Name</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name"
                                       placeholder="Category name" name="name" value="{{$issueCategory->name}}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save Issue Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
