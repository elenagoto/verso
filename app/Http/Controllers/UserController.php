<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = User::withCount('articles')->orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $articles = $user->articles()->published()->latest()->get();

        return view('users.show', compact('user', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

        Gate::authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {

        $user->update($request->validated());

        return redirect()->route('users.show', $user);
    }
}
