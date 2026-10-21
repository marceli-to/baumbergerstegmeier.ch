@extends('layout.auth')
@section('title', 'Passwort vergessen')
@section('content')
<h1 class="mb-4 text-lg font-medium text-gray-900 dark:text-warm-100">Passwort vergessen</h1>
<p class="mb-24 text-sm text-gray-400 dark:text-warm-500">Geben Sie Ihre E-Mail-Adresse ein. Sie erhalten einen Link, mit dem Sie ein neues Passwort setzen können.</p>
@if (session('status'))
  <p class="mb-16 rounded-md bg-emerald-50 px-12 py-10 text-xs text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300" role="status">{{ session('status') }}</p>
@endif
<form method="POST" action="{{ route('password.email') }}" class="space-y-16">
  @csrf
  <x-auth-field type="email" name="email" label="E-Mail" autocomplete="username" autofocus />
  <div class="mt-24 flex items-center justify-between">
    <a href="{{ route('login') }}" class="text-xs text-gray-400 underline decoration-gray-300 underline-offset-4 hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100">Zurück zum Login</a>
    <x-auth-button>Link senden</x-auth-button>
  </div>
</form>
@endsection
