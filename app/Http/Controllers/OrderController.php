<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {


        return response()->json(OrderResource::collection(Order::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' =>  ['required', 'string', 'max:100', 'regex:/^[a-zA-Z]+$/u'],
            'email' => 'required|email|max:200',
            'message' => 'required|string|max:500',
        ]);
//        dd($validated);
        $order = new Order($validated);
        $order->save();
        return redirect()->route('home')->with('success', 'Ваша заявка создана');
    }

    public function list(Order $order) {

    }
}
