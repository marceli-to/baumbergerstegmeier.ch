<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Static pages 
  |--------------------------------------------------------------------------
  |
  */

  'project' => [
    'title' => 'Projekte',
  ],

  'worklist' => [
    'title' => 'Werkliste',
    'route' => 'page.worklist',
  ],

  'office' => [
    'title' => 'Büro',
    'items' => [
      'profile' => [
        'title' => 'Profil',
        'route' => 'page.office.profile',
      ],
      'team' => [
        'title' => 'Team',
        'route' => 'page.office.team',
      ],
      'publications' => [
        'title' => 'Publikationen',
        'route' => 'page.office.publications',
      ],
      'awards' => [
        'title' => 'Auszeichnungen',
        'route' => 'page.office.awards',
      ],
      'jobs' => [
        'title' => 'Jobs',
        'route' => 'page.office.jobs',
      ],
    ],
  ],

  'contact' => [
    'title' => 'Kontakt',
    'route' => 'page.contact',
  ],

]
?>