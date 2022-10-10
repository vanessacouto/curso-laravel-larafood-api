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
        Schema::create(
            'orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('tenant_id');
                $table->string('identify')->unique();
                $table->integer('client_id')->nullable(); // um pedido pode ser anonimo
                $table->integer('table_id')->nullable(); // um pedido pode ter sido feito online e nao na mesa
                $table->double('total', 10, 2);
                $table->text('comment')->nullable();
                $table->enum('status', ['open', 'done', 'rejected', 'working', 'canceled', 'delivering']);
                $table->timestamps();

                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
            }
        );

        Schema::create(
            'order_product', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id');
                $table->integer('qty'); // quantidade do produto
                $table->double('price', 10, 2);

                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
    }
}
