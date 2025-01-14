<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
class CustomersReport extends Controller
{
    public function index(){

        $sections = Section::all();
        return view('reports.customers_report',compact('sections'));
    }
    public function Search_customers(Request $request){
        if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {

            $invoices = Invoice::where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections = Invoice::all();
            return view('reports.customers_report',compact('sections'))->withDetails($invoices);
        }
        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $invoices = Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report',compact('sections'))->withDetails($invoices);
        }
    }
}
