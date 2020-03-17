<?php

namespace App\Http\Controllers;

use App\Http\Models\purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $purchases = DB::table('purchases')
        ->join('suppliers','purchases.supplier_id','=','suppliers.id')
        ->select('purchases.*','suppliers.name as supplier_name')
        ->orderBy('date','DESC')
        ->get();
             return response()->json([
            'purchases'    => $purchases,
            ], 200);
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
            'date'=>'required',
            'supplier_id'=>'required',

             ]);

        $purchase = purchase::create([
             'date'=>request('date'),
             'supplier_id'=>request('supplier_id'),
             'state'=> 'IN_PROGRESS',
             'total_cost'=> 0,
         ]);

        return response()->json([
             'purchase'=> $purchase,
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

        $purchase = purchase::findOrFail($id);
        $purchase->total_cost += $request('total_cost');
        $purchase->save();

        return $purchase;
        // return response()->json([
        //     'purchase'    => $purchase,
        //     'message' => 'success'
        // ], 200);
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
