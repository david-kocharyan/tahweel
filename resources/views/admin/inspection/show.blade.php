@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div id="carouselExampleIndicators" class="carousel slide">
                            <ol class="carousel-indicators">
                                @foreach($data->images as $bin=>$key)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$bin}}"
                                        class="@if($bin == 0) active @endif"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach($data->images as $bin=>$key)
                                    <div class="carousel-item @if($bin == 0) active @endif">
                                        <img class="d-block w-100"
                                             src="{{asset('uploads/inspections')."/".$key->image}}">
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                               data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                               data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h4 class="box-title m-t-40">Inspection description</h4>
                        <p>{{$data->comment}}</p>
                        <ul class="list-icons">
                            <li><i class="fa fa-check text-success"></i> Plumber:
                                <b>{{$data->plumber->full_name}}</b></li>
                            <li><i class="fa fa-check text-success"></i> Inspector:
                                @if(!isset($data->inspector))
                                    <a href="{{$route."/".$data->id."/edit"}}" class="btn btn-success"><i
                                            class="fas fa-plus"></i> Add Inspectors</a>
                                @else
                                    <b> {{$data->inspector->full_name}}</b>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3 class="box-title m-t-40">General Info</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td width="390">Address</td>
                                    <td> {{$data->address}} </td>
                                </tr>
                                <tr>
                                    <td>Apartment</td>
                                    <td> {{$data->apartment}}</td>
                                </tr>
                                <tr>
                                    <td>Floor</td>
                                    <td> {{$data->floor}} </td>
                                </tr>
                                <tr>
                                    <td>Building Type</td>
                                    <td> {{$data->building_type}}</td>
                                </tr>
                                <tr>
                                    <td>Project</td>
                                    <td> {{$data->project}}</td>
                                </tr>
                                <tr>
                                    <td>Created Date</td>
                                    <td> {{$data->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Latitude</td>
                                    <td> {{$data->latitude}}</td>
                                </tr>
                                <tr>
                                    <td>Longitude</td>
                                    <td> {{$data->longitude}} </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
