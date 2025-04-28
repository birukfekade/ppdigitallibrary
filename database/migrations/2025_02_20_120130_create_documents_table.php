<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('user_id')->constrained()->onDelete('cascade');
            $table->string('access_level_id')->constrained()->onDelete('cascade');
            $table->string('department_id')->constrained()->onDelete('cascade');
            $table->string('city_id')->constrained()->onDelete('cascade');
            $table->string('subcity_id')->constrained('sub_cities')->onDelete('cascade');
            $table->timestamp('upload_date');
            $table->string('status')->default('active');
            $table->boolean('is_public')->default(false);
            $table->foreignId('document_category_id')->nullable()->constrained()->after('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['document_category_id']);
            $table->dropColumn('document_category_id');
        });
    }
};
