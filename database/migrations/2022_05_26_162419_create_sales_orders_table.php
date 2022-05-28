<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique();
            $table->string('sell_to_customer_no')->nullable();
            $table->string('sell_to_customer_name')->nullable();
            $table->string('sell_to_address')->nullable();
            $table->string('bill_to_customer_no')->nullable();
            $table->string('bill_to_name')->nullable();
            $table->string('bill_to_address')->nullable();
            $table->string('bill_to_city')->nullable();
            $table->string('bill_to_contact')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->string('ship_to_address')->nullable();
            $table->string('ship_to_city')->nullable();
            $table->string('ship_to_contact')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->dateTime('document_date')->nullable();
            $table->string('external_document_no')->nullable();
            $table->integer('status');
            $table->string('currency_code')->nullable();
            $table->decimal('currency_factor', 38, 20);
            $table->string('project_no')->nullable();
            $table->string('project_name')->nullable();
            $table->integer('shipped_status')->default(0)->comment('所有行是否全部发货完成');
            $table->integer('invoiced_status')->default(0)->comment('所有行是否全部已开发票');
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
        Schema::dropIfExists('sales_orders');
    }
}
