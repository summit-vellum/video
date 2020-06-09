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
        factory(Video::class, 10)->create();
    }

}
