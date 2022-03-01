<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_status_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('user_id', 'fk_order_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('order_status_id', 'fk_order_order_status')
                ->references('id')
                ->on('order_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('payment_id', 'fk_order_payment')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('uuid', 36)->unique();
            $table->json('products');
            $table->json('address');
            $table->float('delivery_fee')->nullable();
            $table->float('amount');
            $table->timestamps();
            $table->timestamp('shipped_at')->nullable();
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
