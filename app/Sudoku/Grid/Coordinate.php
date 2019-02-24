<?php

declare(strict_types=1);

namespace Sudoku\Grid;

class Coordinate
{
    /**
     * @var int $row
     */
    private $row;

    /**
     * @var int $column
     */
    private $column;

    public function __construct(int $row, int $column)
    {
        $this->column = $column;
        $this->row = $row;
    }

    public function __toString() : string
    {
        return '(' . $this->row . ',' . $this->column . ')';
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
