@props(['name', 'label', 'type' => 'text', 'value' => null, 'autocomplete' => null, 'autofocus' => false])
{{-- A field of the login and password screens; the error takes the label's place --}}
<div>
  <label for="{{ $name }}" class="mb-8 block text-xs {{ $errors->has($name) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-warm-400' }}">
    {{ $errors->has($name) ? $errors->first($name) : $label }}
  </label>
  <input
    id="{{ $name }}"
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $type === 'password' ? '' : old($name, $value) }}"
    required
    @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @if ($autofocus) autofocus @endif
    class="block w-full rounded-md border bg-white px-12 py-10 text-sm transition-all focus:ring-2 focus:outline-none dark:bg-warm-800 dark:text-warm-100 {{ $errors->has($name)
      ? 'border-red-400 focus:border-red-400 focus:ring-red-200 dark:border-red-500 dark:focus:border-red-500 dark:focus:ring-red-800'
      : 'border-gray-200 focus:border-gray-300 focus:ring-gray-200 dark:border-warm-700 dark:focus:border-warm-600 dark:focus:ring-warm-700' }}"
  >
</div>
