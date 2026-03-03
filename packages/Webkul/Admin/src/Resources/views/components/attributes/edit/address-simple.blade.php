@if (isset($attribute))
    <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="textarea"
            name="{{ $attribute->code }}[address]"
            value="{{ old($attribute->code.'.address', is_array($value ?? []) ? ($value['address'] ?? '') : '') }}"
            :label="trans('admin::app.common.custom-attributes.address')"
            :rules="$validations"
            rows="3"
        />
        <x-admin::form.control-group.error :control-name="$attribute->code . '.address'" />
    </x-admin::form.control-group>
    {{-- Hidden fields to satisfy storage format when only address is used --}}
    <input type="hidden" name="{{ $attribute->code }}[country]" value="">
    <input type="hidden" name="{{ $attribute->code }}[state]" value="">
    <input type="hidden" name="{{ $attribute->code }}[city]" value="">
    <input type="hidden" name="{{ $attribute->code }}[postcode]" value="">
@endif
