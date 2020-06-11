<button type="button" class="btn btn-outline cf-button pull-left {{ isset($leftBtnClass) ? $leftBtnClass : '' }}" close-modal>{{ isset($leftBtnTxt) ? $leftBtnTxt : 'Cancel' }}</button>
<button type="button" class="btn btn-success cf-button pull-right {{ isset($rightBtnClass) ? $rightBtnClass : '' }}" {!!  isset($attributes) ? $attributes : '' !!}>{{ isset($rightBtnTxt) ? $rightBtnTxt : 'Add to Content' }}</button>

<button type="button" class="btn btn-outline btn-mobile cf-button pull-right mr-2 mobile-clear-mr" style="display: {{ ($target=='alternateHeader') ? 'none' : 'block' }}" onclick="window.location='{{ url('video/library/create?target='.$target.'&tab=2') }}'">
            Add New Non-Summit Video
        </button>

<a href="#internal" class="action" data-toggle="tab" onclick="window.location='{{ url('video/library/create?target='.$target.'&tab=1') }}'">
    <button type="button" class="btn btn-outline btn-mobile cf-button pull-right mr-2 mobile-clear-mr">
        Add New Summit Video
    </button>
</a>
