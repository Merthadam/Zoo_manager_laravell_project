<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('title', 'My Zoo')</title>
    <link rel="stylesheet" href="{{ asset('nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/enclosurebox.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feed-task.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/enclosure-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/enclosure-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animal-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animal-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/archived-animals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alert.css') }}">
</head>
<body>

    @include('comps.nav')

    <main>
        @if (session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>