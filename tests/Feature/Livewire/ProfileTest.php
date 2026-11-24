<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_update_their_profile_information_with_livewire(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state.name', 'Updated Name')
            ->set('state.email', 'updated@example.com')
            ->call('updateProfileInformation')
            ->assertHasNoErrors()
            ->assertDispatched('saved');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }
}
