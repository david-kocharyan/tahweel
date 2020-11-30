<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate 1</title>
    <style>

        @page {
            size: a4 landscape;
        }

        .certificate {
            position: relative;
            width: 100%;
            height: 100vh;
            background-image: url("{{asset('assets/certificate/1.jpg')}}");
            background-repeat: no-repeat;
            background-size: 100% 99%;
        }

        .text-1, .text-2, .text-3 {
            position: absolute;
            width: 390px;
            height: 38px;
            right: 19%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: #696d70;
            border: 1px solid red;
        }

        .text-1 {
            top: 26%;
        }

        .text-2 {
            top: 29.5%;
            left: 52%;
            width: 450px;
        }

        .text-3 {
            top: 33%;
            left: 57%;
            width: 432px;
        }

        .text-4, .text-5, .text-6, .text-7, .text-8, .text-9, .text-10, .text-11, .text-id-1, .text-id-2{
            font-size: 1.85rem;
            display: flex;
            justify-content: center;
            color: #696d70;
            border: 1px solid red;
        }

        .text-4 {
            position: absolute;
            width: 45px;
            height: 35px;
            top: 45%;
            right: 13.7%;
        }

        .text-5 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
            right: 20.5%;
        }

        .text-6 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
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
            top: 45%;
            right: 45.35%;
        }

        .text-9 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
            right: 72%;
        }

        .text-10 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
            right: 74.6%;
        }

        .text-11 {
            position: absolute;
            width: 40px;
            height: 35px;
            top: 45%;
            right: 77.55%;
        }

        .text-id-1 {
            position: absolute;
            width: 170px;
            height: 35px;
            top: 45%;
            right: 59.55%;
        }

        .text-id-2 {
            position: absolute;
            width: 170px;
            height: 35px;
            top: 45%;
            right: 25.55%;
        }

    </style>
</head>
<body style="overflow: hidden;">

    <div class="certificate">
{{--        <div class="text-1"></div>--}}
        <div class="text-2">{{$customer->full_name}}</div>
        <div class="text-3">{{$inspection->address}}</div>
{{--        <div class="text-4"></div>--}}
{{--        <div class="text-5"></div>--}}
        <div class="text-6"></div>
        <div class="text-7"></div>
        <div class="text-8"></div>
        <div class="text-9"></div>
        <div class="text-10"></div>
        <div class="text-11"></div>
        <div class="text-id-1"></div>
        <div class="text-id-2"></div>
    </div>

</body>
</html>
