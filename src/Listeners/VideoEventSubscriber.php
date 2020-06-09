<?php

namespace Quill\Video\Listeners;
use Illuminate\Support\Facades\Log;

class VideoEventSubscriber
{
    /**
     * Handle the event.
     */
    public function handleCreated($event) 
    {
        //
    } 

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Quill\Video\Events\VideoCreated',
            'Quill\Video\Listeners\VideoEventSubscriber@handleCreated'
        ); 
    }
}