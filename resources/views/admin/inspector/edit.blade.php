@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route."/".$data->id }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                @error('full_name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="full_name"
                                       placeholder="Full name" name="full_name" value="{{$data->full_name}}">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                @error('username')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="username"
                                       placeholder="Username" name="username" value="{{$data->username}}">
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                @error('city')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="city" id="city" class="form-control">
                                    @foreach($city as $key=>$val)
                                        <option value="{{$val->id}}" @if($val->id == $data->city_id) selected @endif>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                @error('email')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="email" class="form-control" id="email"
                                       placeholder="Email" name="email" value="{{$data->email}}">
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="approved" @if($data->approved == 1) checked @endif value="1" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Approved
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Password</label>
                                @error('password')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="password" class="form-control" id="password"
                                       placeholder="Password" name="password">
                                <button type="button" class="pass btn btn-primary m-t-5">Generate Password</button>

                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                @error('phone')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" autocomplete="off" class="form-control" id="phone"
                                       placeholder="Phone" name="phone" value="{{$data->phone->phone ?? ''}}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save Inspectors
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('foot')
    <script>
        $(document).ready(function () {
            $('.pass').click(function () {
                var pass = generatePassword();
                $('#password').val(pass);
            });

            function generatePassword() {
                var length = 8,
                    charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                    retVal = "";
                for (var i = 0, n = charset.length; i < length; ++i) {
                    retVal += charset.charAt(Math.floor(Math.random() * n));
                }
                return retVal;
            }
        })
    </script>
@endpush
