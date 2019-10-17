<?php
declare(strict_types=1);

namespace App\Http\RequestValidators;

use App\Entities\Field;
use App\Repositories\FieldRepository;
use Rakit\Validation\Validator;

class CreateSubscriberValidator extends AbstractValidator
{
    /**
     * @var FieldRepository
     */
    protected $fieldRepository;

    /**
     * CreateSubscriberValidator constructor.
     *
     * @param Validator $validator
     * @param FieldRepository $fieldRepository
     */
    public function __construct(
        Validator $validator,
        FieldRepository $fieldRepository
    ) {
        parent::__construct($validator);

        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getValidationRules(array $requestParams): array
    {
        $validationRules = [
            'body.subscriberGroup' => 'required|uuidV4',
            'body.subscriberState' => 'required|uuidV4',
            'body.fields' => 'required|array',
        ];

        /** @var Field[] $fields */
        $fields = $this->fieldRepository->findAll();

        foreach ($fields as $field) {
            $key = 'body.fields.' . $field->getVariable();

            $validationRules[$key] = $field->getValidatorRuleString();
        }

        return $validationRules;
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedBodyParameters(): array
    {
        return [
            'subscriberGroup',
            'subscriberState',
            'fields',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedQueryStringParameters(): array
    {
        return [];
    }
}
