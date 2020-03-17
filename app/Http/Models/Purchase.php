<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    public $fillable = ['consecutive','date','supplier_id','state','total_cost'];


public function suplier()
{
    return $this->belongsTo(supplier::class);
}

}
