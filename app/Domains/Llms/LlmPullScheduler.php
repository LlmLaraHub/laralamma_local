<?php

namespace App\Domains\Llms;

use App\Models\Llm;
use Facades\App\Domains\Settings\DownloadOllama;
use Illuminate\Support\Facades\Log;

class LlmPullScheduler
{
    public function process()
    {
        foreach (Llm::whereStatus(PullStatus::Pending)->get() as $pull) {
            //@TODO Events and Listeners
            $results = DownloadOllama::pullModel($pull->model_name);
            if ($results === true) {
                $pull->update([
                    'status' => PullStatus::Complete,
                ]);
            } else {
                Log::error('Error with pull', [
                    'message' => $results,
                ]);
                $pull->update([
                    'status' => PullStatus::Failed,
                ]);
            }
        }
    }
}
