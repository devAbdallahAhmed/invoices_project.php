<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceReport extends Controller
{

    public function index()
    {
        return view('reports.invoice_report');
    }

    public function Search_invoices(Request $request){

        $rdio = $request->rdio;

        if ($rdio == 1) {


            if ($request->type && $request->start_at =='' && $request->end_at =='') {

                $invoices = Invoice::where('Status','=',$request->type)->get();
                $type = $request->type;
                return view('reports.invoice_report',compact('type'))->withDetails($invoices);
            }

            else {

                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;

                $invoices = Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
                return view('reports.invoice_report',compact('type','start_at','end_at'))->withDetails($invoices);

            }
        }
        else {

            $invoices = Invoice::where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoice_report')->withDetails($invoices);
        }
    }
}
