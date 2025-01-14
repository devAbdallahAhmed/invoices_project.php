<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Http\Requests\InvoiceValidate;
use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoice_details;
use App\Models\Section;
use App\Models\User;
use App\Notifications\Add_invoice_new;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoice',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'invoice_number' => 'required|unique',
            'invoice_Date' => 'required|data',
            'Due_date' => 'required',
            'product' => 'required',
            'section_id' => 'required',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
        ], [
            'invoice_number.required' => 'يجب إدخال رقم الفاتورة.',
            'invoice_number.unique' => 'رقم الفاتوره موجود مسبقا.',
            'invoice_Date.required' => 'يجب إدخال تاريخ الفاتورة.',
            'Due_date.required' => 'يجب إدخال تاريخ الاستحقاق.',
            'product.required' => 'يجب إدخال اسم المنتج.',
            'section_id.required' => 'يجب إدخال اسم القسم.',
            'Amount_collection.required' => 'يجب إدخال مبلغ التحصيل.',
            'Amount_Commission.required' => 'يجب إدخال مبلغ العمولة.',
        ]);


        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        Invoice_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'created_at'=>$request->created_at,
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);
        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = username();
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);

        }

//        $user = User::first();
//        Notification::send($user, new AddInvoice($invoice_id));

        $user = User::where('id','!=',Auth::user()->id)->get();
        $invoices = Invoice::latest()->first();
        Notification::send($user, new \App\Notifications\Add_invoice_new($invoices));



        session()->flash('Add','تم اضافه البيانات بنجاح');
        return redirect('/invoices');

    }



    public function show($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        return view('invoices.status_show',compact('invoices'));
    }

    public function edit($id)
    {

        $invoices = Invoice::where( 'id',$id)->first();
        $sections = Section::all();
        return  view('invoices.invoice_edit',compact('sections','invoices'));

    }

    public function update(Request $request)
    {
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);



        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();


    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::where('id',$id)->first();
        $details= Invoice_attachments::where('invoice_id',$id)->first();

        $id_page = $request->id_page;
        if (!$id_page==2){

            if(!empty($details->invoice_number)) {
                Storage::disk('public_upload')->deleteDirectory($details->invoice_number);
            }
                $invoices->forceDelete();
                session()->flash('delete_invoice');
                return redirect('/invoices');
        }else{

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('Archive_Invoice');
        }

    }

    public function getProducts($id)
    {
       $products= DB::table('products')->where('section_id', $id)->pluck('Product_name', 'id');
       return json_encode($products);

    }


    public function Status_update($id, Request $request)
    {
        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoice_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            Invoice_details ::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }

    public function Invoice_Paid()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid',compact('invoices'));
    }


    public function Invoice_Unpaid()
    {
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }
    public function Invoice_Partial()
    {
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('invoices.invoices_partial',compact('invoices'));
    }

    public function Print_invoice($id)
    {
        $invoices =  Invoice::where('id',$id)->first();
        return view('invoices.print_invoice',compact('invoices'));
    }


    public function export()
    {
        return Excel::download(new InvoiceExport, 'Invoice.xlsx');
    }


    public function MarkAsRead_all (Request $request)
    {

        $userUnreadNotification= auth()->user()->unreadNotifications;
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }
}
