@extends('layout.auth')
@section('title', 'Passwort zurücksetzen')
@section('content')
<h1 class="mb-4 text-lg font-medium text-gray-900 dark:text-warm-100">Passwort zurücksetzen</h1>
<p class="mb-24 text-sm text-gray-400 dark:text-warm-500">Wählen Sie ein neues Passwort.</p>
<form method="POST" action="{{ route('password.update') }}" class="space-y-16">
  @csrf
  <input type="hidden" name="token" value="{{ $token }}">
  <x-auth-field type="email" name="email" label="E-Mail" :value="$email ?? null" autocomplete="username" />
  <x-auth-field type="password" name="password" label="Neues Passwort" autocomplete="new-password" autofocus />
  <x-auth-field type="password" name="password_confirmation" label="Passwort bestätigen" autocomplete="new-password" />
  <div class="mt-24 flex items-center justify-end">
    <x-auth-button>Passwort speichern</x-auth-button>
  </div>
</form>
@endsection
