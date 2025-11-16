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

    #[On('contact-created')]
    public function render()
    {
        return view('livewire.settings.contact.contact-table', [
            'contacts' => Contact::when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name')->paginate(10)->onEachSide(0)
        ]);
    }
}
