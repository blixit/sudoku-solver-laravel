<?php

declare(strict_types=1);

namespace Sudoku\Grid;

interface GridLoaderInterface
{
    /**
     * Load sudoku file
     *
     * @param string $absolutePath
     *
     * @return null|GridInterface
     */
    public static function load(string $absolutePath) : ?GridInterface;

    /**
     * Write a grid into the given file
     *
     * @param GridInterface $grid
     * @param string $filename
     */
    public static function save(GridInterface $grid, string $filename): void;
}
