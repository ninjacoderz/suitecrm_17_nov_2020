<!-- Generic page styles -->
<link rel="stylesheet" href="custom/include/SugarFields/Fields/Multiupload/css/style.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="custom/include/SugarFields/Fields/Multiupload/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="custom/include/SugarFields/Fields/Multiupload/css/jquery.fileupload.css">
<link rel="stylesheet" href="custom/include/SugarFields/Fields/Multiupload/css/jquery.fileupload-ui.css">

<div class="">
    <br>
    <!-- The file upload form used as target for the file upload widget -->
    <div id="fileupload" action="" method="POST" enctype="multipart/form-data">

        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="resize_all" style="background:green;" class="btn btn-primary">
                    <i class="glyphicon glyphicon-collapse-down"></i>
                    <span>Resize All</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <button type="button" class="button primary convert_img">
                    <i class="glyphicon glyphicon-refresh"></i>
                    <span>HEIC to JPG</span>
                </button>
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </div>
    <br>
</div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

{if strlen({{sugarvar key='value' string=true}}) <= 0}
    {assign var="value" value= "" }
{else}
    {assign var="value" value={{sugarvar key='value' string=true}} }
{/if}

<input type="hidden" name='{{sugarvar key='name'}}' value='{$value}' />

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="custom/include/SugarFields/Fields/Multiupload/js/main.js"></script>
<!-- The heic application script -->
<script src="custom/include/heic2any/heic2any.js"></script>