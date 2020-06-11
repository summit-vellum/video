<?php

namespace Quill\Video\Shortcode;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Quill\Post\Models\Video;

use Vellum\Contracts\Resource;
use Vellum\Contracts\Shortcode;
use Illuminate\Support\Facades\Cookie;

class VideoShortcode implements Shortcode
{
    protected $name = 'Video';
    public $resource;
    public $site;

    public function __construct(Resource $resource)
    {
		$this->resource = $resource;
		$this->site = config('site');
    }

    public function input($post)
    {
        return '';
    }

    public function code()
    {
        return '';
    }

    public function parameters()
    {
        return [
        ];
    }

    public function view($request)
    {

    }


    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($request, $next)
    {
    	$shortcodes = $next($request);

        $shortcodes[] =  [
            'type' => 'menutime',
            'text' => 'video',
            'label' => 'Video',
            'url' => '/video/library',
            'icon' => ''
        ];

        return $shortcodes;
    }
}
