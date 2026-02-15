<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Book;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function wishlistBooks()
    {
        return $this->belongsToMany(Book::class, 'wishlists')->withTimestamps();
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->orders()->delete();
            $user->wishlistBooks()->detach();
            $user->cartItems()->delete();


        });
    }





}