<?php

namespace App\Livewire\Settings\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\session;

class UserTable extends Component
{
    public string $search = '';

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $journalExists = $user->journals()->exists();
        $transactionExists = $user->transactions()->exists();

        if ($journalExists || $transactionExists || $user->id == 1) {

            return;
        }


        DB::beginTransaction();
        try {
            $user->role()->delete();
            $user->delete();
            DB::commit();

            $this->dispatch('user-deleted', [
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Flash an error message
            Log::error($e->getMessage());
        }
    }
    public function render()
    {
        $users = User::with(['roles'])->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name', 'asc')->paginate(10)->onEachSide(0);
        return view('livewire.settings.user.user-table', [
            'users' => $users
        ]);
    }
}
