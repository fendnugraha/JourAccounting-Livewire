<?php

namespace App\Livewire\Setting\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;


class CreateUser extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $warehouse_id = 1;
    public $role = 'Kasir';


    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Role::create([
            'user_id' => $user->id,
            'warehouse_id' => $this->warehouse_id,
            'role' => $this->role
        ]);

        $this->dispatch('UserCreated', $user);

        $this->reset('name', 'email', 'password', 'password_confirmation');

        session()->flash('success', 'User Created Successfully');
    }

    public function render()
    {
        return view('livewire.setting.user.create-user', [
            'warehouses' => \App\Models\Warehouse::all(),

        ]);
    }
}
