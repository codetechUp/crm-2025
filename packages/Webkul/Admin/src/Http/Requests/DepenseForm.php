<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'date'           => ['required', 'date'],
            'category'       => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'montant'        => ['required', 'numeric', 'min:0.01'],
            'note'           => ['nullable', 'string'],
            'mode_paiement'  => ['required', 'string', 'max:255'],
        ];

        // Si vous voulez valider contre des catégories prédéfinies
        // $rules['category'] = ['required', Rule::in(array_keys(config('depense.categories', [])))];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'date.required' => 'La date est requise',
            'date.date' => 'La date doit être valide',
            'category.required' => 'La catégorie est requise',
            'category.max' => 'La catégorie ne doit pas dépasser 255 caractères',
            'category.in' => 'La catégorie sélectionnée est invalide',
            'montant.required' => 'Le montant est requis',
            'montant.numeric' => 'Le montant doit être un nombre',
            'montant.min' => 'Le montant doit être supérieur à 0',
            'mode_paiement.required' => 'Le mode de paiement est requis',
            'mode_paiement.max' => 'Le mode de paiement ne doit pas dépasser 255 caractères',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'date'           => 'Date',
            'category'       => 'Catégorie',
            'description'    => 'Description',
            'montant'        => 'Montant',
            'note'           => 'Note',
            'mode_paiement'  => 'Mode de paiement',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'montant' => (float) str_replace(',', '.', $this->montant),
        ]);
    }
}