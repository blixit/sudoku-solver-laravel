<?php

declare(strict_types=1);

namespace Tests\Unit\Sudoku\Solver;

use Sudoku\Solver\BackTrackingSolver;
use Sudoku\Solver\SolverNotFoundException;
use Sudoku\Solver\SolverResolver;
use Tests\TestCase;

class SolverResolverTest extends TestCase
{
    public function testResolver() : void
    {
        $solver = SolverResolver::resolve();

        $this->assertInstanceOf(BackTrackingSolver::class, $solver);
        $this->assertSame(SolverResolver::getCurrentStrategy(), SolverResolver::STRATEGY_BACKTRACKING);
    }

    public function testSolverNotFound() : void
    {
        $randomStrategy = 3;
        $this->expectException(SolverNotFoundException::class);

        $solver = SolverResolver::resolve($randomStrategy);
    }
}
