<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate 1</title>
    <style>
        .certificate {
            width: 100%;
            height: 100vh;
            background-image: url("{{asset('assets/certificate/1.jpg')}}");
            background-repeat: no-repeat;
            background-size: 100% 99%;
        }

        .text-2, .text-3 {
            position: relative;
            width: 390px;
            height: 38px;
            right: 19%;
            font-size: 19px;
            text-align: center;
            color: #696d70;
        }

        .text-2 {
            top: 30.5%;
            left: 52%;
            width: 450px;
        }

        .text-3 {
            top: 31%;
            left: 57%;
            width: 500px;
        }

        .text-6, .text-7, .text-8, .text-9, .text-10, .text-11, .text-id-1, .text-id-2 {
            font-size: 20px;
            color: #696d70;
        }

        .text-6 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 47%;
            right: 40.2%;
        }

        .text-7 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
            right: 42.9%;
        }

        .text-8 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 47%;
            right: 45.35%;
        }

        .text-9 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 47%;
            right: 55%;
        }

        .text-10 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 47%;
            right: 74.6%;
        }

        .text-11 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 47%;
            right: 77.55%;
        }

        .text-id-1 {
            position: absolute;
            width: 170px;
            height: 35px;
            top: 47%;
            right: 59.55%;
        }

        .text-id-2 {
            position: absolute;
            width: 170px;
            height: 35px;
            top: 47%;
            right: 25.55%;
        }

    </style>
</head>
<body style="overflow: hidden;">
<div class="certificate">
    <div class="text-2">{{$customer->full_name}}</div>
    <div class="text-3">{{$inspection->address}}</div>
    @if($phase_two != null)
        <div class="text-6">{{$phase_two->created_at->format('y')}}</div>
        <div class="text-7">{{$phase_two->created_at->format('M')}}</div>
        <div class="text-8">{{$phase_two->created_at->format('d')}}</div>
        <div class="text-id-2">{{$phase_two->id}}</div>
    @endif

    @if($phase_one != null)
        <div class="text-9">{{$phase_one->created_at->format('y')}}</div>
        <div class="text-10">{{$phase_one->created_at->format('M')}}</div>
        <div class="text-11">{{$phase_one->created_at->format('d')}}</div>
        <div class="text-id-1">{{$phase_one->id}}</div>
    @endif
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
</html>
