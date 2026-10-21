<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title') - {{ config('seo.title') }}</title>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<meta name="robots" content="noindex, nofollow">
@vite('resources/css/admin.css')
</head>
<body class="h-full bg-white font-sans text-sm tracking-wide text-gray-900 antialiased dark:bg-warm-900 dark:text-warm-100">
{{-- Login and password screens, as the admin's (split screen, a random project cover on the left) --}}
<x-auth-splash />
<div class="flex min-h-dvh">
  <div class="container-auth relative hidden bg-gray-200 bg-cover bg-center lg:block lg:w-1/2 dark:bg-warm-800">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 flex h-full items-end p-48 text-white">
      <span class="block w-200 [&>svg]:h-auto [&>svg]:w-full [&_path]:fill-current"><x-icons.logo /></span>
    </div>
  </div>
  <main class="flex w-full items-center justify-center bg-white px-32 py-48 lg:w-1/2 dark:bg-warm-900">
    <div class="w-full max-w-sm">
      <div class="mb-32 w-128 text-gray-900 lg:hidden dark:text-warm-100 [&>svg]:h-auto [&>svg]:w-full [&_path]:fill-current"><x-icons.logo /></div>
      @yield('content')
    </div>
  </main>
</div>
</body>
</html>
