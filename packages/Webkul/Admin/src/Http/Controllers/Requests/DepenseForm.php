<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepenseForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'date'           => 'required|date',
            'category'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'montant'        => 'required|numeric|min:0',
            'note'           => 'nullable|string',
            'mode_paiement'  => 'required|string|max:255',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'date'          => trans('admin::app.depenses.create.date'),
            'category'      => trans('admin::app.depenses.create.category'),
            'montant'       => trans('admin::app.depenses.create.montant'),
            'mode_paiement' => trans('admin::app.depenses.create.mode-paiement'),
        ];
    }
}