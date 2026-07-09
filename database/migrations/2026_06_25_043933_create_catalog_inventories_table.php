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
        Schema::create('catalog_inventories', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('external_id')->unique();
            $table->string('BIBID');
            $table->string('Title');
            $table->string('Author');
            $table->string('Edition');
            $table->string('Publisher');
            $table->string('PublishLocation');
            $table->string('PublishYear');
            $table->string('Subject');
            $table->text('PhysicalDescription');
            $table->string('ISBN');
            $table->string('CallNumber');
            $table->string('Languages');
            $table->string('DeweyNo');
            $table->boolean('IsOPAC');
            $table->string('CoverURL')->nullable();
			$table->integer('Quantity');
            $table->timestamps();
			$table->timestamp('synced_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_inventories');
    }
};
