<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceMigrating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',255);
            $table->text('discription');
            $table->integer('price');
            $table->integer('stock');
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',40);
            $table->string('phone',16);
            $table->string('address',255);
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customers_id')->unsigned();
            $table->string('note',255)->nullable();
            $table->boolean('status')->default(false);
            $table->integer('total');
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('invoice_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('invoice_id')->unsigned();
            $table->string('price');
            $table->string('qty');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('customers','products','invoices','invoice_details');
    }
}
