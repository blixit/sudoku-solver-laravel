<?php

namespace App\Http\Controllers\Sudoku;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sudoku\Service\SudokuService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;
use function sprintf;
use function implode;

class SudokuController extends Controller
{
    public function solve(Request $request, string $type, SudokuService $sudokuService)
    {
        try {
            switch ($type) {
                case SudokuService::SOURCE_STRING:
                    $stringGrid = $request->request->get('grid');
                    $grid = $sudokuService->loadGridFromString($stringGrid);

                    break;
                case SudokuService::SOURCE_FILE:
                    /** @var UploadedFile $file */
                    $file = $request->files->get('file');
                    if (! ($file instanceof UploadedFile)) {
                        throw new Exception('A file with the sudoku problem is required');
                    }
                    $grid = $sudokuService->loadGridFromPath((string) $file->getRealPath());

                    break;
                default:
                    throw new Exception(sprintf(
                        'Unknown source type. Allowed: %s',
                        implode(',', [SudokuService::SOURCE_STRING, SudokuService::SOURCE_FILE])
                    ));
            }

            $solvedGrid = $sudokuService->solveGrid($grid);
        } catch (Throwable $exception) {
            return response()->json(
                [ 'error' => sprintf('%s', $exception->getMessage()) ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json([
            'grid' => $grid->toString(),
            'solved' => $solvedGrid->toString()
        ]);
    }
}
