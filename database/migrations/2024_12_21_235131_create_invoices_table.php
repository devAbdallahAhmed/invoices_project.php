<?php

use App\Models\Invoice_details;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50);
            $table->date('invoice_Date')->nullable();
            $table->date('Due_date')->nullable();
            $table->string('product', 50);
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->decimal('Amount_collection',10)->nullable();;
            $table->decimal('Amount_Commission',10);
            $table->decimal('Discount',10);
            $table->decimal('Value_VAT',10);
            $table->string('Rate_VAT', 255);
            $table->decimal('Total',10);
            $table->string('Status', 50);
            $table->integer('Value_Status');
            $table->text('note')->nullable();
            $table->date('Payment_Date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
