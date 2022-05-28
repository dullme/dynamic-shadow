<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_no');
            $table->integer('line_no');
            $table->string('sell_to_customer_no')->nullable();
            $table->integer('type');
            $table->string('no')->nullable();
            $table->string('location_code')->nullable();
            $table->string('description')->nullable();
            $table->string('description_2')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->decimal('quantity',38,20)->nullable();
            $table->decimal('unit_price',38,20)->nullable();
            $table->decimal('amount',38,20)->nullable();
            $table->decimal('quantity_shipped',38,20)->nullable();
            $table->decimal('quantity_invoiced',38,20)->nullable();
            $table->decimal('line_amount',38,20)->nullable();
            $table->string('variant_code')->nullable();
            $table->string('unit_of_measure_code')->nullable();
            $table->string('item_category_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_lines');
    }
}
