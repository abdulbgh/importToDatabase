<?php

namespace App\Rules;

use Closure;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueNestedEmail implements Rule
{
    public function passes($attribute, $value)
    {
        // Decode the JSON object
        $data = json_decode($value, true);

        // Check if the email field exists within the JSON object
        if (isset($data['email'])) {
            // Check if the email is unique within the database
            return !DB::table('buffers')->whereJsonContains('meta_data->email', $data['email'])->exists();
        }

        // If the email field is not present or empty, return true
        return true;
    }

    public function message()
    {
        return 'The email has already been taken.';
    }
}
