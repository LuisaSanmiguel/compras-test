<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    //
    public $fillable = ['id','name'];

    public function suplier()
{
    return $this->hasMany(purchase::class);
}
}
