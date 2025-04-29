<?php

namespace App\Repositories;

use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use App\Models\Wishlist;

class WishlistRepository implements WishlistRepositoryContract
{
    public function addToWishlist(array $data)
    {
        return Wishlist::create($data);
    }

    public function deleteByUserAndHotel(int $user_id, int $hotel_id)
    {
        return Wishlist::where('user_id', $user_id)
            ->where('hotel_id', $hotel_id)
            ->delete();
    }

    public function getByUserId(int $user_id)
    {
        return Wishlist::where('user_id', $user_id)->get();
    }
}
