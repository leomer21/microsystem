<!-- Info blocks -->
<div class="row">
    @foreach($packages as $package)
    <?php $groups =  App\Groups::where('id', $package->group_id)->first(); ?>
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-body text-center">
                <script>
                    // Custom color
                    $('[data-popup=tooltip-custom]').tooltip({
                        template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
                    });
                    // Toggle method
                    $('#toggle-tooltip-method-{{ $package->id }}').on('click', function() {
                        $('#toggle-tooltip-method-target-{{ $package->id }}').tooltip('toggle')
                    });
                </script>
                <div class="icon-object border-success-400 text-success" id="toggle-tooltip-method-target-{{ $package->id }}" data-popup="popover" data-placement="left" data-trigger="hover" title='<center> <strong>Package</strong> <i>type</i>
                    @if($package->type == 1)
                        <p style=color:blue;>Monthly Package</p>
                        @elseif($package->type == 2)
                        <p style=color:blue;>Validity Package</p>
                        @elseif($package->type == 3)
                        <p style=color:blue;>Time Package</p>
                        <p style=color:blue;>Package validity : {{$package->time_package_expiry}} @if($package->time_package_expiry==1)day @else days @endif</p>
                        @elseif($package->type == 4)
                        <p style=color:blue;>Extra Bandwidth Package</p>
                        @endif

                    @if($package->type==4)
                        <p class="mb-10">{{ $package->period }} GB bandwidth Quota</p>
                    @elseif((!isset($groups->quota_limit_upload) || $groups->quota_limit_upload == '0') && (!isset($groups->quota_limit_download) || $groups->quota_limit_download == '0') && (!isset($groups->quota_limit_total) || $groups->quota_limit_total == '0'))
                        <p class="mb-10" style=color:Green;>Unlimited Quota </p>
                    @else
                        @if(isset($groups->quota_limit_upload) && $groups->quota_limit_upload !== '0')
                           <p class="mb-10">{{ round($groups->quota_limit_upload/1024/1024,0) }} GB Upload Quota</p>
                        @endif
                        @if(isset($groups->quota_limit_download) && $groups->quota_limit_download !== '0')
                           <p class="mb-10">{{ round($groups->quota_limit_download/1024/1024,0) }} GB Download Quota</p>
                        @endif
                        @if(isset($groups->quota_limit_total) && $groups->quota_limit_total !== '0')
                            @if(strlen($groups->quota_limit_total) <= 8)
                            <p class="mb-10">{{ round($groups->quota_limit_total/1024/1024,1) }} MB Total Quota</p>
                            @else
                            <p class="mb-10">{{ round($groups->quota_limit_total/1024/1024/1024,1) }} GB Total Quota</p>
                            @endif
                        @endif
                    @endif
                <?php
                  if($package->type!=4){
                      if(isset($groups->speed_pmit)){
                          // get speed pmit
                          $speed_pmit = $groups->speed_pmit;
                          if($speed_pmit and $speed_pmit!="0K/0K"){
                              $pmit_speedSppted = explode("/", $speed_pmit);
                              if(count($pmit_speedSppted)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                              {
                                  //Browsing speed
                                  $spiptedUpload = $pmit_speedSppted['1'];
                                  $spptedpmit_uploadSpipted= explode(" ", $spiptedUpload);
                                  $pmit_uploadSpipted=$spptedpmit_uploadSpipted['1'];// upload of equation speed

                                  $spiptedDownload = $pmit_speedSppted['2'];
                                  $spptedpmit_downloadSpipted= explode(" ", $spiptedDownload);
                                  $pmit_downloadSpipted=$spptedpmit_downloadSpipted['0'];// Download of equation speed

                                  //Download speed
                                  $downloadSpeedSpiptedUpload = $pmit_speedSppted['0'];
                                  $downloadSpeedSpptedpmit_uploadSpipted= explode(" ", $downloadSpeedSpiptedUpload);
                                  $downloadSpeedpmit_uploadSpipted=$downloadSpeedSpptedpmit_uploadSpipted['0'];// upload of equation speed

                                  $downloadSpeedSpiptedDownload = $pmit_speedSppted['1'];
                                  $downloadSpeedSpptedpmit_downloadSpipted= explode(" ", $downloadSpeedSpiptedDownload);
                                  $downloadSpeedpmit_downloadSpipted=$downloadSpeedSpptedpmit_downloadSpipted['0'];// upload of equation speed

                              }
                              else{// normal speed ex. 128k/512k
                                  $downloadSpeedpmit_uploadSpipted = $pmit_speedSppted['0'];
                                  $downloadSpeedpmit_downloadSpipted = $pmit_speedSppted['1'];
                              }
                              //echo "eeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
                          }else{$finalspeed_donwload="Unlmited"; $finalspeed_upload="Unlmited";}
                      }

                      ?>
                      @if(isset($pmit_speedSppted) && count($pmit_speedSppted)>2)
                      <h6>-- Browsing Mode --</h6>
                      @if(isset($pmit_downloadSpipted))
                      <p>{!! $pmit_downloadSpipted !!}  Download Speed</p>
                      @else
                      <p style=color:Green;>Unlmited download speed </p>
                      @endif
                      @if(isset($pmit_uploadSpipted))
                      <p>{!! $pmit_uploadSpipted !!}  Upload Speed</p>
                      @else
                      <p style=color:Green;>Unlmited upload speed </p>
                      @endif
                      <h6>-- Download Mode --</h6>
                      @if(isset($downloadSpeedpmit_downloadSpipted))
                      <p style=color:Orange;> {{ $downloadSpeedpmit_downloadSpipted }} Download Speed</p>
                      @else
                      <p style=color:Green;>Unlmited download speed </p>
                      @endif
                      @if(isset($downloadSpeedpmit_uploadSpipted))
                      <p style=color:Orange;> {{ $downloadSpeedpmit_uploadSpipted }} Upload Speed</p>
                      @else
                      <p style=color:Green;>Unlmited upload speed </p>
                      @endif
                      @else
                      @if(isset($downloadSpeedpmit_downloadSpipted))
                      <p>{!! $downloadSpeedpmit_downloadSpipted !!} Download Speed</p>
                      @else
                      <p style=color:Green;>Unlmited download speed </p>
                      @endif

                      @if(isset($downloadSpeedpmit_uploadSpipted))
                      <p>{!! $downloadSpeedpmit_uploadSpipted !!} Upload Speed</p>
                      @else
                      <p style=color:Green;>Unlmited upload speed </p>
                      @endif
                      @endif

                      <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>
                      <?php

                      if(isset($groups->if_downgrade_speed) && $groups->if_downgrade_speed == 1){
                          // get speed pmit
                          $speed_pmit = $groups->end_speed;
                          if($speed_pmit and $speed_pmit!="0K/0K"){
                              $pmit_speedSppted = explode("/", $speed_pmit);
                              if(count($pmit_speedSppted)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                              {
                                  //Browsing speed
                                  $spiptedUpload = $pmit_speedSppted['1'];
                                  $spptedpmit_uploadSpipted= explode(" ", $spiptedUpload);
                                  $pmit_uploadSpipted=$spptedpmit_uploadSpipted['1'];// upload of equation speed

                                  $spiptedDownload = $pmit_speedSppted['2'];
                                  $spptedpmit_downloadSpipted= explode(" ", $spiptedDownload);
                                  $pmit_downloadSpipted=$spptedpmit_downloadSpipted['0'];// Download of equation speed

                                  //Download speed
                                  $downloadSpeedSpiptedUpload = $pmit_speedSppted['0'];
                                  $downloadSpeedSpptedpmit_uploadSpipted= explode(" ", $downloadSpeedSpiptedUpload);
                                  $downloadSpeedpmit_uploadSpipted=$downloadSpeedSpptedpmit_uploadSpipted['0'];// upload of equation speed

                                  $downloadSpeedSpiptedDownload = $pmit_speedSppted['1'];
                                  $downloadSpeedSpptedpmit_downloadSpipted= explode(" ", $downloadSpeedSpiptedDownload);
                                  $downloadSpeedpmit_downloadSpipted=$downloadSpeedSpptedpmit_downloadSpipted['0'];// upload of equation speed

                              }
                              else{// normal speed ex. 128k/512k
                                  $downloadSpeedpmit_uploadSpipted = $pmit_speedSppted['0'];
                                  $downloadSpeedpmit_downloadSpipted = $pmit_speedSppted['1'];
                              }
                              //echo "eeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
                          }else{$finalspeed_donwload="Unlmited"; $finalspeed_upload="Unlmited";}
                          ?>
                          <span class="text-semibold text-blue-800">After quota finished, internet speed will be downgraded to</span>

                          @if(isset($pmit_speedSppted) && count($pmit_speedSppted)>2)

                          <h6>-- Browsing Mode --</h6>
                          @if(isset($pmit_downloadSpipted))
                          <p>{!! $pmit_downloadSpipted !!}  Download Speed</p>
                          @else
                          <p style=color:Green;>Unlmited download speed </p>
                          @endif
                          @if(isset($pmit_uploadSpipted))
                          <p>{!! $pmit_uploadSpipted !!}  Upload Speed</p>
                          @else
                          <p style=color:Green;>Unlmited upload speed </p>
                          @endif
                          <h6>-- Download Mode --</h6>
                          @if(isset($downloadSpeedpmit_downloadSpipted))
                          <p style=color:Orange;> {{ $downloadSpeedpmit_downloadSpipted }} Download Speed</p>
                          @else
                          <p style=color:Green;>Unlmited download speed </p>
                          @endif
                          @if(isset($downloadSpeedpmit_uploadSpipted))
                          <p style=color:Orange;> {{ $downloadSpeedpmit_uploadSpipted }} Upload Speed</p>
                          @else
                          <p style=color:Green;>Unlmited upload speed </p>
                          @endif
                          @else
                          @if(isset($downloadSpeedpmit_downloadSpipted))
                          <p>{!! $downloadSpeedpmit_downloadSpipted !!} Download Speed</p>
                          @else
                          <p style=color:Green;>Unlmited download speed </p>
                          @endif

                          @if(isset($downloadSpeedpmit_uploadSpipted))
                          <p>{!! $downloadSpeedpmit_uploadSpipted !!} Upload Speed</p>
                          @else
                          <p style=color:Green;>Unlmited upload speed </p>
                          @endif
                          @endif

                  <?php
                      }
                  }
                ?></center>' data-html="true"><a herf="#"></a><i class="icon-cash4"></i></div>
                <h5 class="text-semibold"><a herf="#" id="toggle-tooltip-method-{{ $package->id }}">{{ $package->name }}</a></h5>
                <p class="mb-15">{{ $package->price }} {{ App\Settings::where('type', 'currency')->value('value') }}</p>

                @if($package->type==4)
                    <p class="mb-10">{{ $package->period }} GB bandwidth Quota</p>
                @elseif((!isset($groups->quota_limit_upload) || $groups->quota_limit_upload == '0') && (!isset($groups->quota_limit_download) || $groups->quota_limit_download == '0') && (!isset($groups->quota_limit_total) || $groups->quota_limit_total == '0'))
                    <p class="mb-10" style=color:Green;>Unlimited Quota </p>
                @else
                    @if(isset($groups->quota_limit_total) && $groups->quota_limit_total !== '0')
                        @if(strlen($groups->quota_limit_total) <= 8)
                        <p class="mb-10">{{ round($groups->quota_limit_total/1024/1024,1) }} MB Total Quota</p>
                        @else
                        <p class="mb-10">{{ round($groups->quota_limit_total/1024/1024/1024,1) }} GB Total Quota</p>
                        @endif
                    @endif
                @endif
                <a href="{{ url('chargePackages/'. $id .'/'. $package->id .'/1/'. Auth::user()->id ) }}" class="btn bg-success-400">Charage Package</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<script type="text/javascript" src="assets/js/core/app.js"></script>
<script type="text/javascript" src="assets/js/pages/components_popups.js"></script>
