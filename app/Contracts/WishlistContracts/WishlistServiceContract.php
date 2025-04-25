<?php

namespace App\Contracts\WishlistContracts;



interface WishlistServiceContract
{
    public function addToWishlist(int $user_id,int $hotel_id):bool;
    public function removeFromWishlist(int $user_id,int $hotel_id):bool;
    public  function getWishlistByUser(int $user_id);
}
