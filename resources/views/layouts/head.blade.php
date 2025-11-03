
<!DOCTYPE html>
<html lang="{{ $htmlLocale }}" dir="{{ $htmlDirection }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Swiper -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/swiper-bundle.min.css') }}" />
    <script src="{{ asset('front/assets/js/swiper-bundle.min.js') }}"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/all.min.css') }}">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/tailwind.css') }}">
    <script>
        window.laravelDirection = @json($htmlDirection);
        window.laravelLocale = @json($locale);
    </script>
</head>

<body class="bg-gray-50 {{ $htmlDirection === 'rtl' ? 'rtl' : '' }}">