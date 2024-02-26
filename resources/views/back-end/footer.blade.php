<!-- Footer -->
<div class="footer text-muted">
    @if(App\Settings::where('type', 'copyright')->value('state') == 1) 
        <center> {!! App\Settings::where('type', 'copyright')->value('value') !!} </center>
    @else
        <center>   &copy; 2022 <a target="_blank" href="http://hotspot.microsystem.com.eg">Hotspot</a> by <a href="http://microsystem.com.eg" target="_blank">Microsystem.</a> </center>
    @endif
    
</div>
<!-- /footer -->

