<?php

namespace Quill\Video\Listeners;

class RegisterVideoModule
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        return [
            'name' => 'video',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current"><path class="heroicon-ui" d="M4 3h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm16 4V5H4v2h16zm0 2H4v10h16V9z"/></svg>',
            'title' => 'Video',
            'class' => 'font-xl text-blue-500',
            'module' => 'Video',
            'parent' => '',
            'model' => \Quill\Video\Models\Video::class,
            'overrideModule' => '',
            'permissions' => [],
            'description' => '',
            'navHeader' => [
            	'left' => '',
            	'right' => '',
            	'field' => '' //field to be rendered on the right side of the navheader on edit
            ]
        ];
    }
}
