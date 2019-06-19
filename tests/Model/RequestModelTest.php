<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\AbstractModel;
use App\Model\Contract\RequestModelInterface;
use App\Model\PageRequestModel;
use App\Model\ProductRequestModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class RequestModelTest extends TestCase
{
    /**
     * @covers \App\Model\AbstractRequestModel
     * @covers \App\Model\AbstractRequestModel::getType
     * @covers \App\Model\AbstractRequestModel::getUrl
     * @covers \App\Model\PageRequestModel
     * @covers \App\Model\ProductRequestModel
     * @dataProvider getData
     *
     * @param RequestModelInterface $model
     * @param string                $url
     * @param string                $type
     */
    public function testRequestModel(RequestModelInterface $model, string $url, string $type): void
    {
        $this->assertSame($model->getUrl(), $url);
        $this->assertSame($model->getType(), $type);
    }

    public function getData(): array
    {
        $url = 'test.com';

        return [
            [new PageRequestModel($url), $url, AbstractModel::TYPE_PAGE],
            [new ProductRequestModel($url), $url, AbstractModel::TYPE_PRODUCT],
        ];
    }
}
