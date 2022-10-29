<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
//        dd(Order::orderBy('id')->first()->purchaser);
        return Inertia::render(
            'Order',
            [
                'filters' => Request::all('search', 'dateFrom', 'dateTo'),
                'orders' => Order::filter(Request::only('search', 'dateFrom', 'dateTo'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($order) => [
                        'id' => $order->id,
                        'invoice_number' => $order->invoice_number,
                        'purchaser' => $order->purchaser,
                        'distributor' => $order->distributor,
                        'referred_distributors' => $order->referred_distributors,
                        'order_date' => $order->order_date,
                        'order_total' => $order->order_total,
                        'percentage' => $order->percentage,
                        'commission' => $order->commission,
                    ]),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
