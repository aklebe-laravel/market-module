<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable()->comment('payment name');
                $table->string('code', 255)->nullable()->comment('payment code');
                $table->string('description', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shipping_methods')) {
            Schema::create('shipping_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable()->comment('shipping name');
                $table->string('code', 255)->nullable()->comment('shipping code');
                $table->string('description', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_enabled')->default(true)->comment('True when enabled and listed');
                $table->boolean('is_public')->default(true)->comment('Only public will be listed');
                //            $table->unsignedBigInteger('parent_id')->nullable()->index();
                //            $table->foreign('parent_id')->references('id')->on($table->getTable())->cascadeOnUpdate()->cascadeOnDelete();
                $table->unsignedBigInteger('store_id')->nullable()->index();
                $table->foreign('store_id')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
                $table->string('code', 255)->nullable()->unique()->index();
                $table->string('name', 255)->nullable();
                $table->mediumText('description')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('web_uri', 255)->unique()->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('category_parent')) {
            Schema::create('category_parent', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->unsigned();
                $table->unsignedBigInteger('parent_id')->unsigned();

                $table->unique(['category_id', 'parent_id']);
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
            });
        }


        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_enabled')->default(true)->comment('True when enabled and listed');
                $table->boolean('is_public')->default(true)->comment('Only public will be listed');
                $table->boolean('is_locked')->default(false)->comment('Closed by system');
                $table->boolean('force_public')
                    ->default(false)
                    ->comment('In private stores this can be linked public for everyone');
                $table->boolean('is_custom')
                    ->default(true)
                    ->comment('Marked as individual item (in jumble sales or smth)');
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on($table->getTable())
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->unsignedBigInteger('store_id')->nullable()->index();
                $table->foreign('store_id')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
                $table->string('product_type', 100)->nullable();
                $table->string('system_status', 100)
                    ->nullable()
                    ->comment('Status for future used. Temporary locked or something.');
                $table->string('name', 255)->nullable();
                $table->unsignedBigInteger('payment_method_id')->nullable()->unsigned();
                $table->foreign('payment_method_id')
                    ->references('id')
                    ->on('payment_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('shipping_method_id')->nullable()->unsigned();
                $table->foreign('shipping_method_id')
                    ->references('id')
                    ->on('shipping_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->string('short_description', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('meta_description')->nullable();
                $table->string('web_uri', 255)->unique()->nullable();
                $table->timestamp('started_at')->nullable()->comment('null = always available');
                $table->timestamp('expired_at')->nullable()->comment('null = open end');
                $table->timestamps();
            });
        }

        // relation table: alphabetical order, singular, underline seperated
        if (!Schema::hasTable('category_product')) {
            Schema::create('category_product', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->unsigned();
                $table->unsignedBigInteger('product_id')->unsigned();

                $table->unique(['category_id', 'product_id']);
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
            });
        }

        // relation table: alphabetical order, singular, underline seperated
        if (!Schema::hasTable('media_item_product')) {
            Schema::create('media_item_product', function (Blueprint $table) {
                $table->unsignedBigInteger('media_item_id')->unsigned();
                $table->unsignedBigInteger('product_id')->unsigned();
                $table->string('content_code', 255)->nullable()->comment('like MAKER for first product images');

                $table->unique(['media_item_id', 'product_id']);
                $table->foreign('media_item_id')
                    ->references('id')
                    ->on('media_items')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
            });
        }

        // relation table: alphabetical order, singular, underline seperated
        if (!Schema::hasTable('category_media_item')) {
            Schema::create('category_media_item', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->unsigned();
                $table->unsignedBigInteger('media_item_id')->unsigned();
                $table->string('content_code', 255)->nullable()->comment('like MAKER for first product images');

                $table->unique(['media_item_id', 'category_id']);
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('media_item_id')
                    ->references('id')
                    ->on('media_items')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shopping_carts')) {
            Schema::create('shopping_carts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->unsignedBigInteger('store_id')->unsigned();
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
                $table->string('session_token', 255)->unique()->nullable()->comment('session token');
                $table->string('shared_id', 255)->unique()->nullable()->comment('share token');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shopping_cart_items')) {
            Schema::create('shopping_cart_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shopping_cart_id')->unsigned();
                $table->foreign('shopping_cart_id')
                    ->references('id')
                    ->on('shopping_carts')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('product_id')->unsigned();
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('payment_method_id')->nullable()->unsigned();
                $table->foreign('payment_method_id')
                    ->references('id')
                    ->on('payment_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('shipping_method_id')->nullable()->unsigned();
                $table->foreign('shipping_method_id')
                    ->references('id')
                    ->on('shipping_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->string('product_name', 100)->nullable()->comment('product name');
                $table->double('price')->default(0)->comment('price');
                $table->string('currency_code', 3)->nullable()->comment('currency code');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('prev_offer_id')
                    ->nullable()
                    ->unsigned()
                    ->comment('If given its the previous offer and this one is the changed one');
                $table->foreign('prev_offer_id')
                    ->references('id')
                    ->on('offers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('created_by_user_id')->nullable()->unsigned();
                $table->foreign('created_by_user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('addressed_to_user_id')->unsigned();
                $table->foreign('addressed_to_user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('address_id')->nullable()->unsigned();
                $table->foreign('address_id')
                    ->references('id')
                    ->on('addresses')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('store_id')->unsigned();
                $table->string('status', 100)
                    ->default(\Modules\Market\app\Models\Offer::STATUS_APPLIED)
                    ->comment('Status of this offer like APPLIED, NEGOTIATION, REJECTED, CLOSED');
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
                $table->string('session_token', 255)->nullable()->comment('session token');
                $table->string('shared_id', 255)->unique()->nullable()->comment('share token');
                $table->text('description')->nullable();
                $table->timestamp('expired_at')->nullable()->comment('null = open end');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('offer_items')) {
            Schema::create('offer_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('offer_id')->unsigned();
                $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade')->onUpdate('cascade');
                $table->unsignedBigInteger('product_id')->unsigned();
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('payment_method_id')->nullable()->unsigned();
                $table->foreign('payment_method_id')
                    ->references('id')
                    ->on('payment_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->unsignedBigInteger('shipping_method_id')->nullable()->unsigned();
                $table->foreign('shipping_method_id')
                    ->references('id')
                    ->on('shipping_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->string('product_name', 100)->nullable()->comment('product name');
                $table->double('price')->default(0)->comment('price');
                $table->string('currency_code', 3)->nullable()->comment('currency code');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->unsigned()->comment('Rating creator');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->string('model', 255)->nullable()->comment('Model incl namespace');
                $table->unsignedBigInteger('model_id')->comment('product id, user id, ...');
                $table->string('model_sub_code', 255)
                    ->nullable()
                    ->comment('Model specific like: public_rating, condition, ...');
                $table->float('value')->default(1)->comment('rating value 0..100 (for stars 1-5: 20,40,60,80,100)');
                $table->string('description', 255)->nullable()->comment('Rating description');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('aggregated_ratings')) {
            Schema::create('aggregated_ratings', function (Blueprint $table) {
                $table->id();
                $table->string('model', 255)->nullable()->comment('Model incl namespace');
                $table->unsignedBigInteger('model_id')->comment('product id, user id, ...');
                $table->string('model_sub_code', 255)
                    ->nullable()
                    ->comment('Model specific like: public_rating, condition, ...');
                $table->float('value')->default(1)->comment('rating value 0..100 (for stars 1-5: 20,40,60,80,100)');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregated_ratings');
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('offer_items');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('shopping_cart_items');
        Schema::dropIfExists('shopping_carts');
        Schema::dropIfExists('category_media_item');
        Schema::dropIfExists('media_item_product');
        //        Schema::dropIfExists('object_properties');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('category_parent');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('payment_methods');
    }

};
