<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['name'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

        protected static function booted()
    {
        static::deleting(function ($language) {
            $language->books()->delete();

        });
    }
}
