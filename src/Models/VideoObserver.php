<?php

namespace Quill\Video\Models;

use Illuminate\Support\Str;
use Quill\Video\Events\VideoCreating;
use Quill\Video\Events\VideoCreated;
use Quill\Video\Events\VideoSaving;
use Quill\Video\Events\VideoSaved;
use Quill\Video\Events\VideoUpdating;
use Quill\Video\Events\VideoUpdated;
use Quill\Video\Models\Video;

class VideoObserver
{

    public function creating(Video $video)
    {
        // creating logic... 
        event(new VideoCreating($video));
    }

    public function created(Video $video)
    {
        // created logic...
        event(new VideoCreated($video));
    }

    public function saving(Video $video)
    {
        // saving logic...
        event(new VideoSaving($video));
    }

    public function saved(Video $video)
    {
        // saved logic...
        event(new VideoSaved($video));
    }

    public function updating(Video $video)
    {
        // updating logic...
        event(new VideoUpdating($video));
    }

    public function updated(Video $video)
    {
        // updated logic...
        event(new VideoUpdated($video));
    }

}