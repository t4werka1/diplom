<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ad;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount(['ads' => function ($query) {
            $query->where('status', 'approved')->where('is_active', true);
        }])
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load('children');
        
        // Получаем объявления из категории и всех её подкатегорий
        $categoryIds = [$category->id];
        foreach ($category->children as $child) {
            $categoryIds[] = $child->id;
        }
        
        $ads = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->whereIn('category_id', $categoryIds)
            ->with(['user', 'category'])
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'ads'));
    }
}
