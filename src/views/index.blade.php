@extends('vellum::modalExtend')

@push('css')
<!-- <link href="{{ asset('vendor/imagelibrary/css/dropzone/container-dropzone.css') }}" rel="stylesheet"> -->
@endpush

<style type="text/css"></style>

@section('head')
     <div class="px-3">
        @include('video::modal.header-buttons', ['rightBtnClass' => '', 'attributes' => arrayToHtmlAttributes(['id' => 'insertVideo', 'disabled' => 'disabled'])])
    </div>
@endsection


@section('extend')
<form role="form" id="form" method="post" action="#" autocomplete="off">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="_method" value="put">
    <input type="hidden" name="id" id="video_id">
    <input type="hidden" name="title" id="video_title">

    <div class="my-5">
        <label class="cf-label">URL</label>
        <h4 id="url"></h4>
    </div>
    <div class="form-group">
        <label class="cf-label d-block">Caption <small class="right"><em>optional</em></small></label>
        <textarea id="caption" name="caption" class="cf-input" placeholder="Write your caption" rows="5"></textarea>
        <div class="mt-2">
            @icon(['icon' => 'info', 'classes' => 'pull-left'])
            <div style="padding-left: 17px;">
                <small class="cf-note">The ideal length of an image caption is below 155 characters.</small>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="cf-label">Videographer</label>
        <input type="text" id="contributor" name="contributor" class="cf-input" placeholder="Videographer">
    </div>
    <div class="form-group">
        <label class="cf-label">Contributor Fee</label>
        <input type="text" id="contributor_fee" name="contributor_fee" class="cf-input" placeholder="0.00" data-number-format>
    </div>
    <div class="form-group">
        <label class="cf-label">Tags</label>
        <textarea id="tags" name="tags" class="cf-input" placeholder="Write your caption" rows="5"></textarea>
        <div class="mt-2">
            @icon(['icon' => 'info', 'classes' => 'pull-left'])
            <div style="padding-left: 17px;">
                <small class="cf-note">The ideal length of an image caption is below 155 characters.</small>
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" value="Save Changes" id="submit" name="submit" class="btn btn-success btn-lg btn-block" disabled>
    </div>
</form>
@stop

@section('content')
<div class="container">
    <div class="px-3">
        <div class="row mb-3">
            <span class="cf-label">Video Library</span>
        </div>

        <div class="row mb-3">
            <form role="form" autocomplete="off">
                <div class="col-md-10 pl-0 mb-3">
                    <input type="text" class="cf-input" name="keyword" placeholder="Enter title or tag to search">
                </div>
                <div class="col-md-2 px-0 mb-3">
                <input type="hidden" name="target" value="{{ $target }}">
                    <input type="submit" name="submit" value="Search" class="btn btn-primary btn-block cf-button">
                </div>
            </form>
        </div>

        <div id="video-container" class="row">
            @if(count($videos) >= 1)
                @foreach ($videos as $video)
                    <div role="button" class="video col-md-3 mb-3" data-video="{{ $video }}">
                        <figure class="figure">
                            <img src="{{ $video['thumb_url'] }}" class="figure-img">
                            <figcaption class="figure-caption">{{ $video['title'] }}</figcaption>
                        </figure>
                    </div>
                @endforeach
            @else
                <div id="no-result" class="text-center h4 mt-3">
                    <i>No video found. Change your search parameters and try submitting again.</i>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
	var numberFormat = $('[data-number-format]');

    /* step 1: search article */
    $('.video').on('click', function(){
    	$('.modal-dialog', parent.document.body).addClass('modal-extended');

        video = $(this).data('video');
        action = "{{ url('video/library') }}/" + video.id;

        // toggle image selector
        $('.video img').removeClass('item-selected');
        $(this).find('img').addClass('item-selected');

        $("#form").prop("action", action);
        $('#url').text(video.video_url);
        $('#video_id').val(video.id);
        $('#video_title').val(video.title);
        $('#caption').val(video.caption);
        $('#contributor').val(video.contributor);
        $('#contributor_fee').val(video.contributor_fee);
        $('#tags').val(video.tags);

        var shortcode = '[youtube:{"videoId":"'+video.id+'","youtubeId":"'+video.url_id+'", "caption":"'+((video.caption)?video.caption:'')+'"}]';
        $('#insertVideo').attr('data-shortcode', shortcode).prop('disabled', false);

        $("#submit").prop("disabled", false);

        numberFormat.each(function(i,e){
            $(e).trigger('change');
        });
    });

    numberFormat.on('change', function(event) {
	    var el = $(this),
	        val = el.val().replace(/,/g, ''),
	        config = el.data('numberFormat'),
	        settings = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

	    if(config) {
	        $.extend(settings, config);
	    }

	    if(isNaN(val) || val == ''){
	        el.val('');
	    } else {
	        val = parseFloat(val);
	        el.val(val.toLocaleString(undefined, settings));
	    }
	});

    // insert short code
    $('#insertVideo').on('click', function(){
        var target = "{{ $target }}";
        if(target == 'alternateHeader'){
            var video_id      = $('#video_id').val();
            var video_title   = $('#video_title').val();
            var video_caption = $('#caption').val();

            // set alternate header type
            $("."+target+"Type", parent.document.body).val('Video');
            // set video id
            $(".article_video_id", parent.document.body).val(video_id);
            // set video title
            $("."+target+"Title", parent.document.body).html('Video - '+video_title);
            // set video caption
            $("."+target+"Caption", parent.document.body).val(video_caption);
            $(".article_video_caption", parent.document.body).val(video_caption);
            // hide alternate header selection
            $("."+target+"Selection", parent.document.body).addClass('hide');
            // show video container
            $("."+target+"Container", parent.document.body).removeClass('hide');
            $("."+target+"Caption", parent.document.body).removeClass('hide');
            // display article image to off
            $("#article_hide_image_status", parent.document.body).trigger('click');
        }else{
            var shortcode = $(this).data('shortcode');
            parent.tinymce.get('content').execCommand('mceInsertContent', false, shortcode);
        }
        $('[close-modal]').click();
    })
    </script>
@endpush
