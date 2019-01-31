<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Varilo - разработка сайта</title>
    <link rel="stylesheet" type="text/css" href="/fonts/fortawesome/fontawesome-free/css/all.min.css">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta  id="si--meta-token" content="{{ Auth::user()->api_token }}">
    <meta  id="si--project-id" content="{{ $project_id }}">
    {{-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet"> --}}
</head>
<body>
<div id="root"></div>


    @if (env('APP_ENV')!='production')
        <script type="text/javascript" src="http://localhost:8080/main.js"></script></body>
    @else
        <script type="text/javascript" src="/client/main.js"></script></body>
    @endif

</html>