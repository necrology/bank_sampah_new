<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="landing/assets/img/logo.png" />
    @vite('resources/js/app.js')
</head>

<body>
    <div id="app" data-user='@json(auth()->user())'>
    </div>
</body>

</html>