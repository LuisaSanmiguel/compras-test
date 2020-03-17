<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    public $fillable = ['id','name'];

    public function suplier()
{
    return $this->hasMany(purchase::class);
}
}
