<?php

declare(strict_types=1);

namespace Sudoku\Solver;

/**
 * Class SolverResolver
 *
 * For this exercise, we don't use many strategies.
 *
 * If we had to add other strategies, we could add an abstract StrategyOption class to handle specific
 * solver options. For instance:
 *
 * return new DistributedSolver(new DistributedStrategyOption)
 * where DistributedStrategyOption extends StrategyOption
 * and where StrategyOption children must implement StrategyOptionInterface
 *
 * In this case, the resolve method from SolverResolver class should be changed like this:
 * public static function resolve(StrategyOptionInterface $options) : SolverInterface
 * then the strategy would be guessed from StrategyOptionInterface class at runtime
 */
class SolverResolver
{
    public const STRATEGY_BACKTRACKING = 1;

    /** @var int $strategy */
    private static $strategy;

    public static function resolve(int $strategy = self::STRATEGY_BACKTRACKING) : SolverInterface
    {
        self::$strategy = $strategy;
        switch ($strategy) {
            case self::STRATEGY_BACKTRACKING:
                return new BackTrackingSolver();
            /**
             * add your other solvers here
             * case self::STRATEGY_RANDOM: return new RandomSolver();
             * case self::STRATEGY_PARALLEL: return new DistributedSolver();
             * ...
             */
        }

        throw new SolverNotFoundException('Unknown solver strategy (' . $strategy . ')');
    }

    public static function getCurrentStrategy() : int
    {
        return self::$strategy;
    }
}
