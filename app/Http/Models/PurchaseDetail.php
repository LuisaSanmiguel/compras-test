<?php


namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    //
    public $fillable = ['id','purchase_id','product_id','quantity','cost','total_cost'];

            public function purchase()
        {
            return $this->belongsTo(purchase::class);
        }

            public function product()
            {
                return $this->hasMany(product::class);
            }
}
