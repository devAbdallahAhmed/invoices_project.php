<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ArchiveInvoice extends Controller
{

    public function index()
    {

        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archive_invoice',compact('invoices'));
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


    public function update(Request $request)

    {
        $id = $request->invoice_id;
          Invoice::withTrashed()->where('id',$id)->restore();
        session()->flash('archive_invoice');
        return redirect('/invoices');
    }


    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
         $invoices =   Invoice::withTrashed()->where('id',$id)->first();
         $invoices->forceDelete();
          session()->flash('delete_invoice');
          return back();


    }
}
