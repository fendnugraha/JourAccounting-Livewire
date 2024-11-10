<?php

namespace App\Livewire\Setting\Contact;

use App\Models\Contact;
use App\Models\Payable;
use Livewire\Component;
use App\Models\Receivable;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ContactTable extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $contact = Contact::find($id);
        $receivableExists = Receivable::where('contact_id', $id)->exists();
        $payableExists = Payable::where('contact_id', $id)->exists();
        if ($receivableExists || $payableExists) {
            session()->flash('error', 'Contact Cannot be Deleted!');
        } else {
            $contact->delete();
            session()->flash('success', 'Contact Deleted Successfully');
        }

        $this->dispatch('ContactDeleted', $contact->id);
    }

    #[On('ContactCreated', 'ContactDeleted')]
    #[Layout('layouts.app')]
    public function render()
    {
        $contacts = Contact::where('name', 'like', '%' . $this->search . '%')->paginate(10);
        return view('livewire.setting.contact.contact-table', [
            'title' => 'Contact',
            'contacts' => $contacts,
        ]);
    }
}
