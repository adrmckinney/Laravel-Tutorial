<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        // methods to cache the data
        $postCount = Cache::remember(
            'count.posts' . $user->id, //give it a key name
            now()->addSeconds(30),     //time duration before next cache
            function () use ($user) {  //return the data
                return $user->posts->count();
            });
        
        $followersCount = Cache::remember(
            'count.followers' . $user->id, 
            now()->addSeconds(30), 
            function () use ($user) {
                return $user->profile->followers->count();
            });
        
        $followingCount = Cache::remember(
            'count.following' . $user->id, 
            now()->addSeconds(30), 
            function () use ($user) {
                return $user->following->count();
            });

        $follows = (auth()->user() ? auth()->user()->following->contains($user->id) : false);

        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => ''
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();
        }
        
        $imagePath = request('image')->store('profile', 'public');

        auth()->user()->profile->update(array_merge(  //array_merge will merge the second data point (image=>imagePath) into the first data point at 'image'

            $data,
            ['image' => $imagePath]
        ));

        return redirect("/profile/{$user->id}");
    }
}
