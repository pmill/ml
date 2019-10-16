<?php
namespace App\ValidationRules;

use Rakit\Validation\Rule;

class UuidV4ValidationRule extends Rule
{
    const UUID_VALIDATION_REGEX = '~^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i';

    /**
     * @inheritDoc
     */
    public function check($value): bool
    {
        if (!is_string($value) ||
            preg_match(self::UUID_VALIDATION_REGEX, $value) === 0) {
            return false;
        }

        return true;
    }
}
