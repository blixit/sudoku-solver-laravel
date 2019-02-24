<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sudoku\Service\SudokuService;
use Symfony\Component\Console\Input\InputArgument;
use Throwable;

class SudokuSolver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sudoku:solve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Solve a sudoku grid';

    /**
     * The service used to resolve sudoku grids
     *
     * @var SudokuService $sudokuService
     */
    private $sudokuService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SudokuService $sudokuService)
    {
        parent::__construct();
        $this->addArgument('gridPath', InputArgument::REQUIRED, 'The location of the sudoku file to resolve');
        $this->addArgument('output', InputArgument::OPTIONAL, 'The location of the solution file');

        $this->sudokuService = $sudokuService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            /** @var string $path */
            $path = $this->input->getArgument('gridPath');
            $grid = $this->sudokuService->loadGridFromPath($path);

            echo $grid->toHumanFormat();

            $solvedGrid = $this->sudokuService->solveGrid($grid);

            $this->output->writeln($solvedGrid->toHumanFormat());

            $output = $this->input->getArgument('output');
            if (! empty($output)) {
                $this->sudokuService->saveGrid($solvedGrid, $output);
            }
        } catch (Throwable $exception) {
            dump($exception->getMessage());
        }

        return 0;
    }
}
