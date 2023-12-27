<?php

namespace App\Http\Controllers;
use App\Models\Wishlist;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Add a product to the wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToWishlist(Request $request, $productId)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Check if the product is already in the wishlist
            $wishlist = Wishlist::firstOrNew(['user_id' => $user->id, 'product_id' => $productId]);
            $wishlist->save();

            return response()->json(['message' => 'Product added to wishlist']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add product to wishlist'], 500);
        }
    }

/**
 * Remove a product from the wishlist.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $productId
 * @return \Illuminate\Http\JsonResponse
 */
public function removeFromWishlist(Request $request, $productId)
{
    try {
        // Get the authenticated user
        $user = $request->user();

        // Check if the product is in the wishlist
        $wishlistItem = Wishlist::where(['user_id' => $user->id, 'product_id' => $productId])->first();

        if (!$wishlistItem) {
            return response()->json(['message' => 'Product not found in the wishlist']);
        }

        // Remove the product from the wishlist
        $wishlistItem->delete();

        return response()->json(['message' => 'Product removed from wishlist']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to remove product from wishlist'], 500);
    }
}
}

