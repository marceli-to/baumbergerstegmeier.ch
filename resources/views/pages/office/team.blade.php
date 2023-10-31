@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-teams">

  @if (isset($data['team']->name))
    <div class="md:grid md:grid-cols-12 md:grid-column-gap">
      <div class="md:span-6">
        @if (isset($data['team']->publishedImages[0]))
          <div class="mb-20x lg:mb-50x">
            <x-image 
              :maxSizes="[0 => 1200, 1000 => 1600]" 
              :image="$data['team']->publishedImages[0]" 
              width="1200" 
              height="800">
            </x-image>
          </div>
        @endif
        @if (isset($data['leadership']))
          <h3>{{ $data['leadership']['category']->name}}</h3>
        @endif
        @if (isset($data['leadership']['employees']))
          @foreach($data['leadership']['employees'] as $employee)
            <div class="team__member">
              <strong>
                @if ($employee->email)
                  <a href="mailto:{{ $employee->email}}">{{ $employee->firstname}} {{ $employee->name}}</a>{{ $employee->team_id == 2 ? '*' : '' }}
                @else
                  {{ $employee->firstname}} {{ $employee->name}}{{ $employee->team_id == 2 ? '*' : '' }}
                @endif
              </strong>
              <br>
              {{ $employee->title }}

              @if ($employee->phone)
                <br><a href="tel:{{ $employee->phone}}">{{ $employee->phone}}</a>
              @endif

              @if ($employee->cv && $employee->cv->count() > 0)
                <div>
                  <a href="javascript:;" class="icon-chevron" data-btn-cv>Lebenslauf</a>
                  <div data-cv class="hidden mt-6x mb-40x sm:mb-60x">
                    {{-- cv uncategorized --}}
                    @php $cv = $employee->cv->whereNull('cv_category_id')->sortBy('order'); @endphp
                    @if ($cv)
                      <div class="sm:grid sm:grid-cols-auto sm:grid-column-gap">
                        @foreach($cv as $item)
                          <div class="sm:mb-1x">
                            {{ $item->periode }}
                          </div>
                          <div class="mb-12x sm:mb-1x">
                            {{ $item->description }}
                          </div>
                        @endforeach
                      </div>
                    @endif
                    {{-- cv categorized --}}
                    @php $cv_categories = $employee->cv->whereNotNull('cv_category_id')->groupBy('cv_category_id'); @endphp
                    @if ($cv_categories)
                      @foreach($cv_categories as $items)
                        <div class="mt-22x">
                          @php $cv_category = $items->first()->category; @endphp
                          <strong class="block mb-2x">{{ $cv_category->description }}</strong>
                          <div class="sm:grid sm:grid-cols-auto sm:grid-column-gap">
                            @foreach($items->sortBy('order') as $item)
                              <div class="sm:mb-1x">
                                {{ $item->periode }}
                              </div>
                              <div class="sm:mb-1x">
                                {{ $item->description }}
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        @endif
      </div>
      <div class="md:span-6">
        @if (isset($data['team']->publishedImages[1]))
          <div class="mb-20x lg:mb-50x">
            <x-image 
              :maxSizes="[0 => 1200, 1000 => 1600]" 
              :image="$data['team']->publishedImages[1]" 
              width="1200" 
              height="800">
            </x-image>
          </div>
        @endif
        @if (isset($data['employees']))
          <h3>{{ $data['employees']['category']->name}}</h3>
        @endif
        @if (isset($data['employees']['employees']))
          <div class="md:grid md:grid-cols-12 md:grid-column-gap">
            @foreach($data['employees']['employees'] as $employees)
              <div class="md:span-6">
                @foreach($employees as $employee)
                  <div class="team__member">
                    <strong>
                      @if ($employee->email)
                        <a href="mailto:{{ $employee->email}}">{{ $employee->firstname}} {{ $employee->name}}</a>{{ $employee->team_id == 2 ? '*' : '' }}
                      @else
                        {{ $employee->firstname}} {{ $employee->name}}{{ $employee->team_id == 2 ? '*' : '' }}
                      @endif
                    </strong>
                    <br>
                    {{ $employee->title}}
                    @if ($employee->phone)
                      <br><a href="tel:{{ $employee->phone}}">{{ $employee->phone}}</a>
                    @endif
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  @endif

  @if (isset($data['former_employees']['employees']) && count($data['former_employees']['employees']) > 0)
    <article class="team mt-80x">
      <h2>
        <a href="javascript:;" class="icon-chevron-large flex justify-between" data-btn-toggle-former>
          {{ $data['former_employees']['category']->name }}
          <x-icons.chevron-down-large class="mt-7x" />
        </a>
      </h2>
    </article>
    <div class="md:grid md:grid-cols-12 md:grid-column-gap !hidden" data-hidden-former>
      @foreach($data['former_employees']['employees'] as $employees)
        <div class="md:span-6">
          @foreach($employees as $employee)
            <div class="team__member team__member--former">
              {{ $employee->firstname}} {{ $employee->name}}, {{ $employee->title}}@if ($employee->team_id == 2)* @endif
            </div>
          @endforeach
          @if ($loop->index == 1)<div class="mt-16x lg:mt-32x">* Ehemalige BS+EMI</div>@endif
        </div>
      @endforeach
    </div>
  @endif
</section>
@endsection
