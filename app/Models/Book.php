<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'publisher_id',
        'language_id',
        'pages',
        'description',
        'price',
        'stock_quantity',
        'sold_count',
        'image_url',
        'isbn',
        'category_id',
        'publication_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_date' => 'date',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    protected static function booted()
    {
        static::deleting(function ($book) {
            $book->wishlists()->delete();
            $book->cartItems()->delete();
        });
    }


}