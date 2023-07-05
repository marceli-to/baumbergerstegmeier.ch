@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-worklist">
  <nav class="worklist-filter">
    <ul class="lg:flex">
      <li>
        <a href="javascript:;" class="is-first is-active">Alle</a>
      </li>
      <li>
        <a href="javascript:;">Jahr</a>
      </li>
      @if ($states)
        <li>
          <a href="javascript:;">Status</a>
          <ul class="hidden">
            @foreach($states as $state)
              <li>
                <a href="javascript:;">{{ $state->description }}</a>
              </li>
            @endforeach
          </ul>
        </li>
      @endif
      @if ($categories)
      <li>
        <a href="javascript:;">Programm</a>
        <ul class="hidden">
          @foreach($categories as $category)
            <li>
              <a href="javascript:;">{{ $category->description }}</a>
            </li>
          @endforeach
        </ul>
      </li>
      @endif
      <li class="is-search">
        <a href="javascript:;" class="is-search !mb-0 flex justify-between items-center lg:items-start">
          <x-icons.search class="mr-7x lg:mr-10x" />
          Suche
        </a>
      </li>
    </ul>
  </nav>
</section>
@endsection