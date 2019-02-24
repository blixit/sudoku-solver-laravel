<?php

declare(strict_types=1);

namespace Sudoku\Service;

use Sudoku\Grid\Grid;
use Sudoku\Grid\GridInterface;
use Sudoku\Grid\GridLoader;
use Sudoku\Solver\SolverResolver;

/**
 * Class SudokuService
 *
 * Facade Design pattern is used here to propose the minimal interface required to
 * resolve a sudoku grid.
 * People using this library are free to use directly the internal components
 */
class SudokuService
{
    const SOURCE_FILE = 'file';
    const SOURCE_STRING = 'string';

    /**
     * @param string $path
     * @return GridInterface
     */
    public function loadGridFromPath(string $path) : GridInterface
    {
        return GridLoader::load($path);
    }

    /**
     * @param string $stringGrid
     * @return GridInterface
     */
    public function loadGridFromString(string $stringGrid) : GridInterface
    {
        return Grid::fromString($stringGrid);
    }

    /**
     * @param GridInterface $grid
     * @param string $filename
     */
    public function saveGrid(GridInterface $grid, string $filename) : void
    {
        GridLoader::save($grid, $filename);
    }

    /**
     * @param GridInterface $grid
     * @return GridInterface
     */
    public function solveGrid(GridInterface $grid) : ?GridInterface
    {
        $grid = clone $grid;
        return SolverResolver::resolve()->solve($grid);
    }
}
