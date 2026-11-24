<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admins = [
            [
                'email' => 'valentine@anytine.com',
                'name' => 'Valentine',
                'password' => 'password'
            ],
        ];

        foreach ($admins as $adminData) {
            User::updateOrCreate(
                ['email' => $adminData['email']],
                ['name' => $adminData['name'],
                'password' => bcrypt( $adminData['password'] )]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $emails = [
            'valentine@anytine.com',
        ];

        User::whereIn('email', $emails)->delete();
    }
};
