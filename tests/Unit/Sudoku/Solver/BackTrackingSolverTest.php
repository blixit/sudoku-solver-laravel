<?php

declare(strict_types=1);

namespace Tests\Unit\Sudoku\Solver;

use Sudoku\Grid\GridInterface;
use Sudoku\Service\SudokuService;
use Sudoku\Solver\SolverResolver;
use Tests\TestCase;

class BackTrackingSolverTest extends TestCase
{
    public function testSolver() : void
    {
        $solver = SolverResolver::resolve();

        $sudokuService = new SudokuService();
        $grid = $sudokuService->loadGridFromString(
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

        $solvedGrid = $solver->solve($grid);

        $this->assertInstanceOf(GridInterface::class, $solvedGrid);
    }

    public function testCorruptedGrid() : void
    {
        $solver = SolverResolver::resolve();

        $sudokuService = new SudokuService();

        // this grid has 2 '8' in the 8th column that makes it incorrect
        $grid = $sudokuService->loadGridFromString(
            "2..1.5..3\n" .
            ".54...78.\n" .
            ".1.2.3.8.\n" .
            "6.28.73.4\n" .
            ".........\n" .
            "1.53.98.6\n" .
            ".2.7.1.6.\n" .
            ".81...24.\n" .
            "7..4.2..1"
        );

        $solvedGrid = $solver->solve($grid);

        $this->assertSame(null, $solvedGrid);
    }
}
