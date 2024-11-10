<?php

namespace App\Livewire\Setting\Contact;

use App\Models\Contact;
use Livewire\Component;
use Livewire\Attributes\Layout;

class EditContact extends Component
{
    public $contact_id;
    public $name;
    public $type;
    public $description;

    public function mount($contact_id)
    {
        $this->contact_id = $contact_id;
        $this->name = Contact::find($contact_id)->name;
        $this->type = Contact::find($contact_id)->type;
        $this->description = Contact::find($contact_id)->description;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|min:5|unique:contacts,name,' . $this->contact_id,
            'type' => 'required',
            'description' => 'required'
        ]);

        $contact = Contact::find($this->contact_id);

        $contact->update($this->all());

        session()->flash('success', 'Contact updated successfully');

        $this->dispatch('ContactUpdated', $contact->id);

        return redirect()->route('contact');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.contact.edit-contact', [
            'title' => 'Edit Contact',
            'contact' => Contact::find($this->contact_id),
        ]);
    }
}
