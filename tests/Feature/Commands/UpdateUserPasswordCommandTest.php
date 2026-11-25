<?php

namespace Tests\Feature\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;
use Tests\TestCase;

class UpdateUserPasswordCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_cannot_be_updated_when_fortify_disables_password_updates(): void
    {
        $user = User::factory()->create();
        config( ['fortify.features' => [Features::registration()]] );

        $this->artisan(
            'user:update-password',
            [
            'email' => $user->email,
            '--force' => true,
            ]
        )
            ->expectsOutput( 'Password updates are disabled in the Fortify configuration.' )
            ->assertExitCode( 1 );

        $this->assertTrue( Hash::check( 'password', $user->fresh()->password ) );
    }

    public function test_password_can_be_updated_when_it_satisfies_the_configured_rules(): void
    {
        $user = User::factory()->create();
        $password = 'A-valid-password-123!';

        $this->artisan( 'user:update-password', ['email' => $user->email] )
            ->expectsQuestion( 'New password', $password )
            ->expectsQuestion( 'Confirm new password', $password )
            ->expectsOutput( "Password updated for {$user->email}." )
            ->assertExitCode( 0 );

        $this->assertTrue( Hash::check( $password, $user->fresh()->password ) );
    }

    public function test_force_can_update_the_password_without_password_rules_or_confirmation(): void
    {
        $user = User::factory()->create();
        $password = 'x';

        $this->artisan(
            'user:update-password',
            [
            'email' => $user->email,
            '--force' => true,
            ]
        )
            ->expectsQuestion( 'New password', $password )
            ->expectsOutput( "Password updated for {$user->email}." )
            ->assertExitCode( 0 );

        $this->assertTrue( Hash::check( $password, $user->fresh()->password ) );
    }

    public function test_password_rules_are_enforced_without_force(): void
    {
        $user = User::factory()->create();

        $this->artisan( 'user:update-password', ['email' => $user->email] )
            ->expectsQuestion( 'New password', 'short' )
            ->expectsQuestion( 'Confirm new password', 'short' )
            ->assertExitCode( 1 );

        $this->assertTrue( Hash::check( 'password', $user->fresh()->password ) );
    }
}
