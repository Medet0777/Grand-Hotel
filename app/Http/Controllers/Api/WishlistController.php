<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\WishlistContracts\WishlistServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\Service;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request): JsonResponse
    {
        $hotelId = $request->input('hotel_id');
        $userId = Auth::id();

        if (!$hotelId) {
            return response()->json(['message' => 'Необходимо указать ID отеля'], 400);
        }

        if (Service::wishlist()->addToWishlist($userId, $hotelId)) {
            return response()->json(['message' => 'Отель успешно добавлен в избранное'], 201);
        } else {
            return response()->json(['message' => 'Не удалось добавить отель в избранное'], 400);
        }
    }

    public function removeFromWishlist(int $hotelId): JsonResponse
    {
        $userId = Auth::id();

        if (Service::wishlist()->removeFromWishlist($userId, $hotelId)) {
            return response()->json(['message' => 'Отель успешно удален из избранного'], 200);
        } else {
            return response()->json(['message' => 'Отель не найден в избранном'], 404);
        }
    }

    public function getWishlist(): JsonResponse
    {
        $userId = Auth::id();
        $wishlistItems = Service::wishlist()->getWishlistByUser($userId);
        return response()->json($wishlistItems, 200);
    }
}
