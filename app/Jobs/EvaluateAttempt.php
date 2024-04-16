<?php

namespace App\Jobs;

use App\Models\Attempt;
use App\Services\Ai;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EvaluateAttempt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Attempt $attempt)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $isCorrect = $this->evaluateUsingGivenPossibilities();

        if (! $isCorrect) {
            $isCorrect = $this->evaluateUsingAI();
        }

        $this->attempt->update([
            'evaluated_at' => now(),
            'is_correct' => $isCorrect,
            'health_spent' => $isCorrect ? $this->attempt->health_spent - 3 : $this->attempt->health_spent,
        ]);
    }

    protected function evaluateUsingGivenPossibilities(): bool
    {
        $question = $this->attempt->question;

        foreach ($question->answers as $answer) {
            if ($this->attempt->answer == $answer) {
                return true;
            }
        }

        return false;
    }

    protected function evaluateUsingAI(): bool
    {
        $question = $this->attempt->question;

        $ai = new Ai;
        $response = $ai->client()->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a quiz evaluator. Given a question and variations of the correct answers, you have to evaluate whether the candidate answer is same as the correct answer or not. If it is same, reply with "yes", otherwise reply with "no".',
                ],
                [
                    'role' => 'user',
                    'content' => sprintf("Question:\n%s\n\Variations of the correct answers:\n%s\n\nCandidate answer:\n%s",
                        $question->body, implode(', ', $question->answers), $this->attempt->answer
                    ),
                ],
            ],
        ]);

        $evaluation = strtolower($response->choices[0]->message->content);

        return $evaluation == 'yes';
    }
}
