{{ Form::open(array('url' => 'landing_edit', 'files' => true, 'method' => 'post', 'id' => 'edit')) }}
<div class="form-group">
    <input type="hidden" name="template" value="{{$template}}" >
    @if($template == "branch_landing")
        <input type="hidden" name="branch_id" value="{{$branch_id}}">
        <div class="alert alert-info alert-styled-left alert-bordered">
            <center>Update landing page of <span class="text-semibold">{{$branch_name}}</span> branch</center>
        </div>
    @else
        <div class="alert alert-info alert-styled-left alert-bordered">
            <center>Updateing <span class="text-semibold">default</span> landing page</center>
        </div>
    @endif
    <label class="col-lg-2 control-label text-semibold">Multiple file upload:</label>
    <div class="col-lg-10">
        <input type="file" class="file-input-preview" multiple="multiple" name="file[]">
        <span class="help-block">.</span>
    </div>
</div>

<br>
<br>    
{{ Form::close() }}
<script type="text/javascript">
        
$(function() {

    //
    // Define variables
    //

    // Modal template
    var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
        fullscreen: 'btn btn-default btn-icon btn-xs',
        borderless: 'btn btn-default btn-icon btn-xs',
        close: 'btn btn-default btn-icon btn-xs'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross3"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: 'btn btn-link btn-xs btn-icon',
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'btn btn-link btn-xs btn-icon',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: 'btn btn-link btn-icon btn-xs',
        removeIcon: '<i class="icon-trash"></i>',
        indicatorNew: '<i class="icon-file-plus text-slate"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };

    //
    // Always display preview
    //
    $(".file-input-preview").fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
        },
        initialPreview: [
            @foreach($data as $value)
            "<img src='{{ asset('') }}upload/media/{{ $value->file }}' alt='' style='width:auto;height:160px;'>",
            @endforeach
        ],
        initialPreviewAsData: true,
        overwriteInitial: true,
        maxFileSize: 5120,
        allowedFileExtensions: ["jpg", "jpeg", "gif", "png"],
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings
    });

});    
</script>