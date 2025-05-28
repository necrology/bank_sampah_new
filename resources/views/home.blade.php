<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="landing/assets/img/logo.png" />
    @if (app()->environment('local'))
    <script type="module" src="http://202.10.47.115:5173/@vite/client"></script>
    <script type="module" src="http://202.10.47.115:5173/resources/js/app.js"></script>
    @else
    @vite('resources/js/app.js')
    @endif
</head>

<body>
    <div id="app" data-user='@json(auth()->user())'>
    </div>
</body>

</html>