<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  public function get()
  {
    $linklist = config('linklist');
    return response()->json($linklist);
  }
}