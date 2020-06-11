<?php

namespace Quill\Video\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vellum\Contracts\Resource;
use Quill\Video\Models\Video;
use Vellum\Module\Module;
use Quill\Video\Http\Controllers\BaseController;
use Illuminate\Support\Arr;

class VideoController extends BaseController
{
    protected $resource;
    public $module;

    public function __construct(Resource $resource, Module $module)
    {
    	parent::__construct();
        $this->resource = $resource;
        $this->module = $module;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Video $videos, Request $request)
    {
    	$data = [];
    	$limit = $request->input('limit', ($this->site['pagination_limit']?$this->site['pagination_limit']:30));
    	$data['limit'] = $limit;

    	$keyword = $request->input('keyword', false);
		$target = $request->input('target', false);

		$videos = $videos->orderById();

		//wildcard search for video title
		if ($keyword) {
			$videos = $videos->whereTitleLike($keyword);
		}

		$data['target'] = $target;
        $data['tab'] = $request->input('tab', false);

        $data['videos'] = $videos->paginate($limit);

        $data['image_url'] = $this->site['image_domain'];

    	return template('index', $data, $this->module->getName());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['target'] = $request->input('target', false);
        $data['tab'] = $request->input('tab', false);

        return template('add', $data, $this->module->getName());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Video $video, Request $request)
    {
    	$exclude_fields = ['_token'];
		$payloads = $request->except($exclude_fields);

		$video_payload = Arr::only($payloads, ['video_url', 'caption', 'contributor', 'contributor_fee', 'tags']);
		$details = $this->getVideoDetails($video_payload['video_url']);

		if ($details) {

			$url_id = $details['youtube_id'];
			$video = $video->whereUrlId($url_id)->first();

			if (empty($video)) {
				$video = new Video([
					'video_url' 	  => 'https://www.youtube.com/watch?v='.$url_id,
					'thumb_url'		  => $details['thumb_url'],
					'url_id'	  	  => $details['youtube_id'],
					'title' 		  => $details['title'],
					'caption' 		  => $video_payload['caption'],
					'contributor' 	  => $video_payload['contributor'],
					'contributor_fee' => $video_payload['contributor_fee'],
					'tags' 			  => $video_payload['tags']
				]);

				$video->save();

				//generate json to S3

				// sync to video archives
			}

			$return = ['status'=>'1',
    			   		'data'=> [
	    			   		'video_id'=>$video->id,
	    			   		'title'=>$video->title,
	    			   		'youtube_id'=>$url_id,
	    			   		'caption'=>$video->caption,
	    			   		'contributor'=>$video->contributor_fee
    			   		]
    				];

			return response()->json($return);
		} else {
			$return = ['status'=>'2', 'message'=>'Invalid youtube url.'];

			return response()->json($return);
		}

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Video $videos, Request $request)
    {
        $exclude_fields = ['_token', '_method', 'submit'];
		$payloads = $request->except($exclude_fields);

		$video_id = $payloads['id'];
		$target = isset($payloads['target'])?$payloads['target']:'';
		$videos = $videos->find($video_id);
		$details = Arr::except($payloads, ['id', 'target']);

		foreach ($details as $key => $value) {
			$videos->$key = $value;
		}

		$videos->save();

		return redirect('video/library?target='.$target);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
	|--------------------------------------------------------------------------
	| Non-Resource Methods
	|--------------------------------------------------------------------------
	*/
	public function getVideoDetails($url)
	{
		$allowed_host = ['www.youtube.com', 'youtu.be'];
	    $url = parse_url($url);

	    if(in_array($url['host'], $allowed_host)) {
	    	$video_id = $this->extractYoutubeId($url);
		    //$api_key = ENV('GOOGLE_API_KEY');
		    // temporary api key.

	    	$api_key = "AIzaSyBeIAelUoCPMVEVxyTncNZH7yB5mu3i7Yo";
		    $data = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=".$api_key."&part=snippet&id=".$video_id), true);
			$items = array_shift($data['items']);
			$details = $items['snippet'];

			// get only what you need
			$video = [
				'title' => $details['title'],
				'thumb_url' => $details['thumbnails']['medium']['url'],
				'youtube_id' => $items['id']
			];

			return $video;
		}
	}

	public function extractYoutubeId($value)
	{
		$id = ($value['host'] == 'www.youtube.com') ? substr($value['query'], 2) : substr($value['path'], 1);
		return $id;
	}
}
