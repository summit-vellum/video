@extends('vellum::modal')

@push('css')
<!-- <link href="{{ asset('vendor/imagelibrary/css/dropzone/container-dropzone.css') }}" rel="stylesheet"> -->
@endpush

<style type="text/css"></style>

@section('head')
     <div class="px-3">
        @include('video::modal.header-buttons-inner', ['rightBtnClass' => '', 'attributes' => arrayToHtmlAttributes(['id' => 'save-continue'])])
    </div>
@endsection

@section('content')
 <div class="px-3">
    <div class="row">
        <div class="tab-content px-2 py-2">
            <div class="tab-pane {{($tab == 1)?'active':''}}" id="internal">
                <form role="form" id="internal" autocomplete="off">
                <input type="hidden" name="target" class="target" value="{{ $target }}">
                <input type="submit" name="submitInternal" class="hide">
                    <div class="form-group">
                        <input type="url" id="video_url" name="video_url" class="cf-input" placeholder="Enter Video Url" required>
                        <div class="mt-2">
                            @icon(['icon' => 'info', 'classes' => 'pull-left'])
                            <small class="cf-note">Please enter only Summit Media produced video. This video will be included in our Content Library.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="cf-label">Caption</label>
                        <textarea id="caption" name="caption" class="cf-input" placeholder="Write your caption"></textarea>
                        <div class="mt-2">
                            @icon(['icon' => 'info', 'classes' => 'pull-left'])
                            <div style="padding-left: 17px;">
                                <small class="cf-note">The ideal length of an image caption is below 155 characters.</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-2 mb-5">
                        @icon(['icon' => 'info', 'classes' => 'pull-left'])
                        <small class="cf-note">Information added below will only appear in our Content Library. This will not be displayed on the live sites.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="cf-label">Videographer</label>
                                <input type="text" id="contributor" name="contributor" class="cf-input" placeholder="Enter videographer's name, source, and/or brand">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="cf-label">Tags</label>
                                <input type="text" id="tags" name="tags" class="cf-input" placeholder="Add tag to this video">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="cf-label">Contributor Fee</label>
                                <input type="text" id="contributor_fee" name="contributor_fee" class="cf-input" placeholder="Enter cost producing this video" data-number-format>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tab-pane {{($tab == 2)?'active':''}}" id="external">
                <form role="form" id="external" autocomplete="off">
                <input type="submit" name="submitExternal" class="hide">
                    <div class="form-group">
                        <input type="url" id="external_url" name="external_url" class="cf-input" placeholder="Enter Video Url" required>
                        <div class="mt-2">
                            @icon(['icon' => 'info', 'classes' => 'pull-left'])
                            <small class="cf-note">Please enter only Non-Summit Media produced video. This video will not be included in our Content Library.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="cf-label">Caption</label>
                        <textarea id="external_caption" name="external_caption" class="cf-input" placeholder="Write your caption"></textarea>
                        <div class="mt-2">
                            @icon(['icon' => 'info', 'classes' => 'pull-left'])
                            <div style="padding-left: 17px;">
                                <small class="cf-note">The ideal length of an image caption is below 155 characters.</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <input type="hidden" id="action" value="{{ ($tab == 1)?'#internal':'#external'}}">
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
$('a.action').on('click', function(){
    $('#action').val($(this).attr('href'));
});

$('#internal').submit(function(event){
    // cancels the form submission
    event.preventDefault();

    $.ajax({type: 'POST',
        url: "{{ url('/video/library') }}",
        data: {
            '_token': "{{ csrf_token() }}",
            'video_url': $('#video_url').val(),
            'caption': $('#caption').val(),
            'contributor': $('#contributor').val(),
            'contributor_fee': $('#contributor_fee').val(),
            'tags': $('#tags').val()
        },
        success: function (result) {
            if(result.status == 1){
                var video_id = result.data.video_id;
                var video_title = result.data.title;
                var youtube_id = result.data.youtube_id;
                var caption = result.data.caption;
                var shortcode = '[youtube:{"videoId":"'+video_id+'","youtubeId":"'+youtube_id+'", "caption":"'+caption+'"}]';
                var target = $('.target').val();

                if(target == 'alternateHeader'){
                    // set alternate header type
                    $("."+target+"Type", window.parent.document).val('Video');
                    // set video id
                    $(".article_video_id", window.parent.document).val(video_id);
                    // set video title
                    $("."+target+"Title", window.parent.document).html('Video - '+video_title);
                    // set video caption
                    $("."+target+"Caption", window.parent.document).val(caption);
                    $(".article_video_caption", window.parent.document).val(caption);
                    // hide alternate header selection
                    $("."+target+"Selection", window.parent.document).addClass('hide');
                    // show video container
                    $("."+target+"Container", window.parent.document).removeClass('hide');
                    // display article image to off
                    $("#article_hide_image_status", window.parent.document).trigger('click');
                }else{
                    parent.tinymce.get('content').execCommand('mceInsertContent', false, shortcode);
                }

                $('#toolModal', window.parent.document).trigger('click');
            }else{
                $('#video_url').css('border-color', 'red');
            }
        }
    });
});

$('#external').submit(function(event){
    // cancels the form submission
    event.preventDefault();

    var url = $('#external_url').val();
    var url_id = getYoutubeId(url);
    var caption = $('#external_caption').val();
    var shortcode = '[youtube:{"videoId":"null","youtubeId":"'+url_id+'", "caption":"'+caption+'"}]';

    if(url_id){
        parent.tinymce.get('content').execCommand('mceInsertContent', false, shortcode);
        $('#toolModal', window.parent.document).trigger('click');
    }else{
        var otherVideo = getOtherVideo(url);

        if (otherVideo) {
            var shortcode = '[video:{"videoDomain":"'+otherVideo.domain+'","videoId":"'+otherVideo.id+'", "caption":"'+caption+'"}]';

            parent.tinymce.get('content').execCommand('mceInsertContent', false, shortcode);
            $('#toolModal', window.parent.document).trigger('click');
        } else {
            $('#external_url').css('border-color', 'red');
        }
    }
});

// submit form
$('#save-continue').on('click', function(){
    var action = $('#action').val();
    if(action == '#external') {
        $('input[name="submitExternal"]').trigger('click');
    }else{
        $('input[name="submitInternal"]').trigger('click');
    }
});

getYoutubeId = function(url) {
    var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[2].length == 11) {
        return match[2];
    }
};

getOtherVideo = function(url) {
    var segments = url.replace(/\/$/, "").split( '/' );
    var _domain = segments[2];
    var vId = !$.isNumeric(segments[3]) ? url : segments[segments.length - 1];
    var data = {domain:_domain,id:vId};

    return data;

};
</script>
@endpush
