<?php
namespace Tests\Unit\Builders;

use App\Builders\SubscriberBuilder;
use App\Entities\Field;
use App\Entities\Subscriber;
use App\Repositories\FieldRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use pmill\Doctrine\Hydrator\ArrayHydrator;

class SubscriberBuilderTest extends TestCase
{
    public function testBuildSuscriber()
    {
        $subscriber = new Subscriber();

        $arrayHydratorMock = Mockery::mock(ArrayHydrator::class);
        $arrayHydratorMock
            ->shouldReceive('hydrate')
            ->andReturn($subscriber);

        $emailField = new Field();
        $emailField->setVariable('email');

        $firstNameField = new Field();
        $firstNameField->setVariable('firstName');

        $fieldRepositoryMock = Mockery::mock(FieldRepository::class);
        $fieldRepositoryMock
            ->shouldReceive('findAll')
            ->andReturn([
                $emailField,
                $firstNameField
            ]);

        $parameters = [
            'fields' => [
                'email' => 'my@email.com',
                'firstName' => 'Peter',
            ],
        ];

        $subscriberBuilder = new SubscriberBuilder($arrayHydratorMock, $fieldRepositoryMock);
        $subscriber = $subscriberBuilder->buildSubscriber($parameters);

        $subscriberFields = $subscriber->getSubscriberFields();

        foreach ($subscriberFields as $subscriberField) {
            $this->assertEquals(
                $parameters['fields'][$subscriberField->getField()->getVariable()],
                $subscriberField->getValue()
            );
        }
    }
}
