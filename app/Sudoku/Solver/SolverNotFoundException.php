<?php

declare(strict_types=1);

namespace Sudoku\Solver;

use Exception;
use Throwable;

class SolverNotFoundException extends Exception
{
    public function __construct(string $reason = 'Unknwon')
    {
        parent::__construct('Solver was not found. Reason: ' . $reason);
    }
}
