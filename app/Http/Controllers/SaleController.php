<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Setting;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deleteSale = Sale::where('total_items', '<', 1 )->first();
        if($deleteSale) {
            $deleteSale->delete();
        }
        return view('sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sale = new Sale();
        $sale->total_items = 0;
        $sale->price = 0;
        $sale->discount = 0;
        $sale->pay = 0;
        $sale->received = 0;
        $sale->user_id = auth()->id();
        $sale->save();

        session(['id_sale' => $sale->id]);
        return redirect()->route('transactions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sale = Sale::findOrFail($request->id_sale);
        $sale->total_items = $request->total_items;
        $sale->price = $request->total;
        $sale->discount = $request->discount;
        $sale->pay = $request->pay;
        $sale->received = $request->received;
        $sale->update();

        $detail = SaleDetail::where('sale_id', $sale->id)->get();
        foreach ($detail as $item) {
            $item->discount = $request->discount;
            $item->update();

            $product = Product::find($item->product_id);
            $product->stock -= $item->amount;
            $product->update();
        }

        return redirect()->route('transactions.finish');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = SaleDetail::with('product')->where('sale_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('code', function ($detail) {
                return '<span class="badge badge-success">'. $detail->product->code .'</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('selling_price', function ($detail) {
                return 'Rp. '. format_uang($detail->selling_price);
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
        $sale = Sale::find($id);
        $detail = SaleDetail::where('sale_id', $sale->id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock += $item->amount;
                $product->update();
            }

            $item->delete();
        }

        $sale->delete();

        return response(null, 204);
    }

    public function data()
    {
        $sale = Sale::orderBy('id', 'desc')->get();
        
        return datatables()
            ->of($sale)
            ->addIndexColumn()
            ->addColumn('total_items', function ($sale) {
                return format_uang($sale->total_items);
            })
            ->addColumn('price', function ($sale) {
                return 'Rp. '. format_uang($sale->price);
            })
            ->addColumn('pay', function ($sale) {
                return 'Rp. '. format_uang($sale->pay);
            })
            ->addColumn('tanggal', function ($sale) {
                return tanggal_indonesia($sale->created_at, false);
            })
            ->editColumn('discount', function ($sale) {
                return $sale->discount . '%';
            })
            ->editColumn('kasir', function ($sale) {
                return $sale->user->name ?? '';
            })
            ->addColumn('aksi', function ($sale) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('sales.show', $sale->id) .'`)" class="btn btn-sm btn-info">Lihat</button>
                    <button onclick="deleteData(`'. route('sales.destroy', $sale->id) .'`)" class="btn btn-sm btn-danger">Hapus</button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function finish()
    {
        $setting = Setting::first();

        return view('sales.finish', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id_sale'));
        if (! $sale) {
            abort(404);
        }
        $detail = SaleDetail::with('product')
            ->where('sale_id', session('id_sale'))
            ->get();
        
        return view('sales.nota_kecil', compact('setting', 'sale', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id_sale'));
        if (! $sale) {
            abort(404);
        }
        $detail = SaleDetail::with('product')
            ->where('sale_id', session('id_sale'))
            ->get();

        $pdf = PDF::loadView('sales.nota_besar', compact('setting', 'sale', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
