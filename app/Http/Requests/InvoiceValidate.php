<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceValidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'invoice_number' => 'required|unique:invoices|max:255',
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'product' => 'required',
            'section_id' =>'required',
            'Amount_collection' =>'required',
            'Amount_Commission' => 'required',
            'Value_VAT' => 'required',
            'Rate_VAT' => 'required',
            'Total' => 'required',

        ];
    }

    public function messages()
    {
        return[
        'invoice_number.required'=>"يرجى ادخال البيانات ",
            'invoice_number.unique'=>"هذا الرقم مسجل مسبقا ",
            'invoice_number.max'=>"لقد وصلت الى الحد المسموح",

        ];
    }
}
