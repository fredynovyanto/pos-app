<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use Illuminate\Http\Request;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('spendings.index');
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
        $spending = Spending::create($request->all());

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
        $spending = Spending::find($id);

        return response()->json($spending);
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
        $spending = Spending::find($id);
        $spending->update($request->all());

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
        $spending = Spending::find($id);
        $spending->delete();

        return response(null, 204);
    }

    public function data()
    {
        $spending = Spending::orderBy('id', 'desc')->get();

        return datatables()
            ->of($spending)
            ->addIndexColumn()
            ->addColumn('created_at', function($spending) {
                return tanggal_indonesia($spending->created_at, false);
            })
            ->addColumn('nominal', function ($spending) {
                return 'Rp. '.format_uang($spending->nominal);
            })
            ->addColumn('aksi', function($spending) {
                return '
                    <button onclick="editForm(`'. route('spendings.update', $spending->id) .'`)" class="btn btn-sm btn-warning">edit</button>
                    <button onclick="deleteData(`'. route('spendings.destroy', $spending->id) .'`)" class="btn btn-sm btn-danger">hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
