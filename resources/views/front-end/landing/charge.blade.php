<?php $package = App\Models\Packages::where('id',$package_id)->first() ;?>
<center>

    <li>Package name : {{ $package->name }}</li>

    @if($package->type == 1)
    <li>Package period : {{ $package->period }} @if($package->period>1) Months @else Month @endif </li>
    <li>Package Type : Monthly Package</li>
    @elseif($package->type == 2)
    <li>Package period : {{ $package->period }} @if($package->period>1) Days @else Day @endif </li>
    <li>Package Type : Validity Package</li>
    @elseif($package->type == 3)
    <li>Package period : {{ round($package->period/60/60,1) }} @if($package->time_package_expiry>1) Hours @else Hour @endif </li>
    <li>Package Type : Time Package</li>
    <li>Package validity : {{$package->time_package_expiry}} @if($package->time_package_expiry==1)day @else days @endif</li>
    @elseif($package->type == 4)
    <li>Package period : {{ $package->period }} GB </li>
    <li>Package Type : Extra Bandwidth Package</li>
    @endif

    <?php $currency= App\Settings::where('type','currency')->value('value');?>
    <li>Package price : {{ $package->price }}{{$currency}}</li>
    <hr>

    <?php $groups =  App\Groups::where('id', $package->group_id)->first(); ?>
    
    <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>
    @if($package->type==4)
        <li>{{ $package->period }} GB Add to your bandwidth Quota</li>
    @elseif((!isset($groups->quota_limit_upload) || $groups->quota_limit_upload == '0') && (!isset($groups->quota_limit_download) || $groups->quota_limit_download == '0') && (!isset($groups->quota_limit_total) || $groups->quota_limit_total == '0'))
        <li style=color:Green;>Unlimited Quota </li>
    @else
        @if(isset($groups->quota_limit_upload) && $groups->quota_limit_upload !== '0')
            <li>{{ round($groups->quota_limit_upload/1024/1024,0) }} GB Upload Quota</li>
        @endif
        @if(isset($groups->quota_limit_download) && $groups->quota_limit_download !== '0')
            <li>{{ round($groups->quota_limit_download/1024/1024,0) }} GB Download Quota</li>
        @endif
        @if(isset($groups->quota_limit_total) && $groups->quota_limit_total !== '0')
            @if(strlen($groups->quota_limit_total) <= 8)
            <li>{{ round($groups->quota_limit_total/1024/1024,1) }} MB Total Quota</li>
            @else
            <li>{{ round($groups->quota_limit_total/1024/1024/1024,1) }} GB Total Quota</li>
            @endif
        @endif
    @endif

    <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>

    <?php
    unset($finalspeed_donwload2);
    unset($finalspeed_upload2);
    unset($speed_limit);
    unset($limit_speedSplited);
    unset($spilitedUpload);
    unset($spilitedDownload);
    unset($downloadSpeedlimit_uploadSpilited);
    unset($downloadSpeedLimit_downloadSpilited);

    if($package->type!=4){
        if(isset($groups->speed_limit)){
            // get speed limit
            $speed_limit = $groups->speed_limit;
            if($speed_limit and $speed_limit!="0K/0K"){
                $limit_speedSplited = explode("/", $speed_limit);
                if(count($limit_speedSplited)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                {
                    //Browsing speed
                    $spilitedUpload = $limit_speedSplited['1'];
                    $splitedLimit_uploadSpilited= explode(" ", $spilitedUpload);
                    $limit_uploadSpilited=$splitedLimit_uploadSpilited['1'];// upload of equation speed

                    $spilitedDownload = $limit_speedSplited['2'];
                    $splitedlimit_downloadSpilited= explode(" ", $spilitedDownload);
                    $limit_downloadSpilited=$splitedlimit_downloadSpilited['0'];// Download of equation speed

                    //Download speed
                    $downloadSpeedSpilitedUpload = $limit_speedSplited['0'];
                    $downloadSpeedSplitedLimit_uploadSpilited= explode(" ", $downloadSpeedSpilitedUpload);
                    $downloadSpeedlimit_uploadSpilited=$downloadSpeedSplitedLimit_uploadSpilited['0'];// upload of equation speed

                    $downloadSpeedSpilitedDownload = $limit_speedSplited['1'];
                    $downloadSpeedSplitedlimit_downloadSpilited= explode(" ", $downloadSpeedSpilitedDownload);
                    $downloadSpeedLimit_downloadSpilited=$downloadSpeedSplitedlimit_downloadSpilited['0'];// upload of equation speed

                }
                else{// normal speed ex. 128k/512k
                    $downloadSpeedlimit_uploadSpilited = $limit_speedSplited['0'];
                    $downloadSpeedLimit_downloadSpilited = $limit_speedSplited['1'];
                }
                //echo "eeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
            }else{$finalspeed_donwload="Unlimited"; $finalspeed_upload="Unlimited";}
        }

        ?>
        <hr>
        @if(isset($limit_speedSplited) && count($limit_speedSplited)>2)
        <h6>-- Browsing Mode --</h6>
        @if(isset($limit_downloadSpilited))
        <li>Download Speed up to {!! $limit_downloadSpilited !!} </li>
        @else
        <li style=color:Green;>Unlimited download speed </li>
        @endif
        @if(isset($limit_uploadSpilited))
        <li>Upload Speed up to {!! $limit_uploadSpilited !!}</li>
        @else
        <li style=color:Green;>Unlimited upload speed up to</li>
        @endif
        <h6>-- Download Mode --</h6>
        @if(isset($downloadSpeedLimit_downloadSpilited))
        <li style=color:Orange;>Download Speed up to {{ $downloadSpeedLimit_downloadSpilited }} </li>
        @else
        <li style=color:Green;>Unlimited download speed up to</li>
        @endif
        @if(isset($downloadSpeedlimit_uploadSpilited))
        <li style=color:Orange;>Upload Speed up to{{ $downloadSpeedlimit_uploadSpilited }} </li>
        @else
        <li style=color:Green;>Unlimited upload speed </li>
        @endif
        @else
        @if(isset($downloadSpeedLimit_downloadSpilited))
        <li>Download Speed up to {!! $downloadSpeedLimit_downloadSpilited !!} </li>
        @else
        <li style=color:Green;>Unlimited download speed</li>
        @endif

        @if(isset($downloadSpeedlimit_uploadSpilited))
        <li>Upload Speed up to {!! $downloadSpeedlimit_uploadSpilited !!}</li>
        @else
        <li style=color:Green;>Unlimited upload speed up to</li>
        @endif
        @endif

        <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>
        <?php
        unset($finalspeed_donwload2);
        unset($finalspeed_upload2);
        unset($speed_limit);
        unset($limit_speedSplited);
        unset($spilitedUpload);
        unset($spilitedDownload);
        unset($downloadSpeedlimit_uploadSpilited);
        unset($downloadSpeedLimit_downloadSpilited);

        if(isset($groups->if_downgrade_speed) && $groups->if_downgrade_speed == 1){
            // get speed limit
            $speed_limit = $groups->end_speed;
            if($speed_limit and $speed_limit!="0K/0K"){
                $limit_speedSplited = explode("/", $speed_limit);
                if(count($limit_speedSplited)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                {
                    //Browsing speed
                    $spilitedUpload = $limit_speedSplited['1'];
                    $splitedLimit_uploadSpilited= explode(" ", $spilitedUpload);
                    $limit_uploadSpilited=$splitedLimit_uploadSpilited['1'];// upload of equation speed

                    $spilitedDownload = $limit_speedSplited['2'];
                    $splitedlimit_downloadSpilited= explode(" ", $spilitedDownload);
                    $limit_downloadSpilited=$splitedlimit_downloadSpilited['0'];// Download of equation speed

                    //Download speed
                    $downloadSpeedSpilitedUpload = $limit_speedSplited['0'];
                    $downloadSpeedSplitedLimit_uploadSpilited= explode(" ", $downloadSpeedSpilitedUpload);
                    $downloadSpeedlimit_uploadSpilited=$downloadSpeedSplitedLimit_uploadSpilited['0'];// upload of equation speed

                    $downloadSpeedSpilitedDownload = $limit_speedSplited['1'];
                    $downloadSpeedSplitedlimit_downloadSpilited= explode(" ", $downloadSpeedSpilitedDownload);
                    $downloadSpeedLimit_downloadSpilited=$downloadSpeedSplitedlimit_downloadSpilited['0'];// upload of equation speed

                }
                else{// normal speed ex. 128k/512k
                    $downloadSpeedlimit_uploadSpilited = $limit_speedSplited['0'];
                    $downloadSpeedLimit_downloadSpilited = $limit_speedSplited['1'];
                }
                //echo "eeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
            }else{$finalspeed_donwload="Unlimited"; $finalspeed_upload="Unlimited";}
            ?>

            <li style=color:#0a16ff;> After quota finished, internet speed will be downgraded to </li>

            @if(isset($limit_speedSplited) && count($limit_speedSplited)>2)
            <hr>
            <h6>-- Browsing Mode --</h6>
            @if(isset($limit_downloadSpilited))
            <li>Download Speed up to {!! $limit_downloadSpilited !!}</li>
            @else
            <li style=color:Green;>Unlimited download speed </li>
            @endif
            @if(isset($limit_uploadSpilited))
            <li>Upload Speed up to {!! $limit_uploadSpilited !!}</li>
            @else
            <li style=color:Green;>Unlimited upload speed </li>
            @endif
            <h6>-- Download Mode --</h6>
            @if(isset($downloadSpeedLimit_downloadSpilited))
            <li style=color:Orange;>Download Speed up to {{ $downloadSpeedLimit_downloadSpilited }}</li>
            @else
            <li style=color:Green;>Unlimited download speed </li>
            @endif
            @if(isset($downloadSpeedlimit_uploadSpilited))
            <li style=color:Orange;>Upload Speed up to {{ $downloadSpeedlimit_uploadSpilited }}</li>
            @else
            <li style=color:Green;>Unlimited upload speed </li>
            @endif
            @else
            @if(isset($downloadSpeedLimit_downloadSpilited))
            <li>Download Speed up to {!! $downloadSpeedLimit_downloadSpilited !!} </li>
            @else
            <li style=color:Green;>Unlimited download speed </li>
            @endif

            @if(isset($downloadSpeedlimit_uploadSpilited))
            <li>Upload Speed up to {!! $downloadSpeedlimit_uploadSpilited !!}</li>
            @else
            <li style=color:Green;>Unlimited upload speed </li>
            @endif
            @endif

    <?php
        }
    }
    ?>
</center>
<div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
    <a href="{{ url('chargePackage/'.$charge) }}" class="btn btn-primary"> Apply Now</a>
</div>