@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{$route.'/send'}}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                @error('role')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                @foreach($role as $key=>$val)
                                    <div class="form-check m-t-5">
                                        <input type="checkbox" value="{{$val}}" name='role[]' class="form-check-input">
                                        <label class="form-check-label text-uppercase">{{$key}}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label for="title">Title</label>
                                @error('title')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="title"
                                       placeholder="Title" name="title" value="{{old('title')}}">
                            </div>

                            <div class="form-group">
                                <label for="message">Message</label>
                                @error('message')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <textarea class="form-control" id="message" rows="10" style="resize: none"
                                          placeholder="Message" name="message">{{old('message')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="link">Link</label>
                                @error('link')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" value="" name='link' class="form-control">
                            </div>


                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Send
                                Notification
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
