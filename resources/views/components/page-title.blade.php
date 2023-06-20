@props(['class' => null, 'type' => 'h1'])
<{{ $type }} class="content-title {{ $class }}">
  @if (request()->routeIs('page.project.show'))
    Projekte
  @elseif (request()->routeIs('page.worklist'))
    Werkliste
  @elseif (request()->routeIs('page.office.profile'))
    Profil
  @elseif (request()->routeIs('page.office.team'))
    Team
  @elseif (request()->routeIs('page.office.publications'))
    Publikationen
  @elseif (request()->routeIs('page.office.awards'))
    Auszeichnungen
  @elseif (request()->routeIs('page.office.jobs'))
    Jobs
  @elseif (request()->routeIs('page.contact'))
    Kontakt
  @endif
</{{ $type }}>