<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('email');
            $table->string('pin', 4)->after('phone'); // Code PIN à 4 chiffres
            $table->boolean('phone_verified')->default(false)->after('pin');
            $table->timestamp('pin_created_at')->nullable()->after('phone_verified');
            $table->timestamp('last_login_at')->nullable()->after('pin_created_at');
            $table->integer('login_attempts')->default(0)->after('last_login_at');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'pin',
                'phone_verified',
                'pin_created_at',
                'last_login_at',
                'login_attempts',
                'locked_until'
            ]);
        });
    }
};
