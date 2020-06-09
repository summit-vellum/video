<?php 

namespace Quill\Video\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Quill\Video\Models\Video;

class VideoSaving
{
    // use Dispatchable, InteractsWithSockets, 
    use SerializesModels;
 
    public $data;

    /**
     * Create a new event instance.
     *
     * @param  \Quill\Video\Models\Video  $data
     * @return void
     */
    public function __construct(Video $data) 
    {
        $this->data = $data;  
    }
}
