<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;
use Illuminate\View\View;

/**
 * @OA\Info(
 *     title="QSOFT test API",
 *     version="1.0.0",
 *     description="Документация API"
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get user list",
     *     tags={"Orders"},
     *     @OA\Response(response=200, description="Success, redirect to order create"),
     * )
     */
    public function index() : View
    {
        $orders = Order::where('status', 'Active')->get();
        return view('orders.index', compact('orders'));
//        return response()->json(OrderResource::collection($orders), 200);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create new order",
     *     tags={"Orders"},
     *          @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name", "email", "message"},
     *                  @OA\Property(property="name", type="string", example="user"),
     *                  @OA\Property(property="email", type="string", example="user@example.com"),
     *                  @OA\Property(property="message", type="string", example="some text")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Success, redirect to order create"),
     * )
     */
    public function store(Request $request) : RedirectResponse
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
     * @OA\Put(
     *     path="/orders/{id}",
     *     summary="Set comment",
     *     description="Set status Resolved, and add to order comment",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *                  @OA\Schema(
     *                          required={"comment"},
     *                          @OA\Property(property="comment", type="string", example="order comment text"),
     *                  )
     *           )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Sussecc operation"
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
                Mail::to($order->email)->send(new CommentMail($order->comment));
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
