<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JonnyW\PhantomJs\Client;

use App\Models\Definition;

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

        header( "Content-type: image/png" );
        $im = @imagecreate(400, 400) or die("Impossible d'initialiser la bibliothèque GD");
        $background_color = imagecolorallocate($im, 0, 0, 0);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5, $this->definition->word->name, $text_color);
        imagestring($im, 1, 5, 35, $this->definition->text, $text_color);
        //
        // TODO : generate file name and use env for production destination
        //
        imagepng($im, './file.png');
        imagedestroy($im);
    }
}
