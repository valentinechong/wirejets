<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TwoFactorAuthenticationForm;
use Livewire\Livewire;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_enable_two_factor_authentication_with_livewire(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->withSession(['auth.password_confirmed_at' => time()]);

        Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication')
            ->assertHasNoErrors();

        $this->assertNotNull($user->fresh()->two_factor_secret);
        $this->assertCount(8, $user->fresh()->recoveryCodes());
    }
}
