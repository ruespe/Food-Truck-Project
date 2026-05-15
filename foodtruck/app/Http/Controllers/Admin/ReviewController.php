<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index(): Response
    {
        $reviews = Review::with('user')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id'         => $r->id,
                'user_name'  => $r->user->name,
                'user_email' => $r->user->email,
                'rating'     => $r->rating,
                'comment'    => $r->comment,
                'visible'    => $r->visible,
                'created_at' => $r->created_at->format('d/m/Y H:i'),
            ]);

        return Inertia::render('admin/Reviews/Index', [
            'reviews' => $reviews,
        ]);
    }

    public function toggleVisible(Review $review): RedirectResponse
    {
        $review->update(['visible' => ! $review->visible]);

        $label = $review->visible ? 'aprobada' : 'ocultada';

        return back()->with('success', "Reseña {$label}.");
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Reseña eliminada.');
    }
}
