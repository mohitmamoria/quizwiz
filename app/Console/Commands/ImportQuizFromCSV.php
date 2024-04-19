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
    protected $signature = 'app:import-questions-from-csv {file} {quizId} {--append}';

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

        $this->info(sprintf('Using file: %s', $file));
        $this->info(sprintf('Importing into quiz: %s', $quiz->name));

        if (! $this->option('append')) {
            $this->info('Deleting previous questions.');
            $quiz->questions()->delete();
            $this->comment('Deleted.');
        }

        $questions = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($row);
                $order = $row[0];
                $body = $row[1];
                $answers = [];
                for ($i = 2; $i < $num; $i++) {
                    if (strlen($row[$i]) > 0) {
                        $answers[] = strtolower($row[$i]);
                    }
                }

                $questions[] = compact('order', 'body', 'answers');
            }
            fclose($handle);
        }

        $quiz->questions()->createMany($questions);

        $this->info(sprintf('Added %d questions.', count($questions)));

        return Command::SUCCESS;
    }
}
