<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate 2</title>
    <style>

        .certificate {
            position: relative;
            width: 1754px;
            height: 1240px;
            background-image: url("{{asset('assets/certificate/2.jpg')}}");
            background-repeat: no-repeat;
            background-size: 100% 99%;
        }

        .text-1, .text-2, .text-3 {
            position: absolute;
            width: 425px;
            height: 38px;
            right: 16%;

            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: #696d70;
            border: 1px solid red;
        }

        .text-1 {
            top: 33%;
        }

        .text-2 {
            top: 33%;
            right: 22%;
        }

        .text-3 {
            top: 36.5%;
        }

        .text-4 {
            position: absolute;
            width: 425px;
            height: 38px;
            right: 37.5%;
            bottom: 28%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: #696d70;
            border: 1px solid red;
        }

        .text-5, .text-6, .text-7, .text-8, .text-9, .text-10, .text-11, .text-12 {
            font-size: 1.85rem;
            display: flex;
            justify-content: center;
            color: #696d70;
            border: 1px solid red;

        }

        .text-5 {
            position: absolute;
            width: 165px;
            height: 35px;
            top: 48%;
            right: 16%;
        }

        .text-6 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 31%;
        }

        .text-7 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 35%;
        }

        .text-8 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 39%;
        }

        .text-9 {
            position: absolute;
            width: 165px;
            height: 35px;
            top: 48%;
            right: 55%;
        }

        .text-10 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 70%;
        }

        .text-11 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 74%;
        }

        .text-12 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 48%;
            right: 78%;
        }

    </style>
</head>
<body>

<div class="certificate">
    {{--        <div class="text-1"></div>--}}
    <div class="text-2">{{$customer->full_name}}</div>
    <div class="text-3">{{$inspection->address}}</div>

    <div class="text-4"></div>

    @if($phase_two != null)
        <div class="text-5">{{$phase_two->created_at->format('y')}}</div>
        <div class="text-6">{{$phase_two->created_at->format('M')}}</div>
        <div class="text-7">{{$phase_two->created_at->format('d')}}</div>
        <div class="text-8">{{$phase_two->id}}</div>
    @endif

    @if($phase_one != null)
        <div class="text-9">{{$phase_one->created_at->format('y')}}</div>
        <div class="text-10">{{$phase_one->created_at->format('M')}}</div>
        <div class="text-11">{{$phase_one->created_at->format('d')}}</div>
        <div class="text-12">{{$phase_one->id}}</div>
    @endif

</div>

</body>
</html>
