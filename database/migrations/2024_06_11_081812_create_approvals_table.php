<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_request_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['approvedByManager','approvedByFinance', 'rejected', 'transferred' ,'pending']);
            $table->text('reason')->nullable();
            $table->string('transfer_proof')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('item_request_id')->references('id')->on('item_requests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
