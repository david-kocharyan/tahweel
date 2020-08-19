<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tahweel | Download Warranty</title>
</head>
<style>
    .container {
        text-align: center;
        margin: auto;
    }
    a{
        display: none;
    }
</style>
<body>
<div class="container">
    <img src="{{asset('assets/images/wait.gif')}}" alt="Please Wait...">
    <a href="{{asset('uploads/'.$file)}}" download id="anchor"></a>
</div>
</body>
<script !src="">
    (function download() {
        setTimeout(function () {
            document.getElementById('anchor').click();
        }, 2000)
        setTimeout(function () {
            window.opener = self;
            window.close();
        }, 3000)
    })()
</script>
</html>
