<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\DataCollection;
use App\Http\Requests\CategoryStoreRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Category::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Category $category
   * @return \Illuminate\Http\Response
   */
  public function find(Category $category)
  {
    return response()->json(['category' => Category::find($category->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\CategoryStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CategoryStoreRequest $request)
  {
    $category = Category::create([
      'description' => $request->input('description'),
      'slug' => \Str::slug($request->input('description')),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['categoryId' => $category->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\CategoryStoreRequest  $request
   * @param  Category $category
   * @return \Illuminate\Http\Response
   */
  public function update(CategoryStoreRequest $request, Category $category)
  {
    $category = Category::findOrFail($category->id);
    $category->description = $request->input('description');
    $category->publish = $request->input('publish');
    $category->slug = \Str::slug($request->input('description'));
    $category->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given category
   *
   * @param  Category $category
   * @return \Illuminate\Http\Response
   */
  public function toggle(Category $category)
  {
    $category->publish = !$category->publish;
    $category->save();
    return response()->json($category->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Category $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    $category->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the categories
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $categories = $request->get('categories');
    foreach($categories as $category)
    {
      $c = Category::find($category['id']);
      $c->order = $category['order'];
      $c->save(); 
    }
    return response()->json('successfully updated');
  }

}