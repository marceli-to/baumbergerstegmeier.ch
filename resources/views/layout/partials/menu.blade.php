<nav class="site" data-menu>
  <div>
    <ul>
      @foreach(config('pages') as $key => $page)
        <li>
          @if (isset($page['route']))
            <a href="{{ route($page['route']) }}" title="{{ $page['title'] }}">
              {{ $page['title'] }}
            </a>
          @else
            <a href="javascript:;" title="{{ $page['title'] }}" class="{{ request()->routeIs('page.' . $key . '*') ? '' : '' }}" data-menu-parent>
              {{ $page['title'] }}
            </a>
            @if ($key == 'project')
              @if ($menuProjects)
                <ul class="{{ request()->routeIs('page.' . $key . '*') ? 'is-current' : '' }}">
                  @foreach($menuProjects as $menuProjectState)
                    <li>
                      <a 
                        href="javascript:;" 
                        title="{{ $menuProjectState['state']->description }}" 
                        class="{{ isset($state) && $state->slug == $menuProjectState['state']->slug ? 'is-current' : '' }}" 
                        data-menu-parent 
                        data-menu-item-state>
                        {{ $menuProjectState['state']->description }}
                      </a>

                      {{-- Projects by state and categories --}}
                      @if ($menuProjectState['hasCategories'])
                        @if ($menuProjectState['categories'])
                          <ul class="{{ request()->routeIs('page.project.showByStateAndCategory') && isset($state) && $state->slug == $menuProjectState['state']->slug ? 'is-current' : '' }}">
                            @foreach($menuProjectState['categories'] as $menuProjectCategory)
                              <li>
                                <a href="javascript:;" 
                                  data-menu-parent 
                                  data-menu-item-category>
                                  {{ $menuProjectCategory->description}}
                                </a>
                                <ul class="{{ (isset($state) && $state->slug == $menuProjectState['state']->slug && isset($category) && $category->slug == $menuProjectCategory->slug) ? 'is-current' : '' }}">
                                  @foreach($menuProjectCategory->featuredProjects as $project)
                                    <li>
                                      <a 
                                        href="{{ route('page.project.showByStateAndCategory', ['state' => $menuProjectState['state']->slug, 'category' => $menuProjectCategory->slug, 'project' => $project->slug]) }}" 
                                        title="{{ $project->title_menu ? $project->title_menu : $project->title }}">
                                        {{ $project->title_menu ? $project->title_menu : $project->title }}
                                      </a>
                                    </li>
                                  @endforeach
                                </ul>
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      {{-- Projects by state without categories --}}
                      @else
                        @if ($menuProjectState['featuredProjects'])
                          <ul class="{{ isset($state) && $state->slug == $menuProjectState['state']->slug ? 'is-current' : '' }}">
                            <li>
                              <ul class="is-current">
                                @foreach($menuProjectState['featuredProjects'] as $project)
                                  <li>
                                    <a 
                                      href="{{ route('page.project.showByState', ['state' => $menuProjectState['state']->slug, 'project' => $project->slug]) }}" 
                                      title="{{ $project->title_menu ? $project->title_menu : $project->title }}">
                                      {{ $project->title_menu ? $project->title_menu : $project->title }}
                                    </a>
                                  </li>
                                @endforeach
                              </ul>
                            </li>
                          </ul>
                        @endif
                      @endif
                    </li>
                  @endforeach
                </ul>
              @endif
            @endif
            @if (isset($page['items']))
              <ul class="{{ request()->routeIs('page.' . $key . '*') ? 'is-current' : '' }}">
                @foreach($page['items'] as $key => $item)
                  <li>
                    <a href="{{ route($item['route']) }}" title="{{ $item['title'] }}" class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                      {{ $item['title'] }}
                    </a>
                  </li>
                @endforeach
              </ul>
            @endif
          @endif
        </li>
      @endforeach
    </ul>
  </div>
</nav>