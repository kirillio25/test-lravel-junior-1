<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class   CreateItemsTable extends Migration
{

    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status'); // Allowed / Prohibited
            $table->timestamps();
        });

        DB::statement("ALTER TABLE items ADD CONSTRAINT status_check CHECK (status IN ('Allowed', 'Prohibited'))");
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
