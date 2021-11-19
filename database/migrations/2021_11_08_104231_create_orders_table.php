<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('receipt_number')->unsigned()->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->foreign('cashier_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->double('total_received_khr', 15, 8)->nullable()->default(0);
            $table->double('total_received_usd', 15, 8)->nullable()->default(0);

            $table->float('amount_pay_price_khr', 15, 2)->default(0);
            $table->double('amount_pay_price_usd', 15, 8)->default(0);

            $table->string('table')->default(0);
            $table->dateTime('ordered_at')->nullable();
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
