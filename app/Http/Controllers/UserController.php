<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all(['search']),
            'users' => User::orderBy('first_name')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'image' => $user->image ? URL::route('image', ['path' => $user->image, 'w' => 45, 'h' => 45, 'fit' => 'crop']) : null,
                    'status' => $user->status,
                    'rol_name' => $user->rol_name,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'image' => Request::file('image') ? Request::file('image')->store('users') : null,
            'status' => Request::get('status'),
            'rol_name' => Request::get('rol_name'),
        ]);

        $user->syncRoles(Request::get('rol_name'));

        return Redirect::route('users')->with('success', 'Usuario creado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'image' => $user->image ? URL::route('image', ['path' => $user->image, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'status' => $user->status,
                'rol_name' => $user->rol_name
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request->user()->fill($request->validated());

        $user->update(Request::only('first_name', 'last_name', 'email', 'status', 'rol_name'));

        $user->syncRoles(Request::get('rol_name'));

        if (Request::file('image')) {
            if ($user->image !== null) {
                Storage::delete($user->image);
            }
            $user->update(['image' => Request::file('image')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Usuario actualizado.');
    }

    public function download_backup()
    {
        Artisan::call('backup:run');
        $path = storage_path('app/laravel-sql-backup/*.zip');
        $latest_ctime = 0;
        $latest_filename = '';
        $files = glob($path);

        foreach ($files as $file) {
            if (is_file($file) && filectime($file) > $latest_ctime) {
                $latest_ctime = filectime($file);
                $latest_filename = $file;
            }
        }
        return response()->download($latest_filename)->deleteFileAfterSend(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
