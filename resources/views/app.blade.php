<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    @routes
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="/js/address-autocomplete.js?v=20261027a" defer></script>
    <script src="/js/email-template-editor.js" defer></script>
    @if (config('services.whatsapp.enabled') && config('services.whatsapp.number'))
    <script>window.QB_WHATSAPP = @json(config('services.whatsapp.number'));</script>
    <script src="/js/whatsapp-widget.js" defer></script>
    @endif
</head>

<body class="font-light text-customgray antialiased">
    @inertia
</body>

</html>