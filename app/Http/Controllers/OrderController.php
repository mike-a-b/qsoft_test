<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Order;
//use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $orders = Order::where('status', 'Active')->get();
        return view('orders.index', compact('orders'));
//        return response()->json(OrderResource::collection(Order::all()), 200);
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

    public function answer(Request $request, string $id)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:500'],
        ]);
        $order = Order::query()->findOrFail($id);

        if (isset($validated['comment']))
        {
            $validated['status'] = StatusEnum::Resolved;
            $order->update($validated);
        }

        //        TODO: send email to user with comment and test it
        $this->sendTestEmail($order->email);

        return redirect()->route('orders.index')
                ->with('success', 'Была произведена отправка комментария пользователю,
                и заявке присвоен статус Resolved' );

    }

    public function sendTestEmail(string $mail_to) : string
    {
        Mail::to($mail_to)->send(new CommentMail());
        return 'Письмо отправлено!';
    }
}
