<?php

declare(strict_types=1);

namespace Sudoku\Grid;

use DateTime;
use function array_fill;
use function count;
use function explode;
use function is_numeric;
use function sprintf;
use function str_split;

/**
 * Class Grid
 *
 * Represents sudoku square grid.
 * We admit that x-dimension and y-dimension are equal.
 *
 * In this current implementation, we dont manage many kind of grids/format (sdk, sdx, sdm, ...).
 * To do that, we could rename it to SdxGrid (the current implementation) and move the common
 * properties to an abstract Grid class
 */
class Grid implements GridInterface
{
    const DEFAULT_WIDTH = 9;
    const DEFAULT_HEIGHT = 9;

    /**
     * Relative to the SDK format
     *
     * @var string $author
     */
    private $author = '';

    /**
     * Relative to the SDK format
     *
     * @var string $description
     */
    private $description = '';

    /**
     * Relative to the SDK format
     *
     * @var DateTime $creationDate
     */
    private $creationDate;

    /**
     * The goal of these coordinates is to store the current solver position.
     * Along with the current grid, it's enough to save the current progress.
     * It could be more useful if we plan to develop an interactive solver.
     *
     * @var Coordinate $initialCoordinates
     */
    private $initialCoordinates;

    /**
     * Width
     * We store the width in the case we plan to solve different grids size (4*4, 16*16, ...)
     *
     * @var int $width */
    private $width = self::DEFAULT_WIDTH;

    /**
     * Height
     * We store the height in the case we plan to solve different grids size (4*4, 16*16, ...)
     *
     * @var int $height
     */
    private $height = self::DEFAULT_HEIGHT;

    /**
     * The grid cells
     *
     * @var string[][] $grid */
    private $grid = [];

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->initialCoordinates = new Coordinate(0, 0);

        // build the grid
        $this->grid = array_fill(0, $this->height, []);
        foreach ($this->grid as $k => $row) {
            $this->grid[$k] = array_fill(0, $this->width, '');
        }
    }

    /**
     * Create a grid from a string representation
     *
     * @return GridInterface
     */
    public static function fromString(string $stringGrid): GridInterface
    {
        $grid = new static();
        $lines = explode("\n", $stringGrid);
        if (count($lines) !== $grid->getHeight()) {
            throw new GridLoaderException('The number of lines is not correct');
        }

        foreach ($lines as $i => $line) {
            $chars = str_split($line);
            // set the row cells
            $grid->setLineFromString($i, $chars);
        }
        return $grid;
    }

    /**
     * Set the grid row using a list of sudoku characters
     *
     * @param int $row
     * @param array $chars
     */
    public function setLineFromString(int $row, array $chars) : void
    {
        if (($size = count($chars)) !== 9) {
            throw new GridLoaderException(sprintf(
                'The line %d doesn\'t contain the good characters count (%d)',
                $row,
                $size
            ));
        }

        // validate each character and set grid cells
        foreach ($chars as $k => $char) {
            if (! is_numeric($char) && $char !== '.') {
                throw new GridLoaderException(sprintf(
                    'Bad character (%s) found at (%d, %d) into the grid. Allowed: \'.\' or 1-9 ',
                    $char,
                    $row,
                    $k
                ));
            }
            $this->setCell(
                $row,
                $k,
                $char === '.' ? '' : (string) $char
            );
        }
    }

    public function setCell(int $row, int $column, string $value) : void
    {
        $this->grid[$row][$column] = $value;
    }

    public function getCell(int $row, int $column) : string
    {
        return $this->grid[$row][$column];
    }

    public function isEmpty(int $row, int $column) : bool
    {
        return empty($this->grid[$row][$column]);
    }

    /**
     * Returns a string representation of the grid.
     *
     * @return string
     */
    public function toString(): string
    {
        $string = '';
        for ($row = 0; $row < $this->getHeight(); $row++) {
            for ($col = 0; $col < $this->getWidth(); $col++) {
                $cell = $this->getCell($row, $col);
                if (empty($cell)) {
                    $cell = '.';
                }
                $string .= $cell;
            }
            $string .= "\n";
        }
        return $string;
    }

    /**
     * Returns a string representation of the grid into a human readable format.
     *
     * @return string
     */
    public function toHumanFormat(): string
    {
        $string = '';
        for ($row = 0; $row < $this->getHeight(); $row++) {
            for ($col = 0; $col < $this->getWidth(); $col++) {
                $cell = $this->getCell($row, $col);
                if (empty($cell)) {
                    $cell = '.';
                }
                $string .= $cell;
                if (($col + 1) % 3 === 0) {
                    $string .= ' ';
                }
            }
            $string .= "\n";
            if (($row + 1) % 3 === 0) {
                $string .= "\n";
            }
        }
        return $string;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(?string $author = ''): GridInterface
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description = ''): GridInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate(DateTime $creationDate): GridInterface
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return Coordinate
     */
    public function getInitialCoordinates(): Coordinate
    {
        return $this->initialCoordinates;
    }

    /**
     * @param Coordinate $initialCoordinates
     */
    public function setInitialCoordinates(?Coordinate $initialCoordinates = null): GridInterface
    {
        $this->initialCoordinates = $initialCoordinates ?? new Coordinate(0, 0);
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
