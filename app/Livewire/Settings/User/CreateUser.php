<?php

namespace App\Livewire\Settings\User;

use App\Models\User;
use Livewire\Component;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'Kasir';
    public $warehouse_id;

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'role' => ['required', 'string', 'in:Kasir,Administrator']
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        $user->roles()->create([
            'role' => $this->role,
            'warehouse_id' => $this->warehouse_id
        ]);

        $this->dispatch('user-created', $user);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.settings.user.create-user', [
            'warehouses' => Warehouse::orderBy('name', 'asc')->get()
        ]);
    }
}
