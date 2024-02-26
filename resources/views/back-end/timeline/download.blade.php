<h6 class="text-semibold"></h6>
@if(isset($id) && $type == "networks")

<div class="row">
	<div class="col-xs-6">
		<a class="btn bg-teal-400 btn-block btn-float btn-float-lg" href="{{ url('network_destinations/'.$id.'-'.'xls') }}"><i class="icon-file-excel"></i> <span>Export worksheet xls</span></a>
	</div>
	
	<div class="col-xs-6">
		<a class="btn bg-purple-300 btn-block btn-float btn-float-lg" href="{{ url('network_destinations/'.$id.'-'.'xlsx') }}"><i class="icon-file-excel"></i> <span>Export workbook xlsx</span></a>
		<!--<button class="btn bg-warning-400 btn-block btn-float btn-float-lg" type="button"><i class="icon-stats-bars"></i> <span>Statistics</span></button>
		<button class="btn bg-blue btn-block btn-float btn-float-lg" type="button"><i class="icon-people"></i> <span>Users</span></button>-->
	</div>
</div>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
@elseif(isset($id) && $type == "groups")

<div class="row">
	<div class="col-xs-6">
		<a class="btn bg-teal-400 btn-block btn-float btn-float-lg" href="{{ url('group_destinations/'.$id.'-'.'xls') }}"><i class="icon-file-excel"></i> <span>Export worksheet xls</span></a>
	</div>
	
	<div class="col-xs-6">
		<a class="btn bg-purple-300 btn-block btn-float btn-float-lg" href="{{ url('group_destinations/'.$id.'-'.'xlsx') }}"><i class="icon-file-excel"></i> <span>Export workbook xlsx</span></a>
		<!--<button class="btn bg-warning-400 btn-block btn-float btn-float-lg" type="button"><i class="icon-stats-bars"></i> <span>Statistics</span></button>
		<button class="btn bg-blue btn-block btn-float btn-float-lg" type="button"><i class="icon-people"></i> <span>Users</span></button>-->
	</div>
</div>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
@elseif(isset($id) && $type == "branches")

<div class="row">
	<div class="col-xs-6">
		<a class="btn bg-teal-400 btn-block btn-float btn-float-lg" href="{{ url('branch_destinations/'.$id.'-'.'xls') }}"><i class="icon-file-excel"></i> <span>Export worksheet xls</span></a>
	</div>
	
	<div class="col-xs-6">
		<a class="btn bg-purple-300 btn-block btn-float btn-float-lg" href="{{ url('branch_destinations/'.$id.'-'.'xlsx') }}"><i class="icon-file-excel"></i> <span>Export workbook xlsx</span></a>
		<!--<button class="btn bg-warning-400 btn-block btn-float btn-float-lg" type="button"><i class="icon-stats-bars"></i> <span>Statistics</span></button>
		<button class="btn bg-blue btn-block btn-float btn-float-lg" type="button"><i class="icon-people"></i> <span>Users</span></button>-->
	</div>
</div>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>

@else
<div class="alert alert-danger no-border">
	<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
	<span class="text-semibold">Sorry!</span> @if($type == "networks") Network  @elseif($type == "groups") Group @elseif($type == "branches") Branch @endif don't have activity</a>.
</div>
<script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>

@endif
<script type="text/javascript">
	
</script>