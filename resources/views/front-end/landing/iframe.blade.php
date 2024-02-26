<?php 
$subdomain = url()->full();
$split = explode('/', $subdomain);
?>

<link rel="shortcut icon" href="{{ asset('/') }}upload/photo/faviconlogosmall.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<iframe style="position:absolute; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" src="http://{{ $split[2] }}/indexIframe"></iframe>
