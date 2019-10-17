<?php
declare(strict_types=1);

namespace App\Builders;

use App\Entities\Field;
use App\Entities\Subscriber;
use App\Entities\SubscriberField;
use App\Repositories\FieldRepository;
use Exception;
use pmill\Doctrine\Hydrator\ArrayHydrator;

/**
 * There's a little bit more involved in creating subscribers and that shouldn't be handled in the controllers
 */
class SubscriberBuilder
{
    /**
     * @var ArrayHydrator
     */
    protected $arrayHydrator;

    /**
     * @var FieldRepository
     */
    protected $fieldRepository;

    /**
     * SubscriberBuilder constructor.
     *
     * @param ArrayHydrator $arrayHydrator
     * @param FieldRepository $fieldRepository
     */
    public function __construct(ArrayHydrator $arrayHydrator, FieldRepository $fieldRepository)
    {
        $this->arrayHydrator = $arrayHydrator;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @param array $parameters
     *
     * @return Subscriber
     * @throws Exception
     */
    public function buildSubscriber(array $parameters): Subscriber
    {
        $fields = $parameters['fields'];
        unset($parameters['fields']);

        /** @var Subscriber $subscriber */
        $subscriber = $this->arrayHydrator->hydrate(Subscriber::class, $parameters);

        $fieldVariableMap = $this->getFieldVariableMap();

        foreach ($fields as $variable => $value) {
            if (!isset($fieldVariableMap[$variable])) {
                continue;
            }

            $field = $fieldVariableMap[$variable];

            $subscriberField = new SubscriberField();
            $subscriberField->setField($field);
            $subscriberField->setValue($value);

            $subscriber->addSubscriberField($subscriberField);
        }

        return $subscriber;
    }

    /**
     * @return Field[]
     */
    protected function getFieldVariableMap()
    {
        $result = [];

        /** @var Field[] $fields */
        $fields = $this->fieldRepository->findAll();
        foreach ($fields as $field) {
            $result[$field->getVariable()] = $field;
        }

        return $result;
    }
}
