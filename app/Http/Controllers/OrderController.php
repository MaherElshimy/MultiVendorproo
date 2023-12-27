<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Place a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method' => 'required|string',

            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Create a new order for the authenticated user
            $user = Auth::user();
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0, // We'll update this after adding order items
                'status' => 'pending',
                'payment_method' => $request->input('payment_method'),

            ]);

            // Calculate the total amount and create order items
            $totalAmount = 0;
            foreach ($request->input('items') as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw ValidationException::withMessages(['items' => 'Product not found']);
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $totalAmount += $orderItem->quantity * $orderItem->price;
            }

            // Update the total amount in the order
            $order->update(['total_amount' => $totalAmount]);

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json(['error' => 'Failed to place order', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get details of a specific order for the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetails($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the order for the authenticated user
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order]);
    }


    public function cancelOrder(Request $request, $id)
    {
        try {
            // Find the order
            $order = Order::find($id);

            // Check if the order exists
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Check if the authenticated user is the owner of the order
            $user = $request->user();
            if ($order->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized. You can only cancel your own orders.'], 403);
            }

            // Check if the order is already cancelled
            if ($order->is_cancelled) {
                return response()->json(['error' => 'Order is already cancelled']);
            }

            // Cancel the order
            $order->update(['is_cancelled' => true, 'status' => 'cancelled']);

            return response()->json(['message' => 'Order cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel order', 'message' => $e->getMessage()], 500);
        }
    }

}
