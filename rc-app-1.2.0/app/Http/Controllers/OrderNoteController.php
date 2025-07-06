<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\InternalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderNoteController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        InternalNote::create([
            'order_id' => $orderId,
            'user_id' => Auth::id(),
            'note' => $request->note,
        ]);

        return back()->with('success', 'Catatan internal disimpan.');
    }

}
