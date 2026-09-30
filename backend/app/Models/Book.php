<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Book extends Model
{
    use SoftDeletes;
    protected $table ='books';
    protected $fillable = [
        'title',
        'author',
        'gener_id',
        'user_id'
    ];
    protected function casts()
    {
        return ['deleted_at'=>'datetime',];
    }
    public function gener():BelongsTo{
        return $this -> belongsTo(Gener::class,'gener_id','id');
    }
    public function user():BelongsTo{
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
