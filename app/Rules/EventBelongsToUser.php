<?php

namespace App\Rules;

use App\Models\Event;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EventBelongsToUser implements ValidationRule
{
    public function __construct(private int $userId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $found = Event::query()->where('id', $value)->where('user_id', $this->userId)->count();

        if (!$found) {
            $fail('The event does not belong to the user.');
        }
    }
}
