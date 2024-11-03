<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->text('msg'); // Message content
            $table->string('sender_name'); // Sender's name
            $table->string('recipient_number'); // Sender's name
            $table->boolean('seen')->default(false); // Seen status, default to false
            $table->softDeletes(); // Adds deleted_at column for soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
};