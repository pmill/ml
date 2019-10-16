<?php
namespace App\Services;

use App\Entities\Field;
use App\Entities\FieldType;

class FieldRuleService
{
    /**
     * @var array[]
     */
    protected $fieldTypeIdRulesMap = [
        FieldType::ID_BOOLEAN => ['in:0,1'],
        FieldType::ID_DATE => ['date:Y-m-d'],
        FieldType::ID_NUMBER => ['alpha_num'],
        FieldType::ID_STRING => ['max:255'],
    ];

    /**
     * @var array[]
     */
    protected $fieldVariableRulesMap = [
        'name' => ['required'],
        'email' => ['required', 'email', 'emailHost'],
    ];

    /**
     * @param Field $field
     *
     * @return string[]
     */
    public function getFieldRules(Field $field): array
    {
        $rules = [];

        if (isset($this->fieldVariableRulesMap[$field->getVariable()])) {
            $rules = array_merge($rules, $this->fieldVariableRulesMap[$field->getVariable()]);
        }

        if (isset($this->fieldTypeIdRulesMap[$field->getFieldType()->getId()])) {
            $rules = $this->fieldTypeIdRulesMap[$field->getFieldType()->getId()];
        }

        return $rules;
    }
}
