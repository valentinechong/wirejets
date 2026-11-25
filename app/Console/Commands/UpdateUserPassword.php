<?php

namespace App\Console\Commands;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Features;

class UpdateUserPassword extends Command
{
    use PasswordValidationRules;

    protected $signature = 'user:update-password {email : The email address of the user} {--force : Skip the configured password validation rules}';

    protected $description = "Update a user's password when Fortify password updates are enabled";

    public function handle(): int
    {
        if (! Features::enabled( Features::updatePasswords() )) {
            $this->error( 'Password updates are disabled in the Fortify configuration.' );

            return self::FAILURE;
        }

        $user = User::query()->where( 'email', $this->argument( 'email' ) )->first();

        if (! $user) {
            $this->error( 'No user was found with that email address.' );

            return self::FAILURE;
        }

        $password = $this->secret( 'New password' );

        if ($password === null) {
            $this->error( 'No password was provided.' );

            return self::FAILURE;
        }

        if (! $this->option( 'force' )) {
            $input = [
                'password' => $password,
                'password_confirmation' => $this->secret( 'Confirm new password' ),
            ];

            $validator = Validator::make(
                $input,
                [
                'password' => $this->passwordRules(),
                ]
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $this->error( $message );
                }

                return self::FAILURE;
            }
        }

        $user->forceFill(
            [
            'password' => Hash::make( $password ),
            ]
        )->save();

        $this->info( "Password updated for {$user->email}." );

        return self::SUCCESS;
    }
}
