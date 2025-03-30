<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Order;
//use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;

class OrderController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/orders",
     *     summary="Get list of orders",
     *     tags={"Orders"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Post")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="User havent grants to change ",
     *     ),
     * )
     */
    public function index()
    {
        $orders = Order::where('status', 'Active')->get();
        return view('orders.index', compact('orders'));
//        return response()->json(OrderResource::collection(Order::all()), 200);
    }

    /**
     * @SWG\Post(
     *     path="/orders",
     *     summary="Set status resolved and add comment by Id",
     *     tags={"Orders"},
     *     description="Set status resolved and add comment by Id",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful added order",
     *         @SWG\Schema(ref="#/definitions/Post"),
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Unauthorized user",
     *     )
     * )
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

    /**
     * @SWG\Put(
     *     path="/orders/{order_id}",
     *     summary="Set status resolved and add comment by Id",
     *     tags={"Orders"},
     *     description="Set status resolved and add comment by Id",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Order"),
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Unauthorized",
     *     )
     * )
     */
    public function answer(Request $request, string $id)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:500'],
        ]);
        $order = Order::query()->findOrFail($id);

        // проверка пользователя на возможность осуществления редактирования заявки и смены статуса
        if (isset($validated['comment']) && isset($order))
        {
            if (Gate::allows('edit-orders', $order))
            {
                $validated['status'] = StatusEnum::Resolved;
                $order->update($validated);
                $this->sendTestEmail($order->email);
                return response('Комментарий добавлен, статус заказа установлек Resolved', 200);
            } else {
                abort(403);
            }
        }
        return redirect()->route('orders.index')->with('success', 'Была произведена отправка комментария пользователю и заявке присвоен статус Resolved' );
    }

    public function sendTestEmail(string $mail_to) : string
    {
        Mail::to($mail_to)->send(new CommentMail());
        return 'Письмо отправлено!';
    }
}
