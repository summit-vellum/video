<?php

namespace Quill\Video\Listeners;

class RegisterVideoPermissionModule
{ 
    public function handle()
    {
        return [
            'Video' => [
                'view'
            ]
        ];
    }
}
