@props([
    'attribute'   => '',
    'value'       => '',
    'validations' => '',
])

@switch($attribute->type)
    @case('text')
        <x-admin::attributes.edit.text
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('email')
        <x-admin::attributes.edit.email
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('phone')
        <x-admin::attributes.edit.phone
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('lookup')
        <x-admin::attributes.edit.lookup
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
            can-add-new="true"
        />

        @break

    @case('select')
        <x-admin::attributes.edit.select
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break
    
    @case('multiselect')
        <x-admin::attributes.edit.multiselect
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />
        
        @break

    @case('price')
        <x-admin::attributes.edit.price
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('image')
        <x-admin::attributes.edit.image
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('file')
        <x-admin::attributes.edit.file
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('textarea')
        <x-admin::attributes.edit.textarea
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('address')
        @if (in_array($attribute->entity_type, ['quotes']) && in_array($attribute->code, ['billing_address', 'shipping_address']))
            <x-admin::attributes.edit.address-simple
                :attribute="$attribute"
                :value="$value"
                :validations="$validations"
            />
        @else
            <x-admin::attributes.edit.address
                :attribute="$attribute"
                :value="$value"
                :validations="$validations"
            />
        @endif

        @break

    @case('date')
        <x-admin::attributes.edit.date
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('datetime')
        <x-admin::attributes.edit.datetime
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('boolean')
        <x-admin::attributes.edit.boolean
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break

    @case('checkbox')
        <x-admin::attributes.edit.checkbox
            :attribute="$attribute"
            :value="$value"
            :validations="$validations"
        />

        @break
@endswitch