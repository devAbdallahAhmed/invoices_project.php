<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoice_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{
    public function index($id)
    {
        $userUnreadNotification= Invoice::find($id)->unreadNotifications;
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }

    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {



    }


    public function show(Invoice_details $invoice_details)
    {
        //
    }


    public function edit($id)
    {

        $invoices = Invoice::where('id',$id)->first();
        $details  = Invoice_details::where('id_Invoice',$id)->get();
        $attachments  = Invoice_attachments::where('invoice_id',$id)->get();

        return view('invoices.invoice_details',compact('invoices','details','attachments'));
    }


    public function update(Request $request, Invoice_details $invoice_details)
    {
        //
    }

    public function destroy(Request $request)
    {
        $invoice = Invoice_attachments::findorfail($request->id_file);
        $invoice->delete();
        Storage::disk('public_upload')->delete($request->invoice_number."/".$request->file_name);
        session()->flash('delete','تم حذف المرفق بنجاح');
        return  back();
    }


    public function downloadFile($invoiceNumber, $fileName)
    {

        $filePath = $invoiceNumber . '/' . $fileName;

        if (!Storage::disk('public_upload')->exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // تنزيل الملف
        return Storage::disk('public_upload')->download($filePath);
    }


    public function open_file($invoiceNumber, $fileName)
    {
        $filePath = $invoiceNumber . '/' . $fileName;
        if (!Storage::disk('public_upload')->exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }
         $contant = Storage::disk('public_upload')->path($filePath);
        return response()->file($contant);
    }

}
