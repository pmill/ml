<?php
namespace Tests\Unit\Http\Presenters;

use App\Entities\SubscriberField;
use App\Http\Presenters\JsonPresenter;
use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;

class JsonPresenterTest extends TestCase
{
    public function testPresentItem()
    {
        $gif = new SubscriberField('title', 'http://wwww.mygifs.com/image.gif');

        $expectedResult = [
            'data' => [
                'title' => $gif->getTitle(),
                'url' => $gif->getUrl(),
            ],
        ];

        $presenter = new JsonPresenter();

        $presenter->present($gif);

        $this->expectOutputString(json_encode($expectedResult));
    }

    public function testPresentCollection()
    {
        $faker = FakerFactory::create();

        $bananaGifs = [];
        for ($i = 0; $i < 10; $i++) {
            $bananaGifs[] = new SubscriberField(
                $faker->name,
                $faker->imageUrl()
            );
        }

        $expectedResult = [
            'data' => array_map(function (SubscriberField $gif) {
                return $gif->present();
            }, $bananaGifs),
        ];

        $presenter = new JsonPresenter();
        $presenter->present($bananaGifs);

        $this->expectOutputString(json_encode($expectedResult));
    }
}
