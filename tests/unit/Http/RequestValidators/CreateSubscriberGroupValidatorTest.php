<?php
namespace Tests\Unit\Http\RequestValidators;

use App\Exceptions\HttpValidationException;
use App\Http\RequestValidators\CreateSubscriberGroupValidator;
use App\Routing\Request;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Validator;

class SubscriberBuilderTest extends TestCase
{
    /**
     * @dataProvider assertValidDataProvider
     */
    public function testAssertValid(array $bodyParams, array $queryParams, bool $shouldPass)
    {
        if (!$shouldPass) {
            $this->expectException(HttpValidationException::class);
        }

        $request = Request::create(
            'http://localhost/api/subscriber-group?' . http_build_query($queryParams),
            'POST',
            [],
            [],
            [],
            [],
            json_encode($bodyParams)
        );

        $validator = new Validator();

        $createSubscriberGroupValidator = new CreateSubscriberGroupValidator($validator);
        $createSubscriberGroupValidator->assertValid($request, []);

        if ($shouldPass) {
            $this->assertTrue(true);
        }
    }

    /**
     * @return array
     */
    public function assertValidDataProvider()
    {
        return [
            [
                ['name' => 'Group 1'],
                [],
                true,
            ],
            [
                ['name' => 'Group 1', 'unknown' => 1],
                [],
                false,
            ],
            [
                ['name' => ''],
                [],
                false,
            ],
            [
                [],
                [],
                false,
            ],
            [
                ['name' => 'Group 1'],
                ['unknown' => true],
                false,
            ],
        ];
    }
}
