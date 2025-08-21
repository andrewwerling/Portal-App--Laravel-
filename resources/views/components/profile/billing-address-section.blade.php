@props(['billing_address'])

<div class="grid gap-6 w-full">
    <div>
        <x-input-label for="billing_address_street" :value="__('Street')" />
        <x-text-input wire:model="billing_address.street" id="billing_address_street" name="billing_address.street" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('billing_address.street')" />
    </div>
    <div>
        <x-input-label for="billing_address_city" :value="__('City')" />
        <x-text-input wire:model="billing_address.city" id="billing_address_city" name="billing_address.city" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('billing_address.city')" />
    </div>
    <div>
        <x-input-label for="billing_address_state" :value="__('State')" />
        <select wire:model="billing_address.state" id="billing_address_state" name="billing_address.state" class="p-2.5 mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 dark:bg-zinc-700 dark:text-gray-300">
            <option value="">Select...</option>
            <option value="AL">AL - Alabama</option>
            <option value="AK">AK - Alaska</option>
            <option value="AZ">AZ - Arizona</option>
            <option value="AR">AR - Arkansas</option>
            <option value="CA">CA - California</option>
            <option value="CO">CO - Colorado</option>
            <option value="CT">CT - Connecticut</option>
            <option value="DE">DE - Delaware</option>
            <option value="FL">FL - Florida</option>
            <option value="GA">GA - Georgia</option>
            <option value="HI">HI - Hawaii</option>
            <option value="ID">ID - Idaho</option>
            <option value="IL">IL - Illinois</option>
            <option value="IN">IN - Indiana</option>
            <option value="IA">IA - Iowa</option>
            <option value="KS">KS - Kansas</option>
            <option value="KY">KY - Kentucky</option>
            <option value="LA">LA - Louisiana</option>
            <option value="ME">ME - Maine</option>
            <option value="MD">MD - Maryland</option>
            <option value="MA">MA - Massachusetts</option>
            <option value="MI">MI - Michigan</option>
            <option value="MN">MN - Minnesota</option>
            <option value="MS">MS - Mississippi</option>
            <option value="MO">MO - Missouri</option>
            <option value="MT">MT - Montana</option>
            <option value="NE">NE - Nebraska</option>
            <option value="NV">NV - Nevada</option>
            <option value="NH">NH - New Hampshire</option>
            <option value="NJ">NJ - New Jersey</option>
            <option value="NM">NM - New Mexico</option>
            <option value="NY">NY - New York</option>
            <option value="NC">NC - North Carolina</option>
            <option value="ND">ND - North Dakota</option>
            <option value="OH">OH - Ohio</option>
            <option value="OK">OK - Oklahoma</option>
            <option value="OR">OR - Oregon</option>
            <option value="PA">PA - Pennsylvania</option>
            <option value="RI">RI - Rhode Island</option>
            <option value="SC">SC - South Carolina</option>
            <option value="SD">SD - South Dakota</option>
            <option value="TN">TN - Tennessee</option>
            <option value="TX">TX - Texas</option>
            <option value="UT">UT - Utah</option>
            <option value="VT">VT - Vermont</option>
            <option value="VA">VA - Virginia</option>
            <option value="WA">WA - Washington</option>
            <option value="WV">WV - West Virginia</option>
            <option value="WI">WI - Wisconsin</option>
            <option value="WY">WY - Wyoming</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('billing_address.state')" />
    </div>
    <div>
        <x-input-label for="billing_address_postal_code" :value="__('Postal Code')" />
        <x-text-input wire:model="billing_address.postal_code" id="billing_address_postal_code" name="billing_address.postal_code" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('billing_address.postal_code')" />
    </div>
    <div>
        <x-input-label for="billing_address_country" :value="__('Country')" />
        <select wire:model="billing_address.country" id="billing_address_country" name="billing_address.country" class="p-2.5 mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 dark:bg-zinc-700 dark:text-gray-300">
            <option value="">Select...</option>
            <option value="US">US - United States</option>
            <option value="CA">CA - Canada</option>
            <option value="GB">GB - United Kingdom</option>
            <option value="AU">AU - Australia</option>
            <option value="DE">DE - Germany</option>
            <option value="FR">FR - France</option>
            <option value="IT">IT - Italy</option>
            <option value="ES">ES - Spain</option>
            <option value="JP">JP - Japan</option>
            <option value="CN">CN - China</option>
            <option value="IN">IN - India</option>
            <option value="BR">BR - Brazil</option>
            <option value="MX">MX - Mexico</option>
            <option value="RU">RU - Russia</option>
            <option value="ZA">ZA - South Africa</option>
            <option value="AR">AR - Argentina</option>
            <option value="CH">CH - Switzerland</option>
            <option value="EG">EG - Egypt</option>
            <option value="ID">ID - Indonesia</option>
            <option value="KR">KR - South Korea</option>
            <option value="NG">NG - Nigeria</option>
            <option value="NL">NL - Netherlands</option>
            <option value="NZ">NZ - New Zealand</option>
            <option value="PH">PH - Philippines</option>
            <option value="PK">PK - Pakistan</option>
            <option value="SA">SA - Saudi Arabia</option>
            <option value="SG">SG - Singapore</option>
            <option value="TH">TH - Thailand</option>
            <option value="TR">TR - Turkey</option>
            <option value="VN">VN - Vietnam</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('billing_address.country')" />
    </div>
</div> 