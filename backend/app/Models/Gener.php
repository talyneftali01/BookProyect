<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gener extends Model
{
    protected $table='geners';
    protected $fillable = ['name'];
    public function books():HasMany{
        return $this -> hasMany(Book::class,'gener_id', 'id');
    }
}
