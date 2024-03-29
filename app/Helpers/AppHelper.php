<?php
namespace App\Helpers;
use Illuminate\Support\Str;

class AppHelper
{
  public static function slug($str = NULL)
  {
    $slug = '';
    $slug = str_replace(array('/'), array('-'), $str);
    $slug = Str::slug(AppHelper::transliterate($slug), '-');
    return $slug;
  }

  public static function nl2p($string = NULL)
  {
    $string = nl2br($string, false);
    return '<p>' . preg_replace('#(<br>[\r\n\s]+){2}#', '</p><p>', $string) . '</p>';
  }

  public static function transliterate($string = NULL)
  {
    $search = array(
      'ä', 'ö', 'ü', 'é', 'è', 'â', 'à', 'ç',
    );

    $replace = array(
      'ae', 'oe', 'ue', 'e', 'e', 'a', 'a', 'c',
    );
    return (string) str_replace($search, $replace, mb_strtolower($string, 'UTF-8'));
  }

  public static function number($amount)
  {
    return number_format(round($amount * 20) / 20, 2, '.', '');
  }

  public static function caption($item, $view = NULL)
  {
    // get config item from 'pages.php'
    $pages = config('pages');

    if ($view == 'home')
    {
      if ($item->isProject)
      {
        return $item->project?->title;
        
      }

      if ($item->isPage)
      {
        return $pages[$item->page];
      }
    }

    return $item->image?->caption ? $item->image->caption : NULL;
  }

  public static function projectTitle($title)
  {
    // Split the title by spaces
    $words = explode(' ', $title);

    // Wrap the last word in a span
    $words[count($words) - 1] = '<span class="icon-arrow-right-up-sm">' . $words[count($words) - 1] . '</span>';
    
    // Return the imploded array
    return implode(' ', $words);
  }

}