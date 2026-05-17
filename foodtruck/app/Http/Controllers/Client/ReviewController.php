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

        $existingReview = Review::where('user_id', $user->id)->first();

        // Si tiene reseña aprobada o pendiente, bloquear
        if ($existingReview && ! $existingReview->rejected) {
            abort(403, 'Ya tienes una reseña enviada.');
        }

        // Si tiene reseña rechazada, necesita un pedido entregado DESPUÉS del rechazo
        if ($existingReview && $existingReview->rejected) {
            $hasNewOrder = Order::where('user_id', $user->id)
                ->where('status', 'delivered')
                ->where('updated_at', '>', $existingReview->rejected_at)
                ->exists();
            abort_unless($hasNewOrder, 403, 'Necesitas completar otro pedido para volver a reseñar.');
        }

        // Sin reseña: debe tener al menos un pedido entregado
        if (! $existingReview) {
            $hasDelivered = Order::where('user_id', $user->id)->where('status', 'delivered')->exists();
            abort_unless($hasDelivered, 403, 'Necesitas tener al menos un pedido entregado para dejar una reseña.');
        }

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating'      => $data['rating'],
                'comment'     => $data['comment'] ?? null,
                'visible'     => false,
                'rejected'    => false,
                'rejected_at' => null,
            ]
        );

        return back()->with('success', 'Reseña enviada. Será visible tras la aprobación.');
    }
}
