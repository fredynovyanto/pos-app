<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all()->pluck('name', 'id');
        return view('products.index', compact('category'));
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
        $product = Product::latest()->first() ?? new Product();
        $request['code'] = 'P'. tambah_nol_didepan((int)$product->id +1, 6);
        $product = Product::create($request->all());

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
        $product = Product::find($id);

        return response()->json($product);
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
        $product = Product::find($id);
        $product->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach($request->product_id as $id) {
            $product = Product::find($id);
            $product->delete();
        }

        return response(null, 204);
    }


    public function data()
    {
        $product = Product::with('category')
            // ->orderBy('id', 'desc')
            ->get();

        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function($product) {
                return '<input type="checkbox" name="product_id[]" value="'.$product->id.'"></input>';
            })
            ->addColumn('code', function($product) {
                return '<span class="badge badge-primary">'.$product->code.'</span>';
            })
            ->addColumn('original_price', function($product) {
                return 'Rp. '.format_uang($product->original_price);
            })
            ->addColumn('selling_price', function($product) {
                return 'Rp. '.format_uang($product->selling_price);
            })
            ->addColumn('stock', function($product) {
                return format_uang($product->stock);
            })
            ->addColumn('kategori', function($product) {
                return $product->category->name;
            })
            ->addColumn('aksi', function($product) {
                return '
                    <button type="button" onclick="editForm(`'. route('products.update', $product->id) .'`)" class="btn btn-sm btn-warning">edit</button>
                    <button type="button" onclick="deleteData(`'. route('products.destroy', $product->id) .'`)" class="btn btn-sm btn-danger">hapus</button>
                ';
            })
            ->rawColumns(['aksi', 'code', 'select_all'])
            ->make(true);
    }

    public function cetakBarcode(Request $request)
    {
        $product_data = array();
        foreach ($request->product_id as $id) {
            $product = Product::find($id);
            $product_data[] = $product;
        }

        $no  = 1;
        $pdf = PDF::loadView('products.barcode', compact('product_data', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('products.pdf');
    }
}