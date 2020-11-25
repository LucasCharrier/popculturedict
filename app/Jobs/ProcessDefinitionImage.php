<?php

namespace App\Jobs;

use App\Models\Definition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDefinitionImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $definition;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // use a package to gennerate image either from html or using custom methodes
    }
}
