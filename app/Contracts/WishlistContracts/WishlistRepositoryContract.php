<?php

namespace App\Contracts\WishlistContracts;

interface WishlistRepositoryContract
{
    public function addToWishlist(array $data);

    public function deleteByUserAndHotel(int $user_id, int $hotel_id);

    public function getByUserId(int $user_id);
}
