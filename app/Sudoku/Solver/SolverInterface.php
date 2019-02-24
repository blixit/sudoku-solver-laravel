<?php

declare(strict_types=1);

namespace Sudoku\Solver;

use Sudoku\Grid\GridInterface;

interface SolverInterface
{
    public function solve(GridInterface $grid) : ?GridInterface;
}
