<?php

namespace Quill\Video\Models;

use Vellum\Models\BaseModel;

class Video extends BaseModel
{

    protected $table = 'videos';
    protected $primaryKey = 'id';

    public function scopeOrderById($query, $order = 'DESC')
    {
        return $query->orderBy('id', $order);
    }

    public function scopeWhereTitleLike($query, $keyword)
    {
        // try to search for the title first,
        // then try to search for a wild card keyword in tags
        return $query->where('title', 'LIKE', '%'. $keyword . '%')
                ->orWhere('tags', 'LIKE', '%'. $keyword . '%');
    }

    public function scopeWhereUrlId($query, $id)
    {
        return $query->where('url_id', $id);
    }

    public function history()
    {
        return $this->morphOne('Quill\History\Models\History', 'historyable');
    }

    public function resourceLock()
    {
        return $this->morphOne('Vellum\Models\ResourceLock', 'resourceable');
    }

    public function autosaves()
    {
        return $this->morphOne('Vellum\Models\Autosaves', 'autosavable');
    }

}
