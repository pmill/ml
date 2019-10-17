<?php
namespace App\Http\RequestValidators;

use App\Entities\SubscriberField;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\SubscriberFieldRepository;
use Rakit\Validation\Validator;

class UpdateSubscriberFieldValidator extends AbstractValidator
{
    /**
     * @var SubscriberFieldRepository
     */
    protected $subscriberFieldRepository;

    /**
     * CreateSubscriberValidator constructor.
     *
     * @param Validator $validator
     * @param SubscriberFieldRepository $subscriberFieldRepository
     */
    public function __construct(
        Validator $validator,
        SubscriberFieldRepository $subscriberFieldRepository
    ) {
        parent::__construct($validator);

        $this->subscriberFieldRepository = $subscriberFieldRepository;
    }

    /**
     * @inheritDoc
     *
     * @throws EntityNotFoundException
     */
    protected function getValidationRules(array $requestParams): array
    {
        $subscriberFieldId = $requestParams['id'];

        /** @var SubscriberField $subscriberField */
        $subscriberField = $this->subscriberFieldRepository->find($subscriberFieldId);
        if ($subscriberField === null) {
            throw new EntityNotFoundException();
        }

        $field = $subscriberField->getField();

        return [
            'body.value' => $field->getValidatorRuleString(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedBodyParameters(): array
    {
        return [
            'value',
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
