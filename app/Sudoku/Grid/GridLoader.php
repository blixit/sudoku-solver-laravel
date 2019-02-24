<?php

declare(strict_types=1);

namespace Sudoku\Grid;

use DateTime;
use SplFileInfo;
use SplFileObject;
use Throwable;
use function file_exists;
use function sprintf;
use function str_split;
use function str_replace;
use function trim;

class GridLoader implements GridLoaderInterface
{
    /**
     * Load sudoku file
     *
     * @param string $absolutePath
     *
     * @return null|GridInterface
     */
    public static function load(string $absolutePath): ?GridInterface
    {
        if (! file_exists($absolutePath)) {
            throw new GridLoaderException(sprintf('The file (%s) was not found', $absolutePath));
        }

        // at this point we could handle different kind of file extensions like sdk, sdm, sdx, ss ...
        // but we wont

        $fileInfo = new SplFileInfo($absolutePath);

        if (! $fileInfo->isFile()) {
            throw new GridLoaderException(sprintf('The file (%s) is not a regular file', $absolutePath));
        }

        $file = $fileInfo->openFile('r');

        $grid = self::loadSdk($file);

        unset($file);

        return $grid;
    }

    private static function loadSdk(SplFileObject $file): ?GridInterface
    {
        $grid = new Grid();

        try {
            $read = $file->fscanf("#A%s");
            $grid->setAuthor($read[0]);

            $line = $file->fgets();
            $read = trim(str_replace('#D', '', $line));
            $grid->setDescription($read);

            /** @var string $read */
            $read = $file->fscanf("#B%s");

            /** @var DateTime $date */
            $date = DateTime::createFromFormat('d-m-Y', $read[0]);
            $grid->setCreationDate($date);

            list($row, $column) = $file->fscanf("#P%d %d");
            $grid->setInitialCoordinates(new Coordinate((int) $row, (int) $column));

            $i = 0;
            /** @var string $sudokuLine */
            while ($sudokuLine = $file->fscanf("%s")) {
                // read sudoku line
                $chars = str_split($sudokuLine[0]);
                // set the row cells
                $grid->setLineFromString($i, $chars);
                $i++;
            }
        } catch (Throwable $exception) {
            throw new GridLoaderException($exception->getMessage());
        }

        return $grid;
    }

    /**
     * Writes the grid into the SDK format
     *
     * @param GridInterface $grid
     * @param string $filename
     */
    public static function save(GridInterface $grid, string $filename): void
    {
        // at this point we could handle different kind of file extensions like sdk, sdm, sdx, ss ...
        // but we wont

        $fileInfo = new SplFileInfo($filename);

        $file = $fileInfo->openFile('w+');

        $file->fwrite(sprintf("#A%s\n", $grid->getAuthor()));
        $file->fwrite(sprintf("#D%s\n", $grid->getDescription()));
        $file->fwrite(sprintf("#B%s\n", $grid->getCreationDate()->format('d-m-Y')));
        $icoordinate = $grid->getInitialCoordinates();
        $file->fwrite(sprintf("#P%d %d\n", $icoordinate->getRow(), $icoordinate->getColumn()));
        $file->fwrite($grid->toString());

        unset($file);
    }
}
