<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    const SIZE_2MB = 2097152;

    public function __construct(private int $maxSize)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail("The :attribute must be a base64 image.");
        }

        // Check if the value is a valid base64 string
        if (!preg_match('/^data:image\/(\w+);base64,/', $value, $type)) {
            $fail("The :attribute must be a base64 encoded image.");
        }

        $data = substr($value, strpos($value, ',') + 1);
        $data = base64_decode($data, true);

        if ($data === false) {
            $fail("The :attribute must be a base64 encoded image.");
        }

        // Check the image type
        $type = strtolower($type[1]);

        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            $fail("The :attribute must be a base64 encoded image.");
        }

        $size = (int) (strlen($value) * 3 / 4);

        if ($size > $this->maxSize) {
            $fail("The :attribute may not be greater than " . $this->maxSize / (1024 * 1024)  . " MB.");
        }
    }
}
