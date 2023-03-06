<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->integer('responsible_user_id');
            $table->integer('group_id');
            $table->integer('status_id');
            $table->integer('pipeline_id');
            $table->integer('loss_reason_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('closed_at')->nullable();
            $table->integer('closest_task_at')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->json('custom_fields_values')->nullable();
            $table->json('tags')->nullable();
            $table->json('contacts')->nullable();
            $table->json('company')->nullable();
            $table->integer('score')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('labor_cost')->nullable();
            $table->boolean('is_price_modified_by_robot');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
};