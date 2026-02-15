<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    // Table name is "authors" by default, so no need to specify $table

    // Fillable fields if you want mass assignment
    protected $fillable = ['name'];

    // One author has many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    protected static function booted()
    {
        static::deleting(function ($author) {
            $author->books()->delete();

        });
    }
}
