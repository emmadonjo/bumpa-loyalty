<?php

use App\Domains\Store\Enums\PurchaseStatus;
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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('reference', 64)->unique()->nullable();
            $table->enum('status', PurchaseStatus::values())
                ->default(PurchaseStatus::PENDING)->index();
            $table->string('external_reference', 64)->nullable()->index();
            $table->string('provider', 64)->nullable()->index();
            $table->string('payment_method', 64)->nullable()->index();
            $table->decimal('fees', 8, 2)->default(0);
            $table->string('currency', 4)->default('NGN')
                ->index()->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchaes', function (Blueprint $table) {
            //
        });
    }
};
