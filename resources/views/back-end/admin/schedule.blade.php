

<div class="panel panel-flat">
    <div class="panel-heading">
        <h6 class="panel-title">My schedule</h6>
    </div>

    <div class="panel-body">
        <div class="schedule"></div>
    </div>
</div>
<script>
// Schedule
   // ------------------------------
   <?php $todayDateTime = Carbon\Carbon::now(); ?>
   <?php
   $currency=App\Settings::where('type','currency')->value('value');
   ?>
   @if(Auth::user()->type == 2)
       // Add events
       <?php $reseller_events = App\History::where('reseller_id',Auth::user()->id)->get(); ?>
       var eventsColors = [
           @foreach($reseller_events as $events)
           {
               @if($events->operation=="reseller_charge_package")
                   title: 'Charged Package {{$events->package_price}}{{$currency}} for user {{App\Users::where('u_id',$events->u_id)->value('u_name')}}.',
                   start: '{{ $events->add_date }} {{ $events->add_time }}',
                   color: '#ffa800'
                   ,desc: ''
               @endif

               @if($events->operation=="reseller_cards_package")
                   <?php
                   $cardPackageDetails=$events->details;
                   $cardPackageDetailsExplode=explode(";",$cardPackageDetails);
                   $PackageNoOfCards=$cardPackageDetailsExplode[1]-$cardPackageDetailsExplode[0];
                   ?>
                   title: 'Add Card Package :{{$PackageNoOfCards}} cards, From serial:{{$cardPackageDetailsExplode[0]}} To serial:{{$cardPackageDetailsExplode[1]}}.',
                   start: '{{ $events->add_date }} {{ $events->add_time }}',
                   color: '#ff00f0'
                   ,desc: ''
               @endif

               @if($events->operation=="reseller_payment")
                   title: 'Make a payment {{$events->details}}{{$currency}}',
                   start: '{{ $events->add_date }} {{ $events->add_time }}',
                   color: '#ff0000'
                   ,desc: ''
               @endif

               @if($events->operation=="reseller_credit")
                   title: 'Add Credit {{$events->details}}{{$currency}}',
                   start: '{{ $events->add_date }} {{ $events->add_time }}',
                   color: '#4bdb3c'
                   ,desc: ''
               @endif

           },
           @endforeach
       ];
   @else
       // Add events
       <?php $admin_events = App\History::where('a_id',Auth::user()->id)->get(); ?>
       var eventsColors = [
           @foreach($admin_events as $events)
           {
               title: 'Long Event',
               start: '{{ $events->add_date }}',
               color: '#26A69A'
           },
           @endforeach
       ];
   @endif

   // Initialize calendar with options
   $('.schedule').fullCalendar({
       header: {
           left: 'prev,next today',
           center: 'title',
           right: 'month,agendaWeek,agendaDay'
       },
       defaultDate: '{{ $todayDateTime->toDateString() }}',
       editable: false,
       events: eventsColors,
       eventClick:  function(event, jsEvent, view) {
           new PNotify({
               title: event.title,
               text: event.desc,
               icon: 'icon-cash4'
           });
       }
   });


   // Render in hidden elements
   $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
       $('.schedule').fullCalendar('render');
   });

</script>