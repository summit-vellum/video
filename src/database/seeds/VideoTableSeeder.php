<?php

use Illuminate\Database\Seeder;
use Quill\Video\Models\Video;

class VideoTableSeeder extends Seeder
{
   	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$old_db = DB::connection('olddb');

    	$itemsPerBatch = 500;

    	$videos = $old_db->table('tbl_video_library');

    	$this->command->getOutput()->progressStart($videos->count());

    	$vellumVideos = $videos->orderBy('id')->chunk($itemsPerBatch, function($videos){
    		foreach ($videos as $video) {
    			$migratedVideo = new Video;
    			$migratedVideo->create([
    				'id' => $video->id,
    				'video_url' => $video->video_url,
    				'thumb_url' => $video->thumb_url,
    				'url_id' => $video->url_id,
    				'title' => $video->title,
    				'caption' => $video->caption,
    				'contributor' => $video->contributor,
    				'contributor_fee' => $video->contributor_fee,
    				'tags' => $video->tags,
    				'created_at'=> ($video->date_created != '0000-00-00 00:00:00') ? $video->date_created : NULL,
    				'updated_at'=> ($video->date_modified != '0000-00-00 00:00:00') ? $video->date_modified : NULL
    			]);

    			$this->command->getOutput()->progressAdvance();
    		}
    	});

        $this->command->getOutput()->progressFinish();
    }

}
