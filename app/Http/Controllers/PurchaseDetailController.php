<?php

namespace App\Http\Controllers;

use App\Http\Models\Purchase;
use App\Http\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'purchase_id'        => 'required',
            'product_id'         => 'required',
            'quantity'           => 'required',
            'cost'               => 'required',
            'total_cost'         => 'required',
             ]);

        $purchaseDetail = purchaseDetail::create([
             'purchase_id'        => request('purchase_id'),
             'product_id'         => request('product_id'),
             'quantity'           => request('quantity'),
             'cost'               => request('cost'),
             'total_cost'         => request('total_cost'),
         ]);

        $purchase = Purchase::find(request('purchase_id'));
        $purchase->total_cost = $purchase->total_cost+$purchaseDetail->total_cost;
        $purchase->save();

         return response()->json([
            'purchaseDetail'    => $purchaseDetail,
            'message' => 'success'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

            $purchaseDetails = DB::table('purchase_details')
            ->where('purchase_details.purchase_id','=',$id)
            ->join('products','purchase_details.product_id','=','products.id')
            ->select('purchase_details.*','products.name as product_name')
            ->get();

             return response()->json([
            'purchaseDetails'    => $purchaseDetails,
            ], 200);
    }

    public function one($id)
    {
        //

            $purchaseDetail = PurchaseDetail::where('purchase_details.id','=',$id)
            ->get();

             return response()->json([
            'purchaseDetail'    => $purchaseDetail,
            ], 200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $purchaseDetail = purchaseDetail::findOrFail($id);

        $purchase = Purchase::find(request('purchase_id'));
        $purchase->total_cost = ($purchase->total_cost - $purchaseDetail->total_cost )+ request('total_cost');
        $purchase->save();

        $purchaseDetail->purchase_id = request('purchase_id');

        $purchaseDetail->quantity = request('quantity');
        $purchaseDetail->cost = request('cost');
        $purchaseDetail->total_cost = request('total_cost');

        $purchaseDetail->save();

        return response()->json([
            'purchaseDetail'    => $purchaseDetail,
            'message' => 'success'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
