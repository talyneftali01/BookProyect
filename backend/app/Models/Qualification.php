<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    protected $table = 'qualifications';
    
    protected $fillable = ['user_id', 'book_id', 'stars'];

    public function book(): BelongsTo {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
