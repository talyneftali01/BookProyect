<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $table = 'favorites';
    
    protected $fillable = ['user_id', 'book_id'];

    // Relación para poder saber qué libro está dentro de este favorito
    public function book(): BelongsTo {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
