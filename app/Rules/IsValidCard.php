<?php

namespace App\Rules;

use App\Helpers\LuhnChecker;
use Illuminate\Contracts\Validation\Rule;

class IsValidCard implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return LuhnChecker::check($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The card number does not belong to a valid card';
    }
}
