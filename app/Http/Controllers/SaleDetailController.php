<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Setting;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('name')->get();
        $discount = Setting::first()->discount ?? 0;

        // Cek apakah ada transactions yang sedang berjalan
        if ($id_sale = session('id_sale')) {
            $sale = Sale::find($id_sale);

            return view('sale_details.index', compact('product', 'discount', 'id_sale', 'sale'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transactions.create');
            } else {
                return redirect()->route('dashboard');
            }
        }
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
        $product = Product::where('id', $request->product_id)->first();
        if (! $product) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new SaleDetail();
        $detail->sale_id = $request->id_sale;
        $detail->product_id = $product->id;
        $detail->selling_price = $product->selling_price;
        $detail->amount = 1;
        $detail->discount = $product->discount;
        $detail->subtotal = $product->selling_price - ($product->discount / 100 * $product->selling_price);;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
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
        $detail = SaleDetail::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = $detail->selling_price * $request->amount - (($detail->discount * $request->amount) / 100 * $detail->selling_price);
        $product = Product::find($detail->product_id);
        if($product->stock < $request->amount){
            return abort(400, 'Stok tidak cukup!');
        }
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detail = SaleDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function data($id)
    {
        $detail = SaleDetail::with('product')
            ->where('sale_id', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code'] = '<span class="label label-success">'. $item->product['code'] .'</span';
            $row['name'] = $item->product['name'];
            $row['selling_price']  = 'Rp. '. format_uang($item->selling_price);
            $row['amount']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->amount .'">';
            $row['discount']      = $item->discount . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transactions.destroy', $item->id) .'`)" class="btn btn-sm btn-danger">Hapus</button>
                                </div>';
            $data[] = $row;

            $total += $item->selling_price * $item->amount - (($item->discount * $item->amount) / 100 * $item->selling_price);;
            $total_item += $item->amount;
        }
        $data[] = [
            'code' => '
                <div class="total d-none">'. $total .'</div>
                <div class="total_items d-none">'. $total_item .'</div>',
            'name' => '',
            'selling_price'  => '',
            'amount'      => '',
            'discount'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'code', 'amount'])
            ->make(true);
    }

    public function loadForm($discount = 0, $total = 0, $received = 0)
    {
        $pay   = $total - ($discount / 100 * $total);
        $kembali = ($received != 0) ? $received - $pay : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'pay' => $pay,
            'payrp' => format_uang($pay),
            'terbilang' => ucwords(terbilang($pay). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
