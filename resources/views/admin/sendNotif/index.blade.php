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
                                <label for="plumber">Plumber</label>
                                @error('plumber')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select class="form-control plumber" name="plumber[]" multiple="multiple">
                                    <option value="0">Select All</option>
                                    @foreach($plumber as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inspector">Inspector</label>
                                @error('inspector')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select class="form-control inspector" name="inspector[]" multiple="multiple">
                                    <option value="0">Select All</option>
                                    @foreach($inspector as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                @error('city')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select class="form-control city" name="city[]" multiple="multiple">
                                    <option value="0">Select All</option>
                                    @foreach($city as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->en}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <hr>
                            <h4><strong>Notification body` </strong></h4>
                            <hr>

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



@push('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
@endpush


@push('foot')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script !src="">
        $(".plumber").select2({});
        $(".inspector").select2({});
        $(".city").select2({});

        $(".plumber").on('change', function() {
            var selected = $(this).val();
            if (selected != null) {
                if (selected.indexOf('0') >= 0) {
                    $(this).val('0').select2();
                }
            }
        })

        $(".inspector").on('change', function() {
            var selected = $(this).val();
            if (selected != null) {
                if (selected.indexOf('0') >= 0) {
                    $(this).val('0').select2();
                }
            }
        })

        $(".city").on('change', function() {
            var selected = $(this).val();
            if (selected != null) {
                if (selected.indexOf('0') >= 0) {
                    $(this).val('0').select2();
                }
            }
        })










    </script>
@endpush
