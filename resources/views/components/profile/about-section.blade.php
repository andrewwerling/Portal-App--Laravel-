@props(['first_name', 'last_name', 'birthday', 'gender', 'occupation', 'bio'])

<div class="grid gap-6 h-full w-full">
    <div>
        <x-input-label for="first_name" :value="__('First Name')" />
        <x-text-input wire:model="first_name" id="first_name" name="first_name" type="text" class="mt-1 block w-full" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
    </div>
    <div>
        <x-input-label for="last_name" :value="__('Last Name')" />
        <x-text-input wire:model="last_name" id="last_name" name="last_name" type="text" class="mt-1 block w-full" required />
        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
    </div>
    <div>
        <x-input-label for="birthday" :value="__('Birthday')" />
        <x-text-input wire:model="birthday" id="birthday" name="birthday" type="date" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
    </div>
    <div>
        <x-input-label for="gender" :value="__('Gender')" />
        <select wire:model="gender" id="gender" name="gender" class="p-2.5 mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 dark:bg-zinc-700 dark:text-gray-300">
            <option value="">Select...</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="non-binary">Non-binary</option>
            <option value="other">Other</option>
            <option value="prefer-not-to-say">Prefer not to say</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
    </div>
    <div>
        <x-input-label for="occupation" :value="__('Occupation')" />
        <x-text-input wire:model="occupation" id="occupation" name="occupation" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
    </div>
    <div>
        <x-input-label for="bio" :value="__('Bio')" />
        <textarea wire:model="bio" id="bio" name="bio" class="p-2.5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 dark:bg-zinc-700 dark:text-gray-300"></textarea>
        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
    </div>
</div> 