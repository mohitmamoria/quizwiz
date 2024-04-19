<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use Illuminate\Console\Command;

class ImportQuizFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-questions-from-csv {file} {quizId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a set of questions from a given csv to the given quiz.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = resource_path($this->argument('file'));
        $quiz = Quiz::find($this->argument('quizId'));

        $questions = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($row);
                $order = $row[0];
                $body = $row[1];
                $answers = [];
                for ($i = 2; $i < $num; $i++) {
                    if (strlen($row[$i]) > 0) {
                        $answers[] = $row[$i];
                    }
                }

                $questions[] = compact('order', 'body', 'answers');
            }
            fclose($handle);
        }

        $quiz->questions()->createMany($questions);

        $this->info(sprintf('Saved %d questions.', count($questions)));

        return Command::SUCCESS;
    }
}
