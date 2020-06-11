<button type="button" class="btn btn-outline cf-button pull-left {{ isset($leftBtnClass) ? $leftBtnClass : '' }}" onclick="window.location='{{ url('video/library?target='.$target) }}'">{{ isset($leftBtnTxt) ? $leftBtnTxt : 'Cancel' }}</button>
<button type="button" class="btn btn-success cf-button pull-right {{ isset($rightBtnClass) ? $rightBtnClass : '' }}" {!!  isset($attributes) ? $attributes : '' !!}>{{ isset($rightBtnTxt) ? $rightBtnTxt : 'Add to Content' }}</button>

<a href="#external" class="action" data-toggle="tab">
    <button type="button" class="btn btn-outline cf-button pull-right mr-2" style="display: {{ ($target=='alternateHeader') ? 'none' : 'block' }}">
        Add New Non-Summit Video
    </button>
</a>

<a href="#internal" class="action" data-toggle="tab">
    <button type="button" class="btn btn-outline cf-button pull-right mr-2">
        Add New Summit Video
    </button>
</a>
