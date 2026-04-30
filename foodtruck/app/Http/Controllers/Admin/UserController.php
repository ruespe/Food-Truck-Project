<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::withCount('orders')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email', 'role', 'active', 'email_verified_at', 'created_at']);

        return Inertia::render('admin/Users/Index', [
            'users' => $users->map(fn ($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'role'       => $u->role,
                'active'     => $u->active,
                'verified'   => ! is_null($u->email_verified_at),
                'orders_count' => $u->orders_count,
                'created_at' => $u->created_at->format('d/m/Y'),
            ]),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        // Prevent demoting yourself
        abort_if($user->id === Auth::id(), 403, 'No puedes cambiar tu propio rol.');

        $request->validate(['role' => 'required|in:admin,client']);
        $user->update(['role' => $request->role]);

        return back()->with('success', "Rol de {$user->name} actualizado a {$request->role}.");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_if($user->id === Auth::id(), 403, 'No puedes desactivarte a ti mismo.');

        $user->update(['active' => ! $user->active]);
        $label = $user->active ? 'activada' : 'desactivada';

        return back()->with('success', "Cuenta de {$user->name} {$label}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->id === Auth::id(), 403, 'No puedes eliminarte a ti mismo.');

        $user->delete();

        return back()->with('success', "Usuario {$user->name} eliminado.");
    }
}
