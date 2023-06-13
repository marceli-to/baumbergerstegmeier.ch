<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
// use App\Models\Project;
// use App\Models\Diary;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function __construct()
  {
    // view()->share('menuProjects', Project::flagged('isPublished')->orderBy('order')->get());
    // view()->share('hasDiary', Diary::flagged('isPublished')->first() ? true : false);
  }
}
