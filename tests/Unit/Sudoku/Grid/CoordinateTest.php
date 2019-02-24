<?php

declare(strict_types=1);

namespace Tests\Unit\Sudoku\Grid;

use Sudoku\Grid\Coordinate;
use Tests\TestCase;

class CoordinateTest extends TestCase
{
    public function testCoordinateObject() : void
    {
        $coordinate = new Coordinate(15, 22);

        $this->assertSame(15, $coordinate->getRow());
        $this->assertSame(22, $coordinate->getColumn());
    }
}
