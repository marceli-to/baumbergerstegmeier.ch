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
                      <a href="javascript:;" title="{{ $menuProjectState['description'] }}" class="{{ isset($state) && $state->slug == $menuProjectState['slug'] ? 'is-current' : '' }}" data-menu-parent data-menu-item-state>
                        {{ $menuProjectState['description'] }}
                      </a>
                      @if ($menuProjectState['categories'])
                        <ul class="{{ isset($state) && $state->slug == $menuProjectState['slug'] ? 'is-current' : '' }}">
                          @foreach($menuProjectState['categories'] as $menuProjectCategory)
                            @if ($menuProjectCategory['featuredProjects'] && $menuProjectCategory['featuredProjects']->count() > 0)
                              <li>
                                <a href="javascript:;" title="{{ $menuProjectCategory['description'] }}" class="text-lg {{ isset($category) && $category->slug == $menuProjectCategory['slug'] ? 'is-active' : '' }}" data-menu-parent data-menu-item-category>
                                  {{ $menuProjectCategory['description'] }}
                                </a>
                                <ul class="{{ isset($category) && $category->slug == $menuProjectCategory['slug'] ? 'is-current' : '' }}">
                                  @foreach($menuProjectCategory['featuredProjects'] as $project)
                                    <li>
                                      <a href="{{ route('page.project.show', ['state' => $menuProjectState['slug'], 'category' => $menuProjectCategory['slug'], 'project' => $project->slug]) }}" title="{{ $project->title }}">
                                        {{ $project->title_menu ? $project->title_menu : $project->title }}
                                      </a>
                                    </li>
                                  @endforeach
                                </ul>
                              </li>
                            @endif
                          @endforeach
                        </ul>
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