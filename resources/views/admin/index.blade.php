@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">Blank Page</h3>
                You are logged in! <br/>
                {{Auth::guard('web')->user()->name}}
            </div>
        </div>
    </div>
@endsection
