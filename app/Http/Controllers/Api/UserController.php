<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\DataCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  /**
   * Get users info by authenticated user
   */
  public function find()
  {
    $user = auth()->user();
    return response()->json(['firstname' => $user->firstname, 'name' => $user->name]);
  }

  /**
   * Display a listing of the resource (the admin logins).
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(User::orderBy('name')->orderBy('firstname')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  User $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return response()->json(['user' => $user]);
  }

  /**
   * Store a newly created resource in storage. Every user is an admin.
   *
   * @param  UserStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserStoreRequest $request)
  {
    $user = new User($request->only('firstname', 'name', 'email', 'password'));
    $user->role = 'admin';
    $user->save();
    return response()->json(['userId' => $user->id]);
  }

  /**
   * Update the specified resource; an empty password keeps the current one.
   *
   * A new password ends the user's other sessions (Sanctum's
   * AuthenticateSession compares the password hash). For one's own account
   * the guard gets the updated user, so the current session stores the new
   * hash and stays logged in.
   *
   * @param  User $user
   * @param  UserStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(User $user, UserStoreRequest $request)
  {
    $user->fill($request->only('firstname', 'name', 'email'));
    if ($request->filled('password')) {
      $user->password = $request->input('password');
    }
    $user->save();

    if ($request->user()->is($user)) {
      Auth::guard('web')->setUser($user);
    }

    return response()->json('successfully updated');
  }

  /**
   * Remove the specified resource from storage. One's own account can't be
   * deleted, so there is always a user left.
   *
   * @param  User $user
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user, Request $request)
  {
    abort_if($request->user()->is($user), 403, 'Das eigene Konto kann nicht gelöscht werden.');
    $user->delete();
    return response()->json('successfully deleted');
  }
}
