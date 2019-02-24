<?php

declare(strict_types=1);

namespace Tests\Unit\Sudoku\Grid;

use Sudoku\Grid\GridInterface;
use Sudoku\Grid\GridLoader;
use Sudoku\Grid\GridLoaderException;
use Tests\TestCase;

class GridLoaderTest extends TestCase
{
    public function testGridLoader() : void
    {
        $path = base_path() . '/public/grids/yoomap-hard.sdk';

        $grid = GridLoader::load($path);

        $this->assertInstanceOf(GridInterface::class, $grid);
    }

    public function testGridLoaderWithBadFile() : void
    {
        $path = base_path() . '/public/grids/yoomap-bad.sdk';

        $this->expectException(GridLoaderException::class);

        GridLoader::load($path);
    }

    public function testGridLoaderWithNonExistingFile() : void
    {
        $path = base_path() . '/public/grids/casper-file.sdk';

        $this->expectException(GridLoaderException::class);

        GridLoader::load($path);
    }

    public function testGridLoaderSaveFile() : void
    {
        $directory = base_path();
        $path   = $directory . '/public/grids/yoomap-hard.sdk';
        $output = $directory . '/saved.sdk';

        $grid = GridLoader::load($path);

        GridLoader::save($grid, $output);

        $this->assertFileExists($output);

        unlink($output);
    }
}
