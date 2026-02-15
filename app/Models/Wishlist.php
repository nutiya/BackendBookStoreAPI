<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    // If your table name is 'wishlists', no need to specify $table

    // Define fillable fields if needed
    protected $fillable = ['user_id', 'book_id'];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
