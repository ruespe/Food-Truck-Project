<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/ContactMessages/Index', [
            'messages' => ContactMessage::orderByDesc('created_at')->get(),
        ]);
    }

    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['read' => true]);

        return redirect()->route('admin.contact.index');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact.index')
            ->with('success', 'Mensaje eliminado.');
    }
}
