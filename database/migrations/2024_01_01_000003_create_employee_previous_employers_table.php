<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_previous_employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('hr_name');
            $table->string('hr_phone');
            $table->text('address_line1');
            $table->string('state');
            $table->string('city');
            $table->string('pincode');
            $table->decimal('monthly_salary', 10, 2);
            $table->string('designation');
            $table->string('duration_for_working');
            $table->string('salary_slip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_previous_employers');
    }
};
