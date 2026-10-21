@extends('layout.auth')
@section('title', 'Anmelden')
@section('content')
<h1 class="mb-4 text-lg font-medium text-gray-900 dark:text-warm-100">Anmelden</h1>
<p class="mb-24 text-sm text-gray-400 dark:text-warm-500">Melden Sie sich mit Ihrem Konto an.</p>
<form method="POST" action="{{ route('login') }}" class="space-y-16">
  @csrf
  <x-auth-field type="email" name="email" label="E-Mail" autocomplete="username" autofocus />
  <x-auth-field type="password" name="password" label="Passwort" autocomplete="current-password" />
  <div class="mt-24 flex items-center justify-between">
    @if (Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="text-xs text-gray-400 underline decoration-gray-300 underline-offset-4 hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100">Passwort vergessen?</a>
    @endif
    <x-auth-button>Anmelden</x-auth-button>
  </div>
</form>
@endsection
