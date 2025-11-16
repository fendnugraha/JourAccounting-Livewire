<?php

namespace App\Livewire\Settings\Contact;

use App\Models\Contact;
use Livewire\Component;

class CreateContact extends Component
{
    public $name;
    public $address;
    public $type;
    public $description;

    protected $rules = [
        'name' => 'required|unique:contacts,name',
        'address' => 'required',
        'type' => 'required|in:Customer,Supplier,Employee',
    ];

    public function createContact()
    {
        $this->validate();
        $contact = Contact::create([
            'name' => $this->name,
            'type' => $this->type,
            'phone_number' => null,
            'address' => $this->address,
            'description' => $this->description ?? 'General Contact'
        ]);

        $this->dispatch('contact-created', $contact);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.settings.contact.create-contact');
    }
}
