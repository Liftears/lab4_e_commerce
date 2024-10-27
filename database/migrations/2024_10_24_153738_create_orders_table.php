<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 65, 2); 
            $table->string('payment_status');
            $table->string('shipping_status')->default('Pending'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

