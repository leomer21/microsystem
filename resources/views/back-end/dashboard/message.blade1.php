<?php $counter = 0; ?>

<form method="POST" action="{{ url('reply') }}" id="send">
{{ csrf_field() }}
<div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right">
    @foreach($message as $mess)
    <?php $counter ++;?>
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a  class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-{{ $mess['id'] }}">
                {{ $mess['name'] }}</a>
            </h6>
        </div>
        
        <div id="accordion-control-right-{{ $mess['id'] }}" @if($message_count == $counter) class="panel-collapse collapse in" @else class="panel-collapse collapse" @endif>
            <div class="panel-body">
            	<ul class="media-list content-group-lg stack-media-on-mobile">
					<li class="media">
						<div class="media-left">
							<a href="#"><img src="assets/images/profile.png" class="img-circle img-sm" alt=""></a>
						</div>

						<div class="media-body">
							<div class="media-heading">
								<a href="#" class="text-semibold">{{ $mess['name'] }}</a>
								<span class="media-annotation dotted">{{ $mess['created_at']->diffForHumans() }}</span>
							</div>
							<input type="hidden" name="admin" value="{{ Auth::user()->id }}">
							<input type="hidden" name="parent" value="{{ $getMasterID['id'] }}">
							<input type="hidden" name="user" value="{{ $mess['u_id'] }}">
							<input type="hidden" name="name" value="{{ $mess['name'] }}">



							<p>{!! $mess['message'] !!}.</p>
							<ul class="list-inline list-inline-separate text-size-small">
								<li><a href="tel:{{ $mess['phone'] }}"><i class="icon-phone2 text-success"></i></a>{{ $mess['phone'] }} <a href="mailto:{{ $mess['email'] }}"><i class="icon-mail-read text-danger"></i></a> {{ $mess['email'] }}</li>
								<li><a data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2" >Reply</a></li>
								<li><a href="#" onclick="_delete({{ $mess['id'] }})">Delete</a></li>
								<li><a href="#" onclick="_ignore({{ $mess['id'] }})">Ignore</a></li>
							</ul>
						</div>
					</li>
				</ul>	
				            			
            </div>
        </div>
        
    </div>
    @endforeach
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2">Reply message</a>
            </h6>
        </div>
        <div id="accordion-control-right-group2" class="panel-collapse collapse">
            <div class="panel-body">
				<div class="content-group">
					<textarea name="message" cols="18" rows="18" class="wysihtml5 wysihtml5-min form-control"></textarea>
				</div>
            </div>
        </div>
    </div>
</div>

</form>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>

<script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
		<script>
				
			function _delete(id, that) {
		        $td_edit = $(that);

		        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
		        $.ajax({
		            url: 'delete_message/' + id,
		            success: function(response)
		            {
	                new PNotify({
                            title: 'Success',
                            text: 'Message Has been deleted.',
                            addclass: 'bg-success',
                            buttons: {
                                closer_hover: false,
                                sticker_hover: false
                            }
                        });
		            }
		        });
		    }
		    function _ignore(id, that) {
		        $td_edit = $(that);

		        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
		        $.ajax({
		            url: 'ignore_message/' + id,
		            success: function(response)
		            {
	                new PNotify({
                            title: 'Success',
                            text: 'Message has been Ignored.',
                            addclass: 'bg-success',
                            buttons: {
                                closer_hover: false,
                                sticker_hover: false
                            }
                        });
		            }
		        });
		    }
			// Simple toolbar
			$('.wysihtml5-min').wysihtml5({
				parserRules:  wysihtml5ParserRules,
				stylesheets: ["assets/css/components.css"],
				"font-styles": true, // Font styling, e.g. h1, h2, etc. Default true
				"emphasis": true, // Italics, bold, etc. Default true
				"lists": true, // (Un)ordered lists, e.g. Bullets, Numbers. Default true
				"html": false, // Button which allows you to edit the generated HTML. Default false
				"link": true, // Button to insert a link. Default true
				"image": false, // Button to insert an image. Default true,
				"action": false, // Undo / Redo buttons,
				"color": true // Button to change color of font
			});
			// Basic editors
			// ------------------------------

			// Default initialization
			$('.summernote').summernote();


			// Control editor height
			$('.summernote-height').summernote({
			    height: 200
			});


			// Air mode
			$('.summernote-airmode').summernote({
			    airMode: true
			});

				

			// Click to edit
			// ------------------------------

			// Edit
			$('#edit').on('click', function() {
			    $('.click2edit').summernote({focus: true});
			})

			// Save
			$('#save').on('click', function() {
			    var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
			    $('.click2edit').destroy();
			})



			// Related form components
			// ------------------------------

			// Styled checkboxes/radios
			$(".link-dialog input[type=checkbox], .note-modal-form input[type=radio]").uniform({
			    radioClass: 'choice'
			});


			// Styled file input
			$(".note-image-input").uniform({
			    fileButtonClass: 'action btn bg-warning-400'
			});
	</script>