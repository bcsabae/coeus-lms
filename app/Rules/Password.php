<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
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
        $whyIsItFailing = [
            "[.{8,}]" => preg_match_all("[.{8,}]", $value),
            "{[0-9]}" => preg_match_all("{[0-9]}", $value),
            "{[A-Z]}" => preg_match_all("{[A-Z]}", $value)
        ];
        $isFailing = preg_match_all("[.{8,}]", $value) &&
            preg_match_all("{[0-9]}", $value) &&
            preg_match_all("{[A-Z]}", $value);
        //dd($whyIsItFailing,
        //"The new password is ". ($isFailing ? "not " : "") . "failing");

        return
        //at least 8 characters long
        preg_match_all("[.{8,}]", $value) &&
        //contains at least 1 number
        preg_match_all("{[0-9]}", $value) &&
        //contains capital letter
        preg_match_all("{[A-Z]}", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The password has to be at least 8 characters long, with at least 1 number and 1 uppercase letter';
    }
}
