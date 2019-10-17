<?php
namespace Tests\Unit\Http\Presenters;

use App\Entities\Field;
use App\Entities\FieldType;
use App\Http\Presenters\JsonPresenter;
use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;

class JsonPresenterTest extends TestCase
{
    public function testPresentItem()
    {
        $faker = FakerFactory::create();

        $fieldType = new FieldType();
        $fieldType->setId($faker->uuid);
        $fieldType->setName($faker->word);
        $fieldType->setInputType($faker->word);
        $fieldType->setValidators($faker->word);

        $field = new Field();
        $field->setVariable($faker->word);
        $field->setId($faker->uuid);
        $field->setRequired($faker->boolean);
        $field->setTitle($faker->words(4, true));
        $field->setFieldType($fieldType);

        $expectedResult = [
            'data' => $field->present(),
        ];

        $presenter = new JsonPresenter();

        $presenter->present($field);

        $this->expectOutputString(json_encode($expectedResult));
    }

    public function testPresentCollection()
    {
        $faker = FakerFactory::create();

        $fieldType = new FieldType();
        $fieldType->setId($faker->uuid);
        $fieldType->setName($faker->word);
        $fieldType->setInputType($faker->word);
        $fieldType->setValidators($faker->word);

        $fields = [];
        for ($i = 0; $i < 10; $i++) {
            $field = new Field();
            $field->setVariable($faker->word);
            $field->setId($faker->uuid);
            $field->setRequired($faker->boolean);
            $field->setTitle($faker->words(4, true));
            $field->setFieldType($fieldType);

            $fields[] = $field;
        }

        $expectedResult = [
            'data' => array_map(function (Field $field) {
                return $field->present();
            }, $fields),
        ];

        $presenter = new JsonPresenter();
        $presenter->present($fields);

        $this->expectOutputString(json_encode($expectedResult));
    }
}
