<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Factory\PagerfantaPaginatorFactory;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use PHPUnit\Framework\TestCase;

class PagerfantaPaginatorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPaginator(): void
    {
        $rows = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
        $countRows = count($rows);

        $paginator = (new PagerfantaPaginatorFactory(new PaginatorAdapter()))->create($rows, $countRows, 3);

        $this->assertTrue(method_exists($paginator, 'getCurrentPageResults'));
        $this->assertTrue(method_exists($paginator, 'getHtml'));
    }
}