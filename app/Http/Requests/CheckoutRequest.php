<?php

namespace App\Http\Requests;

use App\Http\Handlers\OrderHandler;
use App\Rules\IsValidCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Executes before the rules function
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'expiryDate' => str_replace(' ', '', $this->expiryDate),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name' => 'required', // To full validate this we should also check if its 2 words
            'client_address' => 'required', // To fully validate this we should use Geolocate API with a custom validator
            'cvc' => 'required|numeric|min:100|max:999', // hack so we can make sure cvc is 3 characters !
            'cardNumber' => ['required', new IsValidCard],
            'expiryDate' => ['required', 'date_format:m/y', 'after_or_equal:' . date('m/y')],
            'shipping_option' => ['required', Rule::in(OrderHandler::AVAILABLE_SHIPPING_OPTIONS)]

        ];
    }

}
