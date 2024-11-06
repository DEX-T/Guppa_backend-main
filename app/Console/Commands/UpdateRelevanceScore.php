<?php

namespace App\Console\Commands;

use App\Models\GuppaJob;
use App\Models\GuppaKeyword;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateRelevanceScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guppa_jobs:update-relevance-score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update relevance scores for jobs';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobs = GuppaJob::available()->get();

        foreach ($jobs as $job) {
            $relevanceScore = $this->calculateRelevanceScore($job);
            $job->relevance_score = $relevanceScore;
            $job->save();
        }

        $this->info('Relevance scores updated successfully.');
    }

    private function calculateRelevanceScore(GuppaJob $job)
    {
        $score = 0;

        // Example relevance score calculation
        // Increase score for keyword matches
        $keywords = GuppaKeyword::all()->pluck('keyword')->toArray();
        foreach ($keywords as $keyword) {
            if (stripos(Str::lower($job->title), $keyword) !== false) {
                $score += 10;
            }
            if (stripos(Str::lower($job->description), $keyword) !== false) {
                $score += 5;
            }
        }

        // Increase score for higher views and applications
        $score += $job->views * 0.1;
        $score += $job->applications * 0.2;

        return $score;
    }
}
