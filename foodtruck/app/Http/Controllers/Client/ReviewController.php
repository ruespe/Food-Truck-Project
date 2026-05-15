<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Solo puede reseñar si tiene al menos un pedido entregado
        $hasDelivered = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->exists();

        abort_unless($hasDelivered, 403, 'Necesitas tener al menos un pedido entregado para dejar una reseña.');

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $existing = Review::where('user_id', $user->id)->first();

        Review::updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating'  => $data['rating'],
                'comment' => $data['comment'] ?? null,
                // Al editar se vuelve a ocultar para re-moderación
                'visible' => $existing ? false : false,
            ]
        );

        return back()->with('success', 'Reseña enviada. Será visible tras la aprobación.');
    }
}
