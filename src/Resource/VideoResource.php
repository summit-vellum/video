<?php

namespace Quill\Video\Resource;

use Quill\Html\Fields\ID;
use Quill\Video\Models\Video;
use Vellum\Contracts\Formable;

class VideoResource extends Video implements Formable
{
    public function fields()
    {
        return [
            ID::make()->sortable()->searchable(),
        ];
    }

    public function filters()
    {
        return [
            //
        ];
    }

    public function actions()
    {
        return [
            new \Vellum\Actions\EditAction,
            new \Vellum\Actions\ViewAction,
            new \Vellum\Actions\DeleteAction,
        ];
    }

    public function excludedFields()
    {
    	return [
    		//
    	];
    }
}
