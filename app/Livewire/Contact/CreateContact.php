<?php

namespace App\Livewire\Contact;

use App\Models\Contact;
use Livewire\Component;

class CreateContact extends Component
{
    public $name;
    public $type;
    public $description;

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'type' => 'required',
            'description' => 'required'
        ]);

        $contact = Contact::create([
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description
        ]);

        $this->dispatch('TransferCreated', $contact->id);

        session()->flash('success', 'Contact created successfully');

        $this->reset();
    }
    public function render()
    {
        return view('livewire.contact.create-contact');
    }
}
