<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Ad $ad)
    {
        if (Auth::id() !== $ad->user_id) {
            Auth::user()->favorites()->attach($ad->id);
            return back()->with('success', 'Объявление добавлено в избранное');
        }
        return back()->with('error', 'Вы не можете добавить в избранное собственное объявление');
    }

    public function remove(Ad $ad)
    {
        Auth::user()->favorites()->detach($ad->id);
        return back()->with('success', 'Объявление удалено из избранного');
    }
} 