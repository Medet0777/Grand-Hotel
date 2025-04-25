<?php

namespace App\Services\WishlistServices;

use App\Contracts\WishlistContracts\WishlistServiceContract;
use App\Facades\Repository;
use App\Models\Hotel;

class WishlistService implements WishlistServiceContract
{

    public function addToWishlist(int $user_id,int $hotel_id): bool
    {
        $user = Repository::user()->findById($user_id);
        $hotel = Hotel::find($hotel_id); // change after creating hotel logic
        if ($user && $hotel) {
            return (bool) Repository::wishlist()->addToWishlist(['user_id' => $user->id, 'hotel_id' => $hotel->id]);
        }

        return false;
    }

    public function removeFromWishlist(int $user_id,int $hotel_id): bool
    {
        $user = Repository::user()->findById($user_id);
        $hotel = Hotel::find($hotel_id); // change after creating hotel logic
        if ($user && $hotel) {
            return (bool) Repository::wishlist()->deleteByUserAndHotel($user->id, $hotel->id);
        }

        return false;
    }

    public function getWishlistByUser(int $user_id)
    {
        $user = Repository::user()->findById($user_id);

        if ($user) {
            return Repository::wishlist()->getByUserId($user->id);
        }

        return collect();
    }
}
