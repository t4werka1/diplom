<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            abort(403, 'Администратор не может использовать личные сообщения.');
        }

        $messages = Message::with(['sender:id,name,is_admin', 'recipient:id,name,is_admin'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->latest()
            ->get();

        $conversations = $messages
            ->filter(function (Message $message) use ($user) {
                $partner = $message->sender_id === $user->id ? $message->recipient : $message->sender;

                return $partner !== null && ! $partner->is_admin;
            })
            ->groupBy(function (Message $message) use ($user) {
                return $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id;
            })
            ->map(function ($conversationMessages) {
                return $conversationMessages->first();
            })
            ->sortByDesc('created_at');

        $unreadCounts = Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        return view('messages.index', compact('conversations', 'unreadCounts'));
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();

        $this->ensureMessagingAllowed($currentUser, $user);

        Message::where('sender_id', $user->id)
            ->where('recipient_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::with(['sender:id,name,is_admin', 'recipient:id,name,is_admin'])
            ->where(function ($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                    ->where('recipient_id', $user->id);
            })
            ->orWhere(function ($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $currentUser->id);
            })
            ->orderBy('created_at')
            ->paginate(20);

        return view('messages.show', [
            'partner' => $user,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, User $user)
    {
        $currentUser = Auth::user();

        $this->ensureMessagingAllowed($currentUser, $user);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'sender_id' => $currentUser->id,
            'recipient_id' => $user->id,
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route('messages.show', $user)
            ->with('success', 'Сообщение отправлено.');
    }

    protected function ensureMessagingAllowed(User $currentUser, User $targetUser): void
    {
        if ($currentUser->isAdmin() || $targetUser->isAdmin()) {
            abort(403, 'Обмен сообщениями с администратором недоступен.');
        }

        if ($currentUser->id === $targetUser->id) {
            abort(403, 'Нельзя отправлять сообщения самому себе.');
        }
    }
}
