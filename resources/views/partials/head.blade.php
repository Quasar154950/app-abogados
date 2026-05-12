<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ $title ?? '' }}
</title>

{{-- FAVICON PERSONALIZADO --}}
<link rel="icon" href="/images/logo.png?v=2">
<link rel="apple-touch-icon" href="/images/logo.png?v=2">

{{-- PWA --}}
<link rel="manifest" href="/manifest.json">

<meta name="theme-color" content="#111827">

<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">

<meta name="apple-mobile-web-app-title" content="MCTandil Apps">

{{-- FUENTES --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- ESTILOS Y JS --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- MODO OSCURO / APARIENCIA --}}
@fluxAppearance
