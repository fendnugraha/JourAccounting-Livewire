<?php

namespace App\Livewire\Settings\Contact;

use App\Models\Contact;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ContactTable extends Component
{
    use WithPagination;

    public $search = '';

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $transactionsExist = $contact->transactions()->exists();
        $financesExist = $contact->finances()->exists();

        if ($transactionsExist || $financesExist || $contact->id === 1) {
            return;
        }

        $contact->delete();

        $this->dispatch('contact-deleted', $id);
    }

    #[On(['contact-created', 'contact-deleted', 'contact-updated'])]
    public function render()
    {
        return view('livewire.settings.contact.contact-table', [
            'contacts' => Contact::when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name')->paginate(10)->onEachSide(0)
        ]);
    }
}
