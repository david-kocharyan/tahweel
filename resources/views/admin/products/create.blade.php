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
                            @foreach($languages as $key => $value)
                                <div class="form-group">
                                    <label for="name">Product Name <b>({{ strtoupper($value->lng) }})</b> </label>
                                    @error("data.$key.name")
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ Str::replaceArray("data.$key.name", ['name'], $message) }}</strong></p>
                                    @enderror
                                    <input type="text" class="form-control" id="name" name="data[{{ $key }}][name]" value="{{ $product->languages[$key]->pivot->name ?? old("data")[$key]['name'] ?? ""}}">
                                </div>

                                <div class="form-group">
                                    <label for="name">Product Description <b>({{ strtoupper($value->lng) }})</b></label>
                                    @error("data.$key.description")
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ Str::replaceArray("data.$key.description", ['description'], $message) }}</strong></p>
                                    @enderror
                                    <textarea name="data[{{ $key }}][description]" id="description" cols="30" class="form-control" rows="10">{{ $product->languages[$key]->pivot->description ?? old("data")[$key]['description'] ?? ""}}</textarea>
                                </div>
                                <input type="hidden" name="data[{{ $key }}][language_id]" value="{{ $value->id }}">
                            @endforeach

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
                                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save</button>
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
