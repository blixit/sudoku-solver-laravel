<?php

declare(strict_types=1);

namespace Tests\Unit\Sudoku\Grid;

use Sudoku\Grid\Grid;
use Sudoku\Grid\GridInterface;
use Sudoku\Grid\GridLoaderException;
use Tests\TestCase;

class GridTest extends TestCase
{
    public function testGridConstruction() : void
    {
        $grid = new Grid();

        $this->assertInstanceOf(GridInterface::class, $grid);
    }

    public function testGridConstructionFromString() : void
    {
        $grid = Grid::fromString(
            "2..1.5..3\n" .
            ".54...71.\n" .
            ".1.2.3.8.\n" .
            "6.28.73.4\n" .
            ".........\n" .
            "1.53.98.6\n" .
            ".2.7.1.6.\n" .
            ".81...24.\n" .
            "7..4.2..1"
        );

        $this->assertInstanceOf(GridInterface::class, $grid);
    }

    public function testGridConstructionFromBadString() : void
    {
        $this->expectException(GridLoaderException::class);

        // a line is missing in this grid
        $grid = Grid::fromString(
            "2..1.5..3\n" .
            ".54...78.\n" .
            ".1.2.3.8.\n" .
            "6.28.73.4\n" .
            ".........\n" .
            "1.53.98.6\n" .
            ".2.7.1.6.\n" .
            "7..4.2..1"
        );
    }

    public function testGridConstructionFromStringWithBadLine() : void
    {
        $this->expectException(GridLoaderException::class);

        // line 1 doesnt contain enough characters
        $grid = Grid::fromString(
            "2..1.5\n" .
            ".54...71.\n" .
            ".1.2.3.8.\n" .
            "6.28.73.4\n" .
            ".........\n" .
            "1.53.98.6\n" .
            ".2.7.1.6.\n" .
            ".81...24.\n" .
            "7..4.2..1"
        );
    }

    public function testGridConstructionFromStringWithBadCharacters() : void
    {
        $this->expectException(GridLoaderException::class);

        // line 4 contains a '*'
        $grid = Grid::fromString(
            "2..1.5..3\n" .
            ".54...71.\n" .
            ".1.2.3.8.\n" .
            "6.28.*3.4\n" .
            ".........\n" .
            "1.53.98.6\n" .
            ".2.7.1.6.\n" .
            ".81...24.\n" .
            "7..4.2..1"
        );
    }
}
