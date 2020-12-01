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
            background-size: 103% 100%;
        }

        .text-1, .text-2, .text-3, .text-4, .text-5, .text-6, .text-7, .text-8, .text-9, .text-10, .text-11 {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            color: #696d70;
        }

        .text-1 {
            width: 440px;
            height: 38px;
            top: 33.5%;
            right: 19.5%;
        }

        .text-2 {
            width: 440px;
            height: 38px;
            top: 37%;
            right: 13.5%;
        }

        .text-3 {
            top: 48.3%;
            right: 13%;
            width: 192px;
            height: 40px;
        }

        .text-4 {
            top: 48.3%;
            right: 28.5%;
            width: 50px;
            height: 40px;
        }

        .text-5, .text-6, .text-7, .text-8, .text-9, .text-10, .text-11 {
            font-size: 1.85rem;
        }

        .text-5 {
            top: 48.3%;
            right: 32.8%;
            width: 50px;
            height: 40px;
        }

        .text-6 {
            top: 48.3%;
            right: 36.8%;
            width: 50px;
            height: 40px;
        }

        .text-7 {
            top: 48.3%;
            right: 53%;
            width: 196px;
            height: 40px;
        }

        .text-8 {
            top: 48.3%;
            right: 68.8%;
            width: 50px;
            height: 40px;
        }

        .text-9 {
            top: 48.3%;
            right: 72.8%;
            width: 50px;
            height: 40px;
        }

        .text-10 {
            top: 48.3%;
            right: 77%;
            width: 50px;
            height: 40px;
        }

        .text-11 {
            position: absolute;
            width: 440px;
            height: 35px;
            top: 70%;
            right: 36%;
        }

    </style>
</head>
<body>

    <div class="certificate" id="certificate">
        <div class="text-1">{{$customer->full_name}}</div>
        <div class="text-2">{{$inspection->address}}</div>
        @if($phase_two != null)
            <div class="text-3">{{$phase_two->id}}</div>
            <div class="text-4">{{$phase_two->created_at->format('y')}}</div>
            <div class="text-5">{{$phase_two->created_at->format('M')}}</div>
            <div class="text-6">{{$phase_two->created_at->format('d')}}</div>
        @endif
        @if($phase_one != null)
            <div class="text-7">{{$phase_one->id}}</div>
            <div class="text-8">{{$phase_one->created_at->format('y')}}</div>
            <div class="text-9">{{$phase_one->created_at->format('M')}}</div>
            <div class="text-10">{{$phase_one->created_at->format('d')}}</div>
        @endif

        <div class="text-11"></div>
    </div>


    <script src="https://code.jquery.com/jquery-1.10.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        window.onload = function() {

            var pdf = new jsPDF('s', 'pt', 'a4');
            pdf.addHTML(document.getElementById("certificate"), function() {

                ps_filename = "certificate-02";
                pdf.save(ps_filename+'.pdf');

                window.close()
            });
        }
    </script>

</body>
</html>
