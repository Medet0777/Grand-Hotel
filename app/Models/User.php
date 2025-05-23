<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'phone_number',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }


    public function userRatings()
    {
        return $this->hasMany(UserRating::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->save();
    }

}
