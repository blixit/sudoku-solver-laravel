<?php

declare(strict_types=1);

namespace Sudoku\Grid;

use DateTime;

interface GridInterface
{
    /**
     * Create a grid from a string representation
     *
     * @return GridInterface
     */
    public static function fromString(string $grid) : self;

    /**
     * Set the grid row using a list of sudoku characters
     *
     * @param int $row
     * @param array $chars
     */
    public function setLineFromString(int $row, array $chars) : void;

    /**
     * Returns a string representation of the grid.
     *
     * @return string
     */
    public function toString() : string;

    /**
     * Returns a string representation of the grid into a human readable format.
     *
     * @return string
     */
    public function toHumanFormat(): string;

    public function setCell(int $row, int $column, string $value) : void;

    public function getCell(int $row, int $column) : string;

    public function isEmpty(int $row, int $column) : bool;

    public function getAuthor(): string;

    public function setAuthor(?string $author = ''): self;

    public function getDescription(): string;

    public function setDescription(?string $description = ''): self;

    public function getCreationDate(): DateTime;

    public function setCreationDate(DateTime $creationDate): self;

    public function getInitialCoordinates(): Coordinate;

    public function setInitialCoordinates(?Coordinate $initialCoordinates = null): self;

    public function getWidth(): int;

    public function getHeight(): int;
}
