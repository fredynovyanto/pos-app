<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_purchase = session('id_purchase');
        $product = Product::orderBy('name')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $discount = Purchase::find($id_purchase)->discount ?? 0;

        if (! $supplier) {
            abort(404);
        }

        return view('purchase_details.index', compact('id_purchase', 'product', 'supplier', 'discount'));
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

        $detail = new PurchaseDetail();
        $detail->purchase_id = $request->id_purchase;
        $detail->product_id = $product->id;
        $detail->original_price = $product->original_price;
        $detail->amount = 1;
        $detail->subtotal = $product->original_price;
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
        $detail = PurchaseDetail::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = $detail->original_price * $request->amount;
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
        $detail = PurchaseDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function data($id)
    {
        $detail = PurchaseDetail::with('product')
            ->where('purchase_id', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code'] = '<span class="badge badge-success">'. $item->product['code'] .'</span';
            $row['name'] = $item->product['name'];
            $row['original_price']  = 'Rp. '. format_uang($item->original_price);
            $row['amount']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->amount .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<button onclick="deleteData(`'. route('purchase_details.destroy', $item->id) .'`)" class="btn btn-sm btn-danger">Hapus</button>';
            $data[] = $row;

            $total += $item->original_price * $item->amount;
            $total_item += $item->amount;
        }
        $data[] = [
            'code' => '
                <div class="total d-none">'. $total .'</div>
                <div class="total_item d-none">'. $total_item .'</div>',
            'name' => '',
            'original_price'  => '',
            'amount'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'code', 'amount'])
            ->make(true);
    }

    public function loadForm($discount, $total)
    {
        $bayar = $total - ($discount / 100 * $total);
        $data  = [
            'total_price' => format_uang($total),
            'bayar' => $bayar,
            'pay' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
