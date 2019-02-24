<?php

declare(strict_types=1);

namespace Sudoku\Solver;

use Sudoku\Grid\Coordinate;
use Sudoku\Grid\GridInterface;

class BackTrackingSolver extends AbstractSolver implements SolverInterface
{
    public function solve(GridInterface $grid) : ?GridInterface
    {
        return $this->doSolve($grid) ? $grid : null;
    }

    private function doSolve(GridInterface &$grid) : bool
    {
        // linear loop on lines and columns
        $nextPosition = $this->findEmptyLocation($grid);
        if (! ($nextPosition instanceof Coordinate)) {
            return true;
        }

        for ($digit=1; $digit<=9; $digit++) {
            if ($this->isSafe($grid, $nextPosition->getRow(), $nextPosition->getColumn(), (string) $digit)) {
                $grid->setInitialCoordinates(new Coordinate($nextPosition->getRow(), $nextPosition->getColumn()));
                $grid->setCell($nextPosition->getRow(), $nextPosition->getColumn(), (string) $digit);
                if ($this->solve($grid)) {
                    return true;
                }
                $grid->setCell($nextPosition->getRow(), $nextPosition->getColumn(), '');
            }
        }

        return false;
    }

    /**
     * Find the next empty position
     */
    private function findEmptyLocation(GridInterface $grid) : ?Coordinate
    {
        for ($row = 0; $row < $grid->getHeight(); $row++) {
            for ($col = 0; $col < $grid->getWidth(); $col++) {
                if ($grid->isEmpty($row, $col)) {
                    return new Coordinate($row, $col);
                }
            }
        }

        return null;
    }

    private function isSafe(GridInterface $grid, int $row, int $col, string $digit) : bool
    {
        return
            ! $this->inRow($grid, $row, $digit)
            && ! $this->inCol($grid, $col, $digit)
            && ! $this->inBox($grid, $row - $row % 3, $col - $col % 3, $digit);
    }

    private function inRow(GridInterface $grid, int $row, string $digit) : bool
    {
        for ($col = 0; $col < $grid->getWidth(); $col++) {
            if ($grid->getCell($row, $col) == $digit) {
                return true;
            }
        }

        return false;
    }

    private function inCol(GridInterface $grid, int $col, string $digit) : bool
    {
        for ($row = 0; $row < $grid->getHeight(); $row++) {
            if ($grid->getCell($row, $col) == $digit) {
                return true;
            }
        }

        return false;
    }

    private function inBox(GridInterface $grid, int $boxStartRow, int $boxStartCol, string $digit) : bool
    {
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if ($grid->getCell($row + $boxStartRow, $col + $boxStartCol) == $digit) {
                    return true;
                }
            }
        }

        return false;
    }
}
