<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->with(['category' => function($query) {
                $query->with('parent');
            }])
            ->latest()
            ->paginate(12);

        $categories = Category::withCount(['ads' => function ($query) {
            $query->where('status', 'approved')->where('is_active', true);
        }])
            ->orderBy('ads_count', 'desc')
            ->take(8)
            ->get();
            
        return view('ads.index', compact('ads', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $condition = $request->input('condition');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $ads = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->when($query, function($q) use ($query) {
                return $q->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($condition, function($q) use ($condition) {
                return $q->where('condition', $condition);
            })
            ->when($minPrice, function($q) use ($minPrice) {
                return $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function($q) use ($maxPrice) {
                return $q->where('price', '<=', $maxPrice);
            })
            ->with(['category' => function($query) {
                $query->with('parent');
            }])
            ->latest()
            ->paginate(12);

        $categories = Category::withCount(['ads' => function ($query) {
            $query->where('status', 'approved')->where('is_active', true);
        }])
            ->orderBy('ads_count', 'desc')
            ->take(8)
            ->get();

        return view('ads.index', compact('ads', 'categories', 'query', 'condition', 'minPrice', 'maxPrice'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('ads.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,used',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'reason_for_sale' => 'nullable|string|max:500',
        ]);

        $ad = new Ad($request->except('image'));
        $ad->user_id = Auth::id();
        $ad->is_active = false;
        $ad->status = 'pending';
        $ad->sold_at = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $ad->image = $path;
        }

        $ad->save();

        return redirect()->route('ads.show', $ad)
            ->with('success', 'Объявление успешно создано и отправлено на модерацию!');
    }

    public function show(Ad $ad)
    {
        $ad->load(['category' => function($query) {
            $query->select('id', 'name', 'icon');
        }]);
        return view('ads.show', compact('ad'));
    }

    public function edit(Ad $ad)
    {
        $this->authorize('update', $ad);
        $categories = Category::all();
        return view('ads.edit', compact('ad', 'categories'));
    }

    public function update(Request $request, Ad $ad)
    {
        $this->authorize('update', $ad);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,used',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'reason_for_sale' => 'nullable|string|max:500',
        ]);

        $ad->fill($request->except('image'));
        
        // При редактировании одобренного, отклоненного или проданного объявления возвращаем его на модерацию.
        if ($ad->status === 'approved' || $ad->status === 'rejected' || $ad->isSold()) {
            $ad->status = 'pending';
            $ad->is_active = false;
            $ad->rejection_reason = null; // Очищаем причину отклонения
            $ad->sold_at = null;
        }

        if ($request->hasFile('image')) {
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $path = $request->file('image')->store('ads', 'public');
            $ad->image = $path;
        }

        $ad->save();

        $message = $ad->status === 'pending' 
            ? 'Объявление успешно обновлено и отправлено на повторную модерацию!' 
            : 'Объявление успешно обновлено!';

        return redirect()->route('ads.show', $ad)
            ->with('success', $message);
    }

    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);

        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return redirect()->route('ads.index')
            ->with('success', 'Объявление успешно удалено!');
    }

    public function myAds()
    {
        $ads = Auth::user()->ads()->latest()->paginate(12);
        return view('ads.my-ads', compact('ads'));
    }

    public function markAsSold(Ad $ad)
    {
        $this->authorize('update', $ad);

        if ($ad->isSold()) {
            return redirect()->route('ads.show', $ad)
                ->with('error', 'Объявление уже отмечено как проданное.');
        }

        if ($ad->status !== 'approved') {
            return redirect()->route('ads.show', $ad)
                ->with('error', 'Отметить как проданное можно только одобренное объявление.');
        }

        $ad->update([
            'sold_at' => now(),
            'is_active' => false,
        ]);

        return redirect()->route('ads.show', $ad)
            ->with('success', 'Объявление отмечено как проданное.');
    }

    public function category(Category $category)
    {
        $ads = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->where('category_id', $category->id)
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::withCount(['ads' => function ($query) {
            $query->where('status', 'approved')->where('is_active', true);
        }])
            ->orderBy('ads_count', 'desc')
            ->take(8)
            ->get();

        return view('ads.index', compact('ads', 'categories', 'category'));
    }
} 