<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of ads pending moderation.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $ads = Ad::with(['user', 'category'])
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        $pendingCount = Ad::where('status', 'pending')->count();
        $approvedCount = Ad::where('status', 'approved')->count();
        $rejectedCount = Ad::where('status', 'rejected')->count();

        return view('admin.ads.index', compact('ads', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Show the form for reviewing a specific ad.
     */
    public function show(Ad $ad)
    {
        $ad->load(['user', 'category']);
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Approve an ad.
     */
    public function approve(Ad $ad)
    {
        $ad->status = 'approved';
        $ad->is_active = true;
        $ad->save();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Объявление одобрено и опубликовано!');
    }

    /**
     * Reject an ad.
     */
    public function reject(Request $request, Ad $ad)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $ad->status = 'rejected';
        $ad->is_active = false;
        if ($request->has('rejection_reason')) {
            $ad->rejection_reason = $request->rejection_reason;
        }
        $ad->save();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Объявление отклонено!');
    }

    /**
     * Delete an ad.
     */
    public function destroy(Ad $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Объявление удалено!');
    }
}
