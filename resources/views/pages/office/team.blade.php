@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-teams">

  @if (isset($data['bsa_team']->name))
    <article class="team">
      <h2>{{ $data['bsa_team']->name }}</h2>
    </article>
    <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap">
      <div class="lg:span-6">
        @if (isset($data['bsa_team']->publishedImages[0]))
          <div class="mb-20x lg:mb-50x">
            <x-image 
              :maxSizes="[0 => 1200, 1000 => 1600]" 
              :image="$data['bsa_team']->publishedImages[0]" 
              width="1200" 
              height="800">
            </x-image>
          </div>
        @endif
        @if (isset($data['bsa_leadership']))
          <h3>{{ $data['bsa_leadership']['category']->name}}</h3>
        @endif
        @if (isset($data['bsa_leadership']['employees']))
          @foreach($data['bsa_leadership']['employees'] as $employee)
            <div class="team__member">
              <strong>{{ $employee->firstname}} {{ $employee->name}}</strong><br>
              {{ $employee->title}}

              @if ($employee->cv)
                <div>
                  <a href="javascript:;" class="icon-chevron" data-btn-cv>Lebenslauf</a>
                  <div data-cv class="hidden mt-6x mb-40x sm:mb-60x">
                    {{-- cv uncategorized --}}
                    @php $cv = $employee->cv->whereNull('cv_category_id')->sortBy('order'); @endphp
                    @if ($cv)
                      <div class="sm:grid sm:grid-cols-12 sm:grid-column-gap">
                        @foreach($cv as $item)
                          <div class="sm:span-2 sm:mb-1x">
                            {{ $item->periode }}
                          </div>
                          <div class="sm:span-10 mb-12x sm:mb-1x">
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
                          <div class="md:grid md:grid-cols-12 md:grid-column-gap">
                            @foreach($items->sortBy('order') as $item)
                              <div class="md:span-2 mb-1x">
                                {{ $item->periode }}
                              </div>
                              <div class="md:span-10 mb-1x">
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
      <div class="lg:span-6">
        @if (isset($data['bsa_team']->publishedImages[1]))
          <div class="mb-20x lg:mb-50x">
            <x-image 
              :maxSizes="[0 => 1200, 1000 => 1600]" 
              :image="$data['bsa_team']->publishedImages[1]" 
              width="1200" 
              height="800">
            </x-image>
          </div>
        @endif
        @if (isset($data['bsa_employees']))
          <h3>{{ $data['bsa_employees']['category']->name}}</h3>
        @endif
        @if (isset($data['bsa_employees']['employees']))
          <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap">
            @foreach($data['bsa_employees']['employees'] as $employees)
              <div class="lg:span-6">
                @foreach($employees as $employee)
                  <div class="team__member">
                    <strong>{{ $employee->firstname}} {{ $employee->name}}</strong><br>
                    {{ $employee->title}}
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  @endif
  
  @if (isset($data['bsemi_team']->name))
    <article class="team mt-80x">
      <h2>{{ $data['bsemi_team']->name }}</h2>
    </article>
    <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap">
      <div class="lg:span-6">
        @if (isset($data['bsemi_team']->publishedImages[0]))
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$data['bsemi_team']->publishedImages[0]" 
            width="1200" 
            height="800">
          </x-image>
        @endif
        @if (isset($data['bsemi_leadership']))
          <h3>{{ $data['bsemi_leadership']['category']->name}}</h3>
        @endif
        @if (isset($data['bsemi_leadership']['employees']))
          @foreach($data['bsemi_leadership']['employees'] as $employee)
            <div class="team__member">
              <strong>{{ $employee->firstname}} {{ $employee->name}}</strong><br>
              {{ $employee->title}}
            </div>
          @endforeach
        @endif
      </div>
      <div class="lg:span-6">
        @if (isset($data['bsemi_team']->publishedImages[1]))
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$data['bsemi_team']->publishedImages[1]" 
            width="1200" 
            height="800">
          </x-image>
        @endif
        @if (isset($data['bsemi_employees']))
          <h3>{{ $data['bsemi_employees']['category']->name}}</h3>
        @endif
        @if (isset($data['bsemi_employees']['employees']))
          <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap">
            @foreach($data['bsemi_employees']['employees'] as $employees)
              <div class="lg:span-6">
                @foreach($employees as $employee)
                  <div class="team__member">
                    <strong>{{ $employee->firstname}} {{ $employee->name}}</strong><br>
                    {{ $employee->title}}
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
      <h2>{{ $data['former_employees']['category']->name }}</h2>
    </article>
    <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap">
      @foreach($data['former_employees']['employees'] as $employees)
        <div class="lg:span-6">
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
