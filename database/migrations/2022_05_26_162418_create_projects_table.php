<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->default('')->comment('项目编号');
            $table->string('search_description')->nullable()->default('');
            $table->string('description')->default('');
            $table->string('description_2')->nullable();
            $table->string('bill_to_customer_no')->nullable();
            $table->integer('status');
            $table->string('bill_to_name')->nullable();
            $table->string('bill_to_address')->nullable();
            $table->string('bill_to_address_2')->nullable();
            $table->string('bill_to_city')->nullable();
            $table->string('bill_to_post_code')->nullable();
            $table->string('bill_to_country_region_code')->nullable();
            $table->string('bill_to_contact_no')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
