<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('seo.title') }} - Administration</title>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="robots" content="noindex, nofollow">
<meta name="format-detection" content="telephone=no">
@vite(['resources/css/admin.css', 'resources/js/admin/app.js'])
</head>
<body class="h-full bg-gray-100 font-sans text-sm tracking-wide text-gray-900 antialiased dark:bg-warm-950 dark:text-warm-100">
<div id="app"></div>
</body>
</html>
