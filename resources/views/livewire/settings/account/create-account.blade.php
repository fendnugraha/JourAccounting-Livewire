<form>
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
            autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
</form>