<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::orderBy('name')->get();
        $deletePurchase = Purchase::where('total_items', '<', 1 )->first();
        if($deletePurchase) {
            $deletePurchase->delete();
        }
        return view('purchases.index', compact('supplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $purchase = new Purchase();
        $purchase->supplier_id = $id;
        $purchase->total_items = 0;
        $purchase->total_price = 0;
        $purchase->discount = 0;
        $purchase->pay = 0;
        $purchase->save();

        session(['id_purchase' => $purchase->id]);
        session(['id_supplier' => $purchase->supplier_id]);

        return redirect()->route('purchase_details.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = Purchase::findOrFail($request->id_purchase);
        $purchase->total_items = $request->total_item;
        $purchase->total_price = $request->total;
        $purchase->discount = $request->discount;
        $purchase->pay = $request->bayar;
        $purchase->update();

        $detail = PurchaseDetail::where('purchase_id', $purchase->id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            $product->stock += $item->amount;
            $product->update();
        }
        session()->forget('id_supplier');

        return redirect()->route('purchases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = PurchaseDetail::with('product')->where('purchase_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('code', function ($detail) {
                return '<span class="badge badge-success">'. $detail->product->code .'</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('original_price', function ($detail) {
                return 'Rp. '. format_uang($detail->original_price);
            })
            ->addColumn('amount', function ($detail) {
                return format_uang($detail->amount);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['code'])
            ->make(true);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $detail    = PurchaseDetail::where('purchase_id', $purchase->id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock -= $item->amount;
                $product->update();
            }
            $item->delete();
        }

        $purchase->delete();

        return response(null, 204);
    }

    public function data()
    {
        $purchase = Purchase::with('supplier')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('total_items', function ($purchase) {
                return format_uang($purchase->total_items);
            })
            ->addColumn('total_price', function ($purchase) {
                return 'Rp. '. format_uang($purchase->total_price);
            })
            ->addColumn('pay', function ($purchase) {
                return 'Rp. '. format_uang($purchase->pay);
            })
            ->addColumn('tanggal', function ($purchase) {
                return tanggal_indonesia($purchase->created_at, false);
            })
            ->addColumn('supplier', function ($purchase) {
                return $purchase->supplier->name;
            })
            ->editColumn('discount', function ($purchase) {
                return $purchase->discount . '%';
            })
            ->addColumn('aksi', function ($purchase) {
                return '
                    <button onclick="showDetail(`'. route('purchases.show', $purchase->id) .'`)" class="btn btn-sm btn-info">Lihat</button>
                    <button onclick="deleteData(`'. route('purchases.destroy', $purchase->id) .'`)" class="btn btn-sm btn-danger">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
