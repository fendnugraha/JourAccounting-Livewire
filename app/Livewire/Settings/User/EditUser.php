<?php

namespace App\Livewire\Settings\User;

use App\Models\User;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;

class EditUser extends Component
{
    public $user_id;
    public string $name = '';
    public string $email = '';
    public string $role = 'Kasir';
    public $warehouse_id;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'role' => 'required',
        'warehouse_id' => 'required',
    ];

    #[On('user-changed')]
    public function updatedUserId($id)
    {
        $this->user_id = $id;

        $user = User::find($this->user_id);
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->role = $user->roles->role ?? 'Kasir';
        $this->warehouse_id = $user->roles->warehouse_id ?? null;
    }

    public function editUser()
    {
        $this->validate();
        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();
        $user->roles()->update([
            'role' => $this->role,
            'warehouse_id' => $this->warehouse_id
        ]);
        session()->flash('success', 'User updated successfully');
        $this->dispatch('user-updated', [
            'message' => 'User updated successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.settings.user.edit-user', [
            'warehouses' => Warehouse::orderBy('name', 'asc')->get()
        ]);
    }
}
