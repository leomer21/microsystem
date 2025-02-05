@extends('..back-end.dashboard.charts')
<?php 
// foreach(App\Branches::where('state','1')->get() as $branch){
if(isset($branch->id)){
    $dashboardType=App\Settings::where(['type'=>'dashboard_type','state'=>Auth::user()->id])->first();
    
    if( !( isset($dashboardType) ) or  ( isset($dashboardType) and ($dashboardType->value=="all" or $dashboardType->value=="internetManagement" or $dashboardType->value=="branchid" or $dashboardType->value=="marketing") ) )
    {
        if($dashboardType->value=="branchid"){
            $viewSelectedBranches=$dashboardType->state;
            $branches = App\Branches::where('state','1')->where('id',$viewSelectedBranches)->get();
        }else{
                $viewAllBranches=1;
                $branches = App\Branches::where('state','1')->get();
            }
         // check if this month is running and have data or not
         $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get();
         if(isset($branchMonths) and count($branchMonths)>0){
            $branchMonthsCounter=count($branchMonths);
            $monitorInternetUsage=1;
           // $helicopterViewOnInternet=1; //LOAD 0.25 ~ LOAD 0.50 or not Second
            $timeTracker=1; //Marketing LOAD 10.5 seconds -> 0.25:0.50
            $monitorOnlineUsers=1;
            $returningVisitorsWeekly=1; //Marketing LOAD 2 seconds
            $returningVisitorsMonhly=1; //Marketing lOAD 2 seconds
         }

        if($dashboardType->value=="internetManagement"){// unset all marketing analytics
            unset($timeTracker);
            unset($returningVisitorsWeekly);
            unset($returningVisitorsMonhly);
        }
        if($dashboardType->value=="marketing"){// unset all techinial analytics
            unset($monitorInternetUsage);
            unset($helicopterViewOnInternet);
            unset($monitorOnlineUsers);
        }
    }
    else{$branches=[];}
	$counter=0;
    
?>
@section('var')

	<?php
	foreach($branches as $branche){
		$branche_name = $branche->name;
		$counter++;
	?>
    @if(isset($monitorInternetUsage)) var monitor_internet_usage_<?php echo $counter;?> = ec.init(document.getElementById('monitor_internet_usage_<?php echo $counter;?>'), limitless); @endif
    @if(isset($helicopterViewOnInternet)) var helicopter_view_on_internet_traffic_<?php echo $counter;?> = ec.init(document.getElementById('helicopter_view_on_internet_traffic_<?php echo $counter;?>'), limitless);  @endif
    @if(isset($timeTracker)) var time_tracker_<?php echo $counter;?> = ec.init(document.getElementById('time_tracker_<?php echo $counter;?>'), limitless);  @endif
    @if(isset($monitorOnlineUsers)) var monitor_online_users_<?php echo $counter;?> = ec.init(document.getElementById('monitor_online_users_<?php echo $counter;?>'), limitless); @endif

    @if(isset($returningVisitorsWeekly)) var returning_visitors_weekly_<?php echo $counter;?> = ec.init(document.getElementById('returning_visitors_weekly_<?php echo $counter;?>'), limitless); @endif
    @if(isset($returningVisitorsMonhly)) var returning_visitors_monthly_<?php echo $counter;?> = ec.init(document.getElementById('returning_visitors_monthly_<?php echo $counter;?>'), limitless); @endif

	<?php } ?>
@endsection
@section('data')
	<?php
	$counter=0;	
	foreach($branches as $branche){
		$branche_name = $branche->name;
		$branche_limit = $branche->monthly_quota;
        $startQuotaDay = $branche->start_quota;
        if(!$startQuotaDay or !isset ($startQuotaDay) or $startQuotaDay==0){$startQuotaDay="01";}
        elseif($startQuotaDay>=1 and $startQuotaDay<=9){$startQuotaDay="0".$startQuotaDay;}
		$counter++;

	?>


	<!-- Monitor internet usage -->
    @if(isset($monitorInternetUsage))
	<!-- -------------------------------------------------------------------------------------------------------------- -->
    <?php

    //$f = new DateTime('first day of this month');
    //$startMonth=$f->format('Y-m-d');
    //$l = new DateTime('last day of this month');
    //$endMonth=$l->format('Y-m-d');

/*
    $startMonth=date("Y-m")."-".$startQuotaDay;
    $endMonth = date('Y-m-d', strtotime('+1 month', strtotime($startMonth)));
    $radacct = App\Models\UsersRadacct::where('branch_id',$branche->id)->whereBetween('dates',[$startMonth, $endMonth])->get();
    if(isset($radacct)){
        $totalUpload=0;
        $totalDownload=0;
        $totalQuota=0;
        foreach($radacct as $currRadacct){
            //$totalUpload+=$currRadacct->acctinputoctets;
            //$totalDownload+=$currRadacct->acctoutputoctets;
            $totalQuota += ($currRadacct->acctinputoctets + $currRadacct->acctoutputoctets);
        }
        //$totalUpload=round($totalUpload/1024/1024/1024,1);
        //$totalDownload=round($totalDownload/1024/1024/1024,1);
        if(isset($totalQuota) and $totalQuota!=0){
            $totalQuota=round($totalQuota/1024/1024/1024,1);
            $percentage=round(($totalQuota/$branche_limit)*100,1);

        }
    }
*/
    ////////////////////////////////////

        //$f = new DateTime('first day of this month');
        //$startMonth = $f->format('Y-m-d');
        //$l = new DateTime('last day of this month');
        //$endMonth = $l->format('Y-m-d');

        $currDay=date('d');
        if($currDay<$startQuotaDay){
            // get last month
            $endMonth=date("Y-m")."-".$startQuotaDay;
            $startMonth = date('Y-m-d', strtotime('-1 month', strtotime($endMonth)));
        }else{
            // get next month
            $startMonth=date("Y-m")."-".$startQuotaDay;
            $endMonth = date('Y-m-d', strtotime('+1 month', strtotime($startMonth)));
        }
        

        $radacct = App\Radacct::where('branch_id', $branche->id)->whereBetween('dates', [$startMonth, $endMonth])->get();
        if (isset($radacct)) {
            $totalUpload = 0;
            $totalDownload = 0;
            $totalQuota = 0;
            foreach ($radacct as $currRadacct) {
                //$totalUpload+=$currRadacct->acctinputoctets;
                //$totalDownload+=$currRadacct->acctoutputoctets;
                $totalQuota += ($currRadacct->acctinputoctets + $currRadacct->acctoutputoctets);
            }
            //$totalUpload=round($totalUpload/1024/1024/1024,1);
            //$totalDownload=round($totalDownload/1024/1024/1024,1);
            $totalQuota = round($totalQuota / 1024 / 1024 / 1024, 1);
            $percentage = round(($totalQuota / $branche_limit) * 100, 1);

            //$percentage = $percentage . ";" . $totalQuota . "GB of " . $branche_limit . "GB";
            }
    

    ///////////////////////////////////

    if(!isset($percentage)){
        $percentage = 0;
    }

    ?>
	monitor_internet_usage_<?php echo $counter;?>_options = {

		// Add title
		title: {
			text: 'Follow Quota usage',
			subtext: 'Real time',
			x: 'center'
		},

		// Add tooltip
		tooltip: {
			formatter: "{a} <br/>{b} : {c}%"
		},

		// Add series
		series: [
			{
				name: 'Memory usage',
				type: 'gauge',
				center: ['50%', '55%'],
				startAngle: 150,
				endAngle: -150,

				// Axis line
				axisLine: {
					lineStyle: {
						color: [[0.2, 'lightgreen'], [0.4, 'orange'], [0.8, 'skyblue'], [1, '#ff4500']],
						width: 30
					}
				},

				// Axis tick
				axisTick: {
					splitNumber: 5,
					length: 5,
					lineStyle: {
						color: '#fff'
					}
				},

				// Axis text label
				axisLabel: {
					formatter: function(v) {
						switch (v+''){
							case '10': return 'Idle';
							case '30': return 'Low';
							case '60': return 'Normal';
							case '90': return 'High';
							default: return '';
						}
					}
				},

				// Split line
				splitLine: {
					length: 35,
					lineStyle: {
						color: '#fff'
					}
				},

				// Display title
				title: {
					offsetCenter: ['-81%', -15],
					textStyle: {
						fontSize: 13
					}
				},

				// Display details info
				detail: {
					offsetCenter: ['-80%', -5],
					formatter: '{value}%',
					textStyle: {
						fontSize: 25
					}
				},

				// Add data
				data: [{value: {{$percentage}}, name: 'Usage'}]
			}
		]
	};
	
	
  

	// Add random data
	clearInterval(timeTicket<?php echo $counter;?>);
	var timeTicket<?php echo $counter;?> = setInterval(function () {
		$.ajax({url:"quotaUsageNow/{{$branche->id}}",type: "get",dataType: "html",success:function(data){ 
			var res = data.split(";");
			monitor_internet_usage_<?php echo $counter;?>_options.series[0].data[0].value =  res[0];
			monitor_internet_usage_<?php echo $counter;?>_options.series[0].data[0].name =   res[1]; }});
		
		monitor_internet_usage_<?php echo $counter;?>.setOption(monitor_internet_usage_<?php echo $counter;?>_options, true);
	}, 7000)


@endif
<!--  --------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
@if(isset($timeTracker))
<!--   Time tracker -->

            <?php
            $year=date("Y");
            /* for next update will delete 132 db query and replace them with the following code
            $firstDayInYear=date("Y")."-01-01";
            $lastDayInYear=date("Y")."-12-30";
            $getCurrentYearStatistics=App\Models\UsersRadacct::where('branch_id',$branche->id)->whereBetween('dates',[$firstDayInYear,$lastDayInYear])->get();
            if(isset($getCurrentYearStatistics))
            {
                foreach($getCurrentYearStatistics as $currentYear){
                    if($currentYear->dates>="2016-01-01" and $currentYear->dates<="2016-01-30")
                    {

                    }
                }
            }
            */

            ?>
            var idx = 1;
            time_tracker_<?php echo $counter;?>_options = {

                // Add timeline
                timeline: {
                    x: 10,
                    x2: 10,
                    data: [
                         <?php 
                            $justCounter2=0;
                            foreach($branchMonths as $currMonth)
                            {
                                $justCounter2++;
                                echo "'".$currMonth->month."-01'";
                                if($justCounter2!=$branchMonthsCounter){echo ",";}
                            }
                        ?>
                    ],
                    label: {
                        formatter: function(s) {
                            return s.slice(0, 7);
                        }
                    },
                    autoPlay: true,
                    playInterval: 3000
                },

                // Set options
                options: [
                    {

                        // Add title
                        title: {
                            text: 'Time Segmentations',
                            subtext: 'Know more about your customers happiness',
                            x: 'center'
                        },
                        // Enable drag recalculate
                        calculable: true,
                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            formatter: "{a} <br/>{b}: {c} ({d}%)"
                        },

                        // Add legend
                        legend: {
                            x: 'left',
                            orient: 'vertical',
                            data: ['Less 5 Min','5 - 15 Min','15 - 30 Min','30 - 45 Min','45 - 60 Min','1 - 2 Hour','2 - 3 Hour','3 - 4 Hour','4 - 5 Hour','5 - 6 Hour','More than 6 Hours']
                        },

                        // Display toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: false,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        pie: 'Switch to pies',
                                        funnel: 'Switch to funnel',
                                    },
                                    type: ['pie', 'funnel'],
                                    option: {
                                        funnel: {
                                            x: '25%',
                                            width: '50%',
                                            funnelAlign: 'left',
                                            max: 1700
                                        }
                                    }
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },

                        // Add series
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            center: ['50%', '50%'],
                            radius: '60%',
                            <?php 
                            $justCounter4=0;
                            foreach($branchMonths as $currMonth)
                            {
                                $justCounter4++;
                                if($justCounter4==1)
                                {
                            ?>
                            
                            <?php
                            $MonthData=App\Models\UsersRadacct::whereBetween('dates',["$currMonth->month-01","$currMonth->month-31"])->where('branch_id',$branche->id)->get();
                            $less5Min=0;
                            $from5_15Min=0;
                            $from15_30Min=0;
                            $from30_45Min=0;
                            $from45_60Min=0;
                            $from1_2h=0;
                            $from2_3h=0;
                            $from3_4h=0;
                            $from4_5h=0;
                            $from5_6h=0;
                            $moreThan6=0;
                            foreach($MonthData as $record){
                                if($record->acctsessiontime >=0 and $record->acctsessiontime < 300){$less5Min++;}
                                if($record->acctsessiontime >=300 and $record->acctsessiontime < 900){$from5_15Min++;}
                                if($record->acctsessiontime >=900 and $record->acctsessiontime < 1800){$from15_30Min++;}
                                if($record->acctsessiontime >=1800 and $record->acctsessiontime < 2700){$from30_45Min++;}
                                if($record->acctsessiontime >=2700 and $record->acctsessiontime < 3600){$from45_60Min++;}
                                if($record->acctsessiontime >=3600 and $record->acctsessiontime < 7200){$from1_2h++;}
                                if($record->acctsessiontime >=7200 and $record->acctsessiontime < 10800){$from2_3h++;}
                                if($record->acctsessiontime >=10800 and $record->acctsessiontime < 14400){$from3_4h++;}
                                if($record->acctsessiontime >=14400 and $record->acctsessiontime < 18000){$from4_5h++;}
                                if($record->acctsessiontime >=18000 and $record->acctsessiontime < 21600){$from5_6h++;}
                                if($record->acctsessiontime >=21600){$moreThan6++;}
                                
                            }
                            ?>
                            data: [
                                {value: {{ $less5Min }}, name: 'Less 5 Min'},
                                {value: {{ $from5_15Min }}, name: '5 - 15 Min'},
                                {value: {{ $from15_30Min }}, name: '15 - 30 Min'},
                                {value: {{ $from30_45Min }}, name: '30 - 45 Min'},
                                {value: {{ $from45_60Min }}, name: '45 - 60 Min'},
                                {value: {{ $from1_2h }}, name: '1 - 2 Hour'},
                                {value: {{ $from2_3h }}, name: '2 - 3 Hour'},
                                {value: {{ $from3_4h }}, name: '3 - 4 Hour'},
                                {value: {{ $from4_5h }}, name: '4 - 5 Hour'},
                                {value: {{ $from5_6h }}, name: '5 - 6 Hour'},
                                {value: {{ $moreThan6 }}, name: 'More than 6 Hours'}
                            ]

                        }]
                    },
                    <?php
                    }else{
                    ?>

                    <?php
                    $MonthData=App\Models\UsersRadacct::whereBetween('dates',["$currMonth->month-01","$currMonth->month-31"])->where('branch_id',$branche->id)->get();
                    $less5Min=0;
                    $from5_15Min=0;
                    $from15_30Min=0;
                    $from30_45Min=0;
                    $from45_60Min=0;
                    $from1_2h=0;
                    $from2_3h=0;
                    $from3_4h=0;
                    $from4_5h=0;
                    $from5_6h=0;
                    $moreThan6=0;
                    foreach($MonthData as $record){
                        if($record->acctsessiontime >=0 and $record->acctsessiontime < 300){$less5Min++;}
                        if($record->acctsessiontime >=300 and $record->acctsessiontime < 900){$from5_15Min++;}
                        if($record->acctsessiontime >=900 and $record->acctsessiontime < 1800){$from15_30Min++;}
                        if($record->acctsessiontime >=1800 and $record->acctsessiontime < 2700){$from30_45Min++;}
                        if($record->acctsessiontime >=2700 and $record->acctsessiontime < 3600){$from45_60Min++;}
                        if($record->acctsessiontime >=3600 and $record->acctsessiontime < 7200){$from1_2h++;}
                        if($record->acctsessiontime >=7200 and $record->acctsessiontime < 10800){$from2_3h++;}
                        if($record->acctsessiontime >=10800 and $record->acctsessiontime < 14400){$from3_4h++;}
                        if($record->acctsessiontime >=14400 and $record->acctsessiontime < 18000){$from4_5h++;}
                        if($record->acctsessiontime >=18000 and $record->acctsessiontime < 21600){$from5_6h++;}
                        if($record->acctsessiontime >=21600){$moreThan6++;}   
                    }
                    ?>
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $less5Min }}, name: 'Less 5 Min'},
                                {value: {{ $from5_15Min }}, name: '5 - 15 Min'},
                                {value: {{ $from15_30Min }}, name: '15 - 30 Min'},
                                {value: {{ $from30_45Min }}, name: '30 - 45 Min'},
                                {value: {{ $from45_60Min }}, name: '45 - 60 Min'},
                                {value: {{ $from1_2h }}, name: '1 - 2 Hour'},
                                {value: {{ $from2_3h }}, name: '2 - 3 Hour'},
                                {value: {{ $from3_4h }}, name: '3 - 4 Hour'},
                                {value: {{ $from4_5h }}, name: '4 - 5 Hour'},
                                {value: {{ $from5_6h }}, name: '5 - 6 Hour'},
                                {value: {{ $moreThan6 }}, name: 'More than 6 Hours'}
                            ]
                        }]
                    }
                    <?php
                            if($justCounter4!=$branchMonthsCounter){echo ",";}
                        }// end else if($justCounter4==1)    
                    }// end for each
                    ?>
                ]
            };

@endif
<!--  ----------------------------------------------------------------------------------------------------------------- -->
            <!-- Monitor online users  -->
@if(isset($monitorOnlineUsers))            

            // new speed dashboard
             // Setup chart
             var www = $.ajax({url:"onlineUsersNow",type: "get",dataType: "html",success:function(percentage){ return percentage;}});

            monitor_online_users_<?php echo $counter;?>_options = {

                // Add title
                title: {
                    text: 'Track online users',
                    subtext: 'Real time',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    formatter: "{a} <br/>{b}, Percentage: {c}%"
                },

                // Add series
                series: [
                    {
                        name: 'Online',
                        type: 'gauge',
                        center: ['50%', '55%'],
                        detail: {formatter:'{value}% '},
                        data: [{value: <?php $counterOnlineUsers=App\Models\UserActive::where('branch_id',$branche->id)->count();
                                             if(!isset($counterOnlineUsers)){$counterOnlineUsers = 0;}
                                             $countAllUsers=App\Users::where("Registration_type","2")->where("u_state","1")->where("suspend","0")->where('branch_id',$branche->id)->count();
                                             if($countAllUsers != 0){
                                                echo $percentage=round(($counterOnlineUsers/$countAllUsers)*100,1);
                                             }else{
                                                echo "0";
                                             }?>, name: 'Online Users NOW'}]

                    }
                ]
            };

            // Add random data
            clearInterval(timeTickerOnlineUsers<?php echo $counter;?>);
            var timeTickerOnlineUsers<?php echo $counter;?> = setInterval(function () {
                $.ajax({url:"onlineUsersNow/{{$branche->id}}",type: "get",dataType: "html",success:function(data){
                    var res = data.split(";");
                    monitor_online_users_<?php echo $counter;?>_options.series[0].data[0].value =  res[0];
                    monitor_online_users_<?php echo $counter;?>_options.series[0].data[0].name =   res[1]; }});
                monitor_online_users_<?php echo $counter;?>.setOption(monitor_online_users_<?php echo $counter;?>_options, true);
            }, 15000);

@endif
<!--   ---------------------------------------------------------------------------------------------------------------- -->
	<!-- Helicopter view on internet traffic  -->
@if(isset($helicopterViewOnInternet))    
	<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $counterSessions=array();
    $counterUpload=array();
    $counterDownload=array();
    $counterTotalQuota=array();
    $counterTime=array();
    $counterSessions[1]=0;$counterUpload[1]=0;$counterDownload[1]=0;$counterTotalQuota[1]=0;$counterTime[1]=0;
    $counterSessions[2]=0;$counterUpload[2]=0;$counterDownload[2]=0;$counterTotalQuota[2]=0;$counterTime[2]=0;
    $counterSessions[3]=0;$counterUpload[3]=0;$counterDownload[3]=0;$counterTotalQuota[3]=0;$counterTime[3]=0;
    $counterSessions[4]=0;$counterUpload[4]=0;$counterDownload[4]=0;$counterTotalQuota[4]=0;$counterTime[4]=0;
    $counterSessions[5]=0;$counterUpload[5]=0;$counterDownload[5]=0;$counterTotalQuota[5]=0;$counterTime[5]=0;
    $counterSessions[6]=0;$counterUpload[6]=0;$counterDownload[6]=0;$counterTotalQuota[6]=0;$counterTime[6]=0;
    $counterSessions[7]=0;$counterUpload[7]=0;$counterDownload[7]=0;$counterTotalQuota[7]=0;$counterTime[7]=0;
    $counterSessions[8]=0;$counterUpload[8]=0;$counterDownload[8]=0;$counterTotalQuota[8]=0;$counterTime[8]=0;
    $counterSessions[9]=0;$counterUpload[9]=0;$counterDownload[9]=0;$counterTotalQuota[9]=0;$counterTime[9]=0;
    $counterSessions[10]=0;$counterUpload[10]=0;$counterDownload[10]=0;$counterTotalQuota[10]=0;$counterTime[10]=0;
    $counterSessions[11]=0;$counterUpload[11]=0;$counterDownload[11]=0;$counterTotalQuota[11]=0;$counterTime[11]=0;
    $counterSessions[12]=0;$counterUpload[12]=0;$counterDownload[12]=0;$counterTotalQuota[12]=0;$counterTime[12]=0;
    $counterSessions[13]=0;$counterUpload[13]=0;$counterDownload[13]=0;$counterTotalQuota[13]=0;$counterTime[13]=0;
    $counterSessions[14]=0;$counterUpload[14]=0;$counterDownload[14]=0;$counterTotalQuota[14]=0;$counterTime[14]=0;
    $counterSessions[15]=0;$counterUpload[15]=0;$counterDownload[15]=0;$counterTotalQuota[15]=0;$counterTime[15]=0;
    $counterSessions[16]=0;$counterUpload[16]=0;$counterDownload[16]=0;$counterTotalQuota[16]=0;$counterTime[16]=0;
    $counterSessions[17]=0;$counterUpload[17]=0;$counterDownload[17]=0;$counterTotalQuota[17]=0;$counterTime[17]=0;
    $counterSessions[18]=0;$counterUpload[18]=0;$counterDownload[18]=0;$counterTotalQuota[18]=0;$counterTime[18]=0;
    $counterSessions[19]=0;$counterUpload[19]=0;$counterDownload[19]=0;$counterTotalQuota[19]=0;$counterTime[19]=0;
    $counterSessions[20]=0;$counterUpload[20]=0;$counterDownload[20]=0;$counterTotalQuota[20]=0;$counterTime[20]=0;
    $counterSessions[21]=0;$counterUpload[21]=0;$counterDownload[21]=0;$counterTotalQuota[21]=0;$counterTime[21]=0;
    $counterSessions[22]=0;$counterUpload[22]=0;$counterDownload[22]=0;$counterTotalQuota[22]=0;$counterTime[22]=0;
    $counterSessions[23]=0;$counterUpload[23]=0;$counterDownload[23]=0;$counterTotalQuota[23]=0;$counterTime[23]=0;
    $counterSessions[24]=0;$counterUpload[24]=0;$counterDownload[24]=0;$counterTotalQuota[24]=0;$counterTime[24]=0;
    $counterSessions[25]=0;$counterUpload[25]=0;$counterDownload[25]=0;$counterTotalQuota[25]=0;$counterTime[25]=0;
    $counterSessions[26]=0;$counterUpload[26]=0;$counterDownload[26]=0;$counterTotalQuota[26]=0;$counterTime[26]=0;
    $counterSessions[27]=0;$counterUpload[27]=0;$counterDownload[27]=0;$counterTotalQuota[27]=0;$counterTime[27]=0;
    $counterSessions[28]=0;$counterUpload[28]=0;$counterDownload[28]=0;$counterTotalQuota[28]=0;$counterTime[28]=0;
    $counterSessions[29]=0;$counterUpload[29]=0;$counterDownload[29]=0;$counterTotalQuota[29]=0;$counterTime[29]=0;
    $counterSessions[30]=0;$counterUpload[30]=0;$counterDownload[30]=0;$counterTotalQuota[30]=0;$counterTime[30]=0;
    $counterSessions[31]=0;$counterUpload[31]=0;$counterDownload[31]=0;$counterTotalQuota[31]=0;$counterTime[31]=0;

    //$startQuotaDay = $branche->start_quota;
    //if($startQuotaDay >=1 && $startQuotaDay <= 9){ $startQuotaDay="0".$startQuotaDay; }
    //$currDate=date("Y-m-").$startQuotaDay;
    $currDate=date("Y-m-d");
    //$currDate="2016-10-18";

    $firstWeekBeforeConvert= strtotime(date("$currDate", strtotime($currDate)) . "sunday this week");
    $firstWeek = date('Y-m-d', $firstWeekBeforeConvert);
    $firstWeek1stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +1 day"); $firstWeek1st=date('Y-m-d',$firstWeek1stConvert);
        //$firstWeek2stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +2 day"); $firstWeek1st=date('Y-m-d',$firstWeek2stConvert);
        //$firstWeek3stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +3 day"); $firstWeek1st=date('Y-m-d',$firstWeek3stConvert);
        //$firstWeek4stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +4 day"); $firstWeek1st=date('Y-m-d',$firstWeek4stConvert);
        //$firstWeek5stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +5 day"); $firstWeek1st=date('Y-m-d',$firstWeek5stConvert);
        //$firstWeek6stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +6 day"); $firstWeek1st=date('Y-m-d',$firstWeek6stConvert);

    $secondWeekBeforeConvert= strtotime(date("$currDate", strtotime($currDate)) . "sunday -1 week");
    $secondWeek = date('Y-m-d', $secondWeekBeforeConvert);
        //$secondWeek2stConvert = strtotime(date("$secondWeek", strtotime($secondWeek)) . " +2 day"); $secondWeek1st=date('Y-m-d',$secondWeek2stConvert);
        //$secondWeek3stConvert = strtotime(date("$secondWeek", strtotime($secondWeek)) . " +3 day"); $secondWeek1st=date('Y-m-d',$secondWeek3stConvert);
        //$secondWeek4stConvert = strtotime(date("$secondWeek", strtotime($secondWeek)) . " +4 day"); $secondWeek1st=date('Y-m-d',$secondWeek4stConvert);
        //$secondWeek5stConvert = strtotime(date("$secondWeek", strtotime($secondWeek)) . " +5 day"); $secondWeek1st=date('Y-m-d',$secondWeek5stConvert);
        //$secondWeek6stConvert = strtotime(date("$secondWeek", strtotime($secondWeek)) . " +6 day"); $secondWeek1st=date('Y-m-d',$secondWeek6stConvert);

    $thirdWeekBeforeConvert=strtotime(date("$currDate", strtotime($currDate)) . "sunday -2 week");
    $thirdWeek = date('Y-m-d', $thirdWeekBeforeConvert);
        //$thirdWeek2stConvert = strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +2 day"); $thirdWeek1st=date('Y-m-d',$thirdWeek2stConvert);
        //$thirdWeek3stConvert = strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +3 day"); $thirdWeek1st=date('Y-m-d',$thirdWeek3stConvert);
        //$thirdWeek4stConvert = strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +4 day"); $thirdWeek1st=date('Y-m-d',$thirdWeek4stConvert);
        //$thirdWeek5stConvert = strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +5 day"); $thirdWeek1st=date('Y-m-d',$thirdWeek5stConvert);
        //$thirdWeek6stConvert = strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +6 day"); $thirdWeek1st=date('Y-m-d',$thirdWeek6stConvert);

    $fourthWeekBeforeConvert=strtotime(date("$currDate", strtotime($currDate)) . "sunday -3 week");
    $fourthWeek = date('Y-m-d', $fourthWeekBeforeConvert);
        //$fourthWeek2stConvert = strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +2 day"); $fourthWeek1st=date('Y-m-d',$fourthWeek2stConvert);
        //$fourthWeek3stConvert = strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +3 day"); $fourthWeek1st=date('Y-m-d',$fourthWeek3stConvert);
        //$fourthWeek4stConvert = strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +4 day"); $fourthWeek1st=date('Y-m-d',$fourthWeek4stConvert);
        //$fourthWeek5stConvert = strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +5 day"); $fourthWeek1st=date('Y-m-d',$fourthWeek5stConvert);
        //$fourthWeek6stConvert = strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +6 day"); $fourthWeek1st=date('Y-m-d',$fourthWeek6stConvert);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $startMonth=$fourthWeek;
    $endMonth=$firstWeek1st;

    $radacctView = App\Models\UsersRadacct::where('branch_id',$branche->id)->whereBetween('dates',[$startMonth, $endMonth])->get();

    foreach($radacctView as $currRadacctView){
        $i2=1;//$counter2toCorrectDaysCounter=1;
        for($i=0;$i<=31;$i++)
        {
            if($i!=0){$currDayBeforeConvert = strtotime(date("$startMonth", strtotime($startMonth)) . " +$i day"); $currDay=date('Y-m-d',$currDayBeforeConvert);}else{$currDay=$startMonth;}
            if($currDay==$currRadacctView->dates){$counterSessions[$i2]++; $counterUpload[$i2]+=$currRadacctView->acctinputoctets; $counterDownload[$i2]+=$currRadacctView->acctoutputoctets; $counterTotalQuota[$i2]+=$currRadacctView->acctinputoctets+$currRadacctView->acctoutputoctets;$counterTime[$i2]+=$currRadacctView->acctsessiontime;}

            $i2++;
        }

    }


	?>
	helicopter_view_on_internet_traffic_<?php echo $counter;?>_options = {

                // Setup timeline
                timeline: {
                    data: ['{{$fourthWeek}}', '{{$thirdWeek}}', '{{$secondWeek}}', '{{$firstWeek}}'],
                    x: 10,
                    x2: 10,
                    label: {
                        formatter: function(s) {
                            return s.slice(0, 10);
                        }
                    },
                    autoPlay: true,
                    playInterval: 3000
                },

                // Main options
                options: [
                    {

                        // Setup grid
                        grid: {
                            x: 55,
                            x2: 110,
                            y: 35,
                            y2: 100
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'axis'
                        },

                        // Add legend
                        legend: {
                            data: ['Total Usage','Upload','Download','Time','Online Sessions']
                        },

                        // Add toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            x: 'right',
                            y: 70,
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: false,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        line: 'Switch to line chart',
                                        bar: 'Switch to bar chart',
                                        stack: 'Switch to stack',
                                        tiled: 'Switch to tiled'
                                    },
                                    type: ['line', 'bar', 'stack', 'tiled']
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },

                        // Enable drag recalculate
                        calculable: true,

                        // Horizontal axis
                        xAxis: [{
                            type: 'category',
                            axisLabel: {
                                interval: 0
                            },
                            data: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
                        }],

                        // Vertical axis
                        yAxis: [
                            {
                                type: 'value',
                                name: ''
                            },
                            {
                                type: 'value',
                                name: ''
                            }
                        ],

                        // Add series
                        // fourth week
                        series: [
                            {
                                name: 'Total Usage',
                                type: 'bar',
                                markLine: {
                                    symbol: ['arrow','none'],
                                    symbolSize: [4, 2],
                                    itemStyle: {
                                        normal: {
                                            lineStyle: {color: 'orange'},
                                            barBorderColor: 'orange',
                                            label: {
                                                position: 'left',
                                                formatter: function(params) {
                                                    return Math.round(params.value);
                                                },
                                                textStyle: {color: 'orange'}
                                            }
                                        }
                                    },
                                    data: [{type: 'average', name: 'Average'}]
                                },
                                data: [{{round($counterTotalQuota[1]/1024/1024/1024,1)}},{{round($counterTotalQuota[2]/1024/1024/1024,1)}},{{round($counterTotalQuota[3]/1024/1024/1024,1)}},{{round($counterTotalQuota[4]/1024/1024/1024,1)}},{{round($counterTotalQuota[5]/1024/1024/1024,1)}},{{round($counterTotalQuota[6]/1024/1024/1024,1)}},{{round($counterTotalQuota[7]/1024/1024/1024,1)}}]
                            },
                            {
                                name: 'Upload',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: [{{round($counterUpload[1]/1024/1024/1024,1)}},{{round($counterUpload[2]/1024/1024/1024,1)}},{{round($counterUpload[3]/1024/1024/1024,1)}},{{round($counterUpload[4]/1024/1024/1024,1)}},{{round($counterUpload[5]/1024/1024/1024,1)}},{{round($counterUpload[6]/1024/1024/1024,1)}},{{round($counterUpload[7]/1024/1024/1024,1)}}]
                            },
                            {
                                 name: 'Download',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{round($counterDownload[1]/1024/1024/1024,1)}},{{round($counterDownload[2]/1024/1024/1024,1)}},{{round($counterDownload[3]/1024/1024/1024,1)}},{{round($counterDownload[4]/1024/1024/1024,1)}},{{round($counterDownload[5]/1024/1024/1024,1)}},{{round($counterDownload[6]/1024/1024/1024,1)}},{{round($counterDownload[7]/1024/1024/1024,1)}}]
                            },
                            {
                                 name: 'Time',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{round($counterTime[1]/60/60,0)}},{{round($counterTime[2]/60/60,0)}},{{round($counterTime[3]/60/60,0)}},{{round($counterTime[4]/60/60,0)}},{{round($counterTime[5]/60/60,0)}},{{round($counterTime[6]/60/60,0)}},{{round($counterTime[7]/60/60,0)}}]
                            },
                            {
                                 name: 'Online Sessions',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$counterSessions[1]}},{{$counterSessions[2]}},{{$counterSessions[3]}},{{$counterSessions[4]}},{{$counterSessions[5]}},{{$counterSessions[6]}},{{$counterSessions[7]}}]
                            }
                        ]
                    },
                    // third week
                    {
                        series: [
                            {data: [{{round($counterTotalQuota[8]/1024/1024/1024,1)}},{{round($counterTotalQuota[9]/1024/1024/1024,1)}},{{round($counterTotalQuota[10]/1024/1024/1024,1)}},{{round($counterTotalQuota[11]/1024/1024/1024,1)}},{{round($counterTotalQuota[12]/1024/1024/1024,1)}},{{round($counterTotalQuota[13]/1024/1024/1024,1)}},{{round($counterTotalQuota[14]/1024/1024/1024,1)}}]},//Total
                            {data: [{{round($counterUpload[8]/1024/1024/1024,1)}},{{round($counterUpload[9]/1024/1024/1024,1)}},{{round($counterUpload[10]/1024/1024/1024,1)}},{{round($counterUpload[11]/1024/1024/1024,1)}},{{round($counterUpload[12]/1024/1024/1024,1)}},{{round($counterUpload[13]/1024/1024/1024,1)}},{{round($counterUpload[14]/1024/1024/1024,1)}}]},//Upload
                            {data: [{{round($counterDownload[8]/1024/1024/1024,1)}},{{round($counterDownload[9]/1024/1024/1024,1)}},{{round($counterDownload[10]/1024/1024/1024,1)}},{{round($counterDownload[11]/1024/1024/1024,1)}},{{round($counterDownload[12]/1024/1024/1024,1)}},{{round($counterDownload[13]/1024/1024/1024,1)}},{{round($counterDownload[14]/1024/1024/1024,1)}}]},//Download
                            {data: [{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}},{{round($counterTime[8]/60/60,0)}}]},//Time
                            {data: [{{$counterSessions[8]}},{{$counterSessions[9]}},{{$counterSessions[10]}},{{$counterSessions[11]}},{{$counterSessions[12]}},{{$counterSessions[13]}},{{$counterSessions[14]}}]}//online count

                        ]
                    },

                    // second week
                    {
                        series: [
                            {data: [{{round($counterTotalQuota[15]/1024/1024/1024,1)}},{{round($counterTotalQuota[16]/1024/1024/1024,1)}},{{round($counterTotalQuota[17]/1024/1024/1024,1)}},{{round($counterTotalQuota[18]/1024/1024/1024,1)}},{{round($counterTotalQuota[19]/1024/1024/1024,1)}},{{round($counterTotalQuota[20]/1024/1024/1024,1)}},{{round($counterTotalQuota[21]/1024/1024/1024,1)}}]},//Total
                            {data: [{{round($counterUpload[15]/1024/1024/1024,1)}},{{round($counterUpload[16]/1024/1024/1024,1)}},{{round($counterUpload[17]/1024/1024/1024,1)}},{{round($counterUpload[18]/1024/1024/1024,1)}},{{round($counterUpload[19]/1024/1024/1024,1)}},{{round($counterUpload[20]/1024/1024/1024,1)}},{{round($counterUpload[21]/1024/1024/1024,1)}}]},//Upload
                            {data: [{{round($counterDownload[15]/1024/1024/1024,1)}},{{round($counterDownload[16]/1024/1024/1024,1)}},{{round($counterDownload[17]/1024/1024/1024,1)}},{{round($counterDownload[18]/1024/1024/1024,1)}},{{round($counterDownload[19]/1024/1024/1024,1)}},{{round($counterDownload[20]/1024/1024/1024,1)}},{{round($counterDownload[21]/1024/1024/1024,1)}}]},//Download
                            {data: [{{round($counterTime[15]/60/60,0)}},{{round($counterTime[16]/60/60,0)}},{{round($counterTime[17]/60/60,0)}},{{round($counterTime[18]/60/60,0)}},{{round($counterTime[19]/60/60,0)}},{{round($counterTime[20]/60/60,0)}},{{round($counterTime[21]/60/60,0)}}]},//Time
                            {data: [{{$counterSessions[15]}},{{$counterSessions[16]}},{{$counterSessions[17]}},{{$counterSessions[18]}},{{$counterSessions[19]}},{{$counterSessions[20]}},{{$counterSessions[21]}}]}//online count
                            ]
                    },

                     // first week
                    {
                        series: [
                            {data: [{{round($counterTotalQuota[22]/1024/1024/1024,1)}},{{round($counterTotalQuota[23]/1024/1024/1024,1)}},{{round($counterTotalQuota[24]/1024/1024/1024,1)}},{{round($counterTotalQuota[25]/1024/1024/1024,1)}},{{round($counterTotalQuota[26]/1024/1024/1024,1)}},{{round($counterTotalQuota[27]/1024/1024/1024,1)}},{{round($counterTotalQuota[28]/1024/1024/1024,1)}}]},//Total
                            {data: [{{round($counterUpload[22]/1024/1024/1024,1)}},{{round($counterUpload[23]/1024/1024/1024,1)}},{{round($counterUpload[24]/1024/1024/1024,1)}},{{round($counterUpload[25]/1024/1024/1024,1)}},{{round($counterUpload[26]/1024/1024/1024,1)}},{{round($counterUpload[27]/1024/1024/1024,1)}},{{round($counterUpload[28]/1024/1024/1024,1)}}]},//Upload
                            {data: [{{round($counterDownload[22]/1024/1024/1024,1)}},{{round($counterDownload[23]/1024/1024/1024,1)}},{{round($counterDownload[24]/1024/1024/1024,1)}},{{round($counterDownload[25]/1024/1024/1024,1)}},{{round($counterDownload[26]/1024/1024/1024,1)}},{{round($counterDownload[27]/1024/1024/1024,1)}},{{round($counterDownload[28]/1024/1024/1024,1)}}]},//Download
                            {data: [{{round($counterTime[22]/60/60,0)}},{{round($counterTime[23]/60/60,0)}},{{round($counterTime[24]/60/60,0)}},{{round($counterTime[25]/60/60,0)}},{{round($counterTime[26]/60/60,0)}},{{round($counterTime[27]/60/60,0)}},{{round($counterTime[28]/60/60,0)}}]},//Time
                            {data: [{{$counterSessions[22]}},{{$counterSessions[23]}},{{$counterSessions[24]}},{{$counterSessions[25]}},{{$counterSessions[26]}},{{$counterSessions[27]}},{{$counterSessions[28]}}]}//online count

                        ]
                    }
                ]
            };
@endif
<!--   ---------------------------------------------------------------------------------------------------------------- -->
<!--   ---------------------------------------------------------------------------------------------------------------- -->

	<!-- returning visitors Weekly  -->
@if(isset($returningVisitorsWeekly))
	<?php

    $oneTime=array();
    $twoTimes=array();
    $threeTimes=array();
    $fourTimes=array();
    $FiveTimes=array();
    $sixTimes=array();
    $sevenTimes=array();
    $oneTime[1]=0;$twoTimes[1]=0;$threeTimes[1]=0;$fourTimes[1]=0;$FiveTimes[1]=0;$sixTimes[1]=0;$sevenTimes[1]=0;
    $oneTime[2]=0;$twoTimes[2]=0;$threeTimes[2]=0;$fourTimes[2]=0;$FiveTimes[2]=0;$sixTimes[2]=0;$sevenTimes[2]=0;
    $oneTime[3]=0;$twoTimes[3]=0;$threeTimes[3]=0;$fourTimes[3]=0;$FiveTimes[3]=0;$sixTimes[3]=0;$sevenTimes[3]=0;
    $oneTime[4]=0;$twoTimes[4]=0;$threeTimes[4]=0;$fourTimes[4]=0;$FiveTimes[4]=0;$sixTimes[4]=0;$sevenTimes[4]=0;


    $currDate=date("Y-m-d");
    //$currDate="2016-10-18";

    $firstWeekBeforeConvert= strtotime(date("$currDate", strtotime($currDate)) . "sunday this week");
    $firstWeek = date('Y-m-d', $firstWeekBeforeConvert);
    $firstWeek1stConvert = strtotime(date("$firstWeek", strtotime($firstWeek)) . " +1 day"); $firstWeek1st=date('Y-m-d',$firstWeek1stConvert);

    $secondWeekBeforeConvert= strtotime(date("$currDate", strtotime($currDate)) . "sunday -1 week");
    $secondWeek = date('Y-m-d', $secondWeekBeforeConvert);

    $thirdWeekBeforeConvert=strtotime(date("$currDate", strtotime($currDate)) . "sunday -2 week");
    $thirdWeek = date('Y-m-d', $thirdWeekBeforeConvert);

    $fourthWeekBeforeConvert=strtotime(date("$currDate", strtotime($currDate)) . "sunday -3 week");
    $fourthWeek = date('Y-m-d', $fourthWeekBeforeConvert);

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $startMonth=$fourthWeek;
    $endMonth=$firstWeek1st;
    
    $getAllActiveUsers=App\Users::where("Registration_type","2")->where("u_state","1")->where("suspend","0")->where('branch_id',$branche->id)->get();
    if($getAllActiveUsers){
        foreach($getAllActiveUsers as $user)
        {
            $totalCounterForThisUserWeek1=0;
            $totalCounterForThisUserWeek2=0;
            $totalCounterForThisUserWeek3=0;
            $totalCounterForThisUserWeek4=0;

            $radacctView = App\Models\UsersRadacct::where('branch_id',$branche->id)->where('u_id',$user->u_id)->whereBetween('dates',[$startMonth, $endMonth])->get();
            foreach($radacctView as $currRadacctView){
                $i2=1;// to get 4 weeks
                for($i=1;$i<=4;$i++)
                {
                    if($i==1){
                        $startWeek=$firstWeek;
                        $currWeekBeforeConvert=strtotime(date("$firstWeek", strtotime($firstWeek)) . " +6 day");
                        $endWeek=date('Y-m-d',$currWeekBeforeConvert);
                        if(date($currRadacctView->dates) >= date($startWeek) and date($currRadacctView->dates) <= date($endWeek)){$totalCounterForThisUserWeek1++;}
                    }elseif($i==2){
                        $startWeek=$secondWeek;
                        $currWeekBeforeConvert=strtotime(date("$secondWeek", strtotime($secondWeek)) . " +6 day");
                        $endWeek=date('Y-m-d',$currWeekBeforeConvert);
                        if(date($currRadacctView->dates) >= date($startWeek) and date($currRadacctView->dates) <= date($endWeek)){$totalCounterForThisUserWeek2++;}
                    }elseif($i==3){
                        $startWeek=$thirdWeek;
                        $currWeekBeforeConvert=strtotime(date("$thirdWeek", strtotime($thirdWeek)) . " +6 day");
                        $endWeek=date('Y-m-d',$currWeekBeforeConvert);
                        if(date($currRadacctView->dates) >= date($startWeek) and date($currRadacctView->dates) <= date($endWeek)){$totalCounterForThisUserWeek3++;}
                    }elseif($i==4){
                        $startWeek=$fourthWeek;
                        $currWeekBeforeConvert=strtotime(date("$fourthWeek", strtotime($fourthWeek)) . " +6 day");
                        $endWeek=date('Y-m-d',$currWeekBeforeConvert);
                        if(date($currRadacctView->dates) >= date($startWeek) and date($currRadacctView->dates) <= date($endWeek)){$totalCounterForThisUserWeek4++;}
                    }
                    $i2++;
                }
            }
            if($totalCounterForThisUserWeek1==1){$oneTime[1]++;}
            elseif($totalCounterForThisUserWeek1==2){$twoTimes[1]++;}
            elseif($totalCounterForThisUserWeek1==3){$threeTimes[1]++;}
            elseif($totalCounterForThisUserWeek1==4){$fourTimes[1]++;}
            elseif($totalCounterForThisUserWeek1==5){$FiveTimes[1]++;}
            elseif($totalCounterForThisUserWeek1==6){$sixTimes[1]++;}
            elseif($totalCounterForThisUserWeek1==7){$sevenTimes[1]++;}

            if($totalCounterForThisUserWeek2==1){$oneTime[2]++;}
            elseif($totalCounterForThisUserWeek2==2){$twoTimes[2]++;}
            elseif($totalCounterForThisUserWeek2==3){$threeTimes[2]++;}
            elseif($totalCounterForThisUserWeek2==4){$fourTimes[2]++;}
            elseif($totalCounterForThisUserWeek2==5){$FiveTimes[2]++;}
            elseif($totalCounterForThisUserWeek2==6){$sixTimes[2]++;}
            elseif($totalCounterForThisUserWeek2==7){$sevenTimes[2]++;}

            if($totalCounterForThisUserWeek3==1){$oneTime[3]++;}
            elseif($totalCounterForThisUserWeek3==2){$twoTimes[3]++;}
            elseif($totalCounterForThisUserWeek3==3){$threeTimes[3]++;}
            elseif($totalCounterForThisUserWeek3==4){$fourTimes[3]++;}
            elseif($totalCounterForThisUserWeek3==5){$FiveTimes[3]++;}
            elseif($totalCounterForThisUserWeek3==6){$sixTimes[3]++;}
            elseif($totalCounterForThisUserWeek3==7){$sevenTimes[3]++;}

            if($totalCounterForThisUserWeek4==1){$oneTime[4]++;}
            elseif($totalCounterForThisUserWeek4==2){$twoTimes[4]++;}
            elseif($totalCounterForThisUserWeek4==3){$threeTimes[4]++;}
            elseif($totalCounterForThisUserWeek4==4){$fourTimes[4]++;}
            elseif($totalCounterForThisUserWeek4==5){$FiveTimes[4]++;}
            elseif($totalCounterForThisUserWeek4==6){$sixTimes[4]++;}
            elseif($totalCounterForThisUserWeek4==7){$sevenTimes[4]++;}
        }
    }


	?>
	returning_visitors_weekly_<?php echo $counter;?>_options = {

                // Setup timeline
                timeline: {
                    data: ['{{$fourthWeek}}', '{{$thirdWeek}}', '{{$secondWeek}}', '{{$firstWeek}}'],
                    x: 10,
                    x2: 10,
                    label: {
                        formatter: function(s) {
                            return s.slice(0, 10);
                        }
                    },
                    autoPlay: true,
                    playInterval: 3000
                },

                // Main options
                options: [
                    {

                        // Setup grid
                        grid: {
                            x: 55,
                            x2: 110,
                            y: 35,
                            y2: 100
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'axis'
                        },

                        // Add legend
                        legend: {
                            data: ['One Time','Two Times','Three Times','Four Times','Five Times','Six Times','Seven Times']
                        },

                        // Add toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            x: 'right',
                            y: 70,
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: false,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        line: 'Switch to line chart',
                                        bar: 'Switch to bar chart',
                                        stack: 'Switch to stack',
                                        tiled: 'Switch to tiled'
                                    },
                                    type: ['line', 'bar', 'stack', 'tiled']
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },

                        // Enable drag recalculate
                        calculable: true,

                        // Horizontal axis
                        xAxis: [{
                            type: 'category',
                            axisLabel: {
                                interval: 0
                            },
                            data: ['Weekly Returned Visitors']
                        }],

                        // Vertical axis
                        yAxis: [
                            {
                                type: 'value',
                                name: ''
                            },
                            {
                                type: 'value',
                                name: ''
                            }
                        ],

                        // Add series
                        // fourth week
                        series: [
                            {
                                name: 'One Time',
                                type: 'bar',
                                markLine: {
                                    symbol: ['arrow','none'],
                                    symbolSize: [4, 2],
                                    itemStyle: {
                                        normal: {
                                            lineStyle: {color: 'orange'},
                                            barBorderColor: 'orange',
                                            label: {
                                                position: 'left',
                                                formatter: function(params) {
                                                    return Math.round(params.value);
                                                },
                                                textStyle: {color: 'orange'}
                                            }
                                        }
                                    },
                                    data: [{type: 'average', name: 'Average'}]
                                },
                                data: [{{$oneTime[4]}}]
                            },
                            {
                                name: 'Two Times',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: [{{$twoTimes[4]}}]
                            },
                            {
                                 name: 'Three Times',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$threeTimes[4]}}]
                            },
                            {
                                 name: 'Four Times',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$fourTimes[4]}}]
                            },
                            {
                                 name: 'Five Times',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$FiveTimes[4]}}]
                            },
                            {
                                 name: 'Six Times',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$sixTimes[4]}}]
                            },
                            {
                                 name: 'Seven Times',
                                 yAxisIndex: 1,
                                 type: 'bar',
                                 data: [{{$sevenTimes[4]}}]
                            }
                        ]
                    },
                    // third week
                    {
                        series: [
                            {data: [{{$oneTime[3]}}]},
                            {data: [{{$twoTimes[3]}}]},
                            {data: [{{$threeTimes[3]}}]},
                            {data: [{{$fourTimes[3]}}]},
                            {data: [{{$FiveTimes[3]}}]},
                            {data: [{{$sixTimes[3]}}]},
                            {data: [{{$sevenTimes[3]}}]}

                        ]
                    },

                    // second week
                    {
                        series: [
                            {data: [{{$oneTime[2]}}]},
                            {data: [{{$twoTimes[2]}}]},
                            {data: [{{$threeTimes[2]}}]},
                            {data: [{{$fourTimes[2]}}]},
                            {data: [{{$FiveTimes[2]}}]},
                            {data: [{{$sixTimes[2]}}]},
                            {data: [{{$sevenTimes[2]}}]}
                            ]
                    },

                     // first week
                    {
                        series: [
                            {data: [{{$oneTime[1]}}]},
                            {data: [{{$twoTimes[1]}}]},
                            {data: [{{$threeTimes[1]}}]},
                            {data: [{{$fourTimes[1]}}]},
                            {data: [{{$FiveTimes[1]}}]},
                            {data: [{{$sixTimes[1]}}]},
                            {data: [{{$sevenTimes[1]}}]}
                        ]
                    }
                ]
            };
@endif            
<!---------------------------------------------------------------------------------------------------------------------- -->

<!--   Returning visitors Monthly -->
@if(isset($returningVisitorsMonhly))
    <?php
    $year=date("Y");

    $oneTime=array();
    $twoTimes=array();
    $threeTimes=array();
    $fourTimes=array();
    $FiveTimes=array();
    $sixTimes=array();
    $sevenTimes=array();
    $oneTime[1]=0;$twoTimes[1]=0;$threeTimes[1]=0;$fourTimes[1]=0;$FiveTimes[1]=0;$sixTimes[1]=0;$sevenTimes[1]=0;
    $oneTime[2]=0;$twoTimes[2]=0;$threeTimes[2]=0;$fourTimes[2]=0;$FiveTimes[2]=0;$sixTimes[2]=0;$sevenTimes[2]=0;
    $oneTime[3]=0;$twoTimes[3]=0;$threeTimes[3]=0;$fourTimes[3]=0;$FiveTimes[3]=0;$sixTimes[3]=0;$sevenTimes[3]=0;
    $oneTime[4]=0;$twoTimes[4]=0;$threeTimes[4]=0;$fourTimes[4]=0;$FiveTimes[4]=0;$sixTimes[4]=0;$sevenTimes[4]=0;
    $oneTime[5]=0;$twoTimes[5]=0;$threeTimes[5]=0;$fourTimes[5]=0;$FiveTimes[5]=0;$sixTimes[5]=0;$sevenTimes[5]=0;
    $oneTime[6]=0;$twoTimes[6]=0;$threeTimes[6]=0;$fourTimes[6]=0;$FiveTimes[6]=0;$sixTimes[6]=0;$sevenTimes[6]=0;
    $oneTime[7]=0;$twoTimes[7]=0;$threeTimes[7]=0;$fourTimes[7]=0;$FiveTimes[7]=0;$sixTimes[7]=0;$sevenTimes[7]=0;
    $oneTime[8]=0;$twoTimes[8]=0;$threeTimes[8]=0;$fourTimes[8]=0;$FiveTimes[8]=0;$sixTimes[8]=0;$sevenTimes[8]=0;
    $oneTime[9]=0;$twoTimes[9]=0;$threeTimes[9]=0;$fourTimes[9]=0;$FiveTimes[9]=0;$sixTimes[9]=0;$sevenTimes[9]=0;
    $oneTime[10]=0;$twoTimes[10]=0;$threeTimes[10]=0;$fourTimes[10]=0;$FiveTimes[10]=0;$sixTimes[10]=0;$sevenTimes[10]=0;
    $oneTime[11]=0;$twoTimes[11]=0;$threeTimes[11]=0;$fourTimes[11]=0;$FiveTimes[11]=0;$sixTimes[11]=0;$sevenTimes[11]=0;
    $oneTime[12]=0;$twoTimes[12]=0;$threeTimes[12]=0;$fourTimes[12]=0;$FiveTimes[12]=0;$sixTimes[12]=0;$sevenTimes[12]=0;


    $currDate=date("Y-m-d");
    //$currDate="2016-10-18";

    $firstDayMonth1=date("Y")."-01-01";
    $lastDayMonth1=date('Y-m-t', strtotime($firstDayMonth1));

    $firstDayMonth2=date("Y")."-02-01";
    $lastDayMonth2=date('Y-m-t', strtotime($firstDayMonth2));

    $firstDayMonth3=date("Y")."-03-01";
    $lastDayMonth3=date('Y-m-t', strtotime($firstDayMonth3));

    $firstDayMonth4=date("Y")."-04-01";
    $lastDayMonth4=date('Y-m-t', strtotime($firstDayMonth4));

    $firstDayMonth5=date("Y")."-05-01";
    $lastDayMonth5=date('Y-m-t', strtotime($firstDayMonth5));

    $firstDayMonth6=date("Y")."-06-01";
    $lastDayMonth6=date('Y-m-t', strtotime($firstDayMonth6));

    $firstDayMonth7=date("Y")."-07-01";
    $lastDayMonth7=date('Y-m-t', strtotime($firstDayMonth7));

    $firstDayMonth8=date("Y")."-08-01";
    $lastDayMonth8=date('Y-m-t', strtotime($firstDayMonth8));

    $firstDayMonth9=date("Y")."-09-01";
    $lastDayMonth9=date('Y-m-t', strtotime($firstDayMonth9));

    $firstDayMonth10=date("Y")."-10-01";
    $lastDayMonth10=date('Y-m-t', strtotime($firstDayMonth10));

    $firstDayMonth11=date("Y")."-11-01";
    $lastDayMonth11=date('Y-m-t', strtotime($firstDayMonth11));

    $firstDayMonth12=date("Y")."-12-01";
    $lastDayMonth12=date('Y-m-t', strtotime($firstDayMonth12));

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $getAllActiveUsers=App\Users::where("Registration_type","2")->where("u_state","1")->where("suspend","0")->where('branch_id',$branche->id)->get();
    if($getAllActiveUsers){
        foreach($getAllActiveUsers as $user)
        {
            $totalCounterForThisUserMonth1=0;
            $totalCounterForThisUserMonth2=0;
            $totalCounterForThisUserMonth3=0;
            $totalCounterForThisUserMonth4=0;
            $totalCounterForThisUserMonth4=0;
            $totalCounterForThisUserMonth5=0;
            $totalCounterForThisUserMonth6=0;
            $totalCounterForThisUserMonth7=0;
            $totalCounterForThisUserMonth8=0;
            $totalCounterForThisUserMonth9=0;
            $totalCounterForThisUserMonth10=0;
            $totalCounterForThisUserMonth11=0;
            $totalCounterForThisUserMonth12=0;

            $radacctView = App\Models\UsersRadacct::where('branch_id',$branche->id)->where('u_id',$user->u_id)->whereBetween('dates',[$firstDayMonth1, $lastDayMonth12])->get();
            foreach($radacctView as $currRadacctView){
                $i2=1;// to get 12 Months
                for($i=1;$i<=12;$i++)
                {
                    if($i==1){
                        if(date($currRadacctView->dates) >= date($firstDayMonth1) and date($currRadacctView->dates) <= date($lastDayMonth1)){$totalCounterForThisUserMonth1++;}
                    }elseif($i==2){
                        if(date($currRadacctView->dates) >= date($firstDayMonth2) and date($currRadacctView->dates) <= date($lastDayMonth2)){$totalCounterForThisUserMonth2++;}
                    }elseif($i==3){
                        if(date($currRadacctView->dates) >= date($firstDayMonth3) and date($currRadacctView->dates) <= date($lastDayMonth3)){$totalCounterForThisUserMonth3++;}
                    }elseif($i==4){
                        if(date($currRadacctView->dates) >= date($firstDayMonth4) and date($currRadacctView->dates) <= date($lastDayMonth4)){$totalCounterForThisUserMonth4++;}
                    }elseif($i==5){
                        if(date($currRadacctView->dates) >= date($firstDayMonth5) and date($currRadacctView->dates) <= date($lastDayMonth5)){$totalCounterForThisUserMonth5++;}
                    }elseif($i==6){
                        if(date($currRadacctView->dates) >= date($firstDayMonth6) and date($currRadacctView->dates) <= date($lastDayMonth6)){$totalCounterForThisUserMonth6++;}
                    }elseif($i==7){
                        if(date($currRadacctView->dates) >= date($firstDayMonth7) and date($currRadacctView->dates) <= date($lastDayMonth7)){$totalCounterForThisUserMonth7++;}
                    }elseif($i==8){
                        if(date($currRadacctView->dates) >= date($firstDayMonth8) and date($currRadacctView->dates) <= date($lastDayMonth8)){$totalCounterForThisUserMonth8++;}
                    }elseif($i==9){
                        if(date($currRadacctView->dates) >= date($firstDayMonth9) and date($currRadacctView->dates) <= date($lastDayMonth9)){$totalCounterForThisUserMonth9++;}
                    }elseif($i==10){
                        if(date($currRadacctView->dates) >= date($firstDayMonth10) and date($currRadacctView->dates) <= date($lastDayMonth10)){$totalCounterForThisUserMonth10++;}
                    }elseif($i==11){
                        if(date($currRadacctView->dates) >= date($firstDayMonth11) and date($currRadacctView->dates) <= date($lastDayMonth11)){$totalCounterForThisUserMonth11++;}
                    }elseif($i==12){
                        if(date($currRadacctView->dates) >= date($firstDayMonth12) and date($currRadacctView->dates) <= date($lastDayMonth12)){$totalCounterForThisUserMonth12++;}
                    }
                    $i2++;
                }
            }
            if($totalCounterForThisUserMonth1==1){$oneTime[1]++;}
            elseif($totalCounterForThisUserMonth1==2){$twoTimes[1]++;}
            elseif($totalCounterForThisUserMonth1==3){$threeTimes[1]++;}
            elseif($totalCounterForThisUserMonth1==4){$fourTimes[1]++;}
            elseif($totalCounterForThisUserMonth1==5){$FiveTimes[1]++;}
            elseif($totalCounterForThisUserMonth1==6){$sixTimes[1]++;}
            elseif($totalCounterForThisUserMonth1>=7){$sevenTimes[1]++;}

            if($totalCounterForThisUserMonth2==1){$oneTime[2]++;}
            elseif($totalCounterForThisUserMonth2==2){$twoTimes[2]++;}
            elseif($totalCounterForThisUserMonth2==3){$threeTimes[2]++;}
            elseif($totalCounterForThisUserMonth2==4){$fourTimes[2]++;}
            elseif($totalCounterForThisUserMonth2==5){$FiveTimes[2]++;}
            elseif($totalCounterForThisUserMonth2==6){$sixTimes[2]++;}
            elseif($totalCounterForThisUserMonth2>=7){$sevenTimes[2]++;}

            if($totalCounterForThisUserMonth3==1){$oneTime[3]++;}
            elseif($totalCounterForThisUserMonth3==2){$twoTimes[3]++;}
            elseif($totalCounterForThisUserMonth3==3){$threeTimes[3]++;}
            elseif($totalCounterForThisUserMonth3==4){$fourTimes[3]++;}
            elseif($totalCounterForThisUserMonth3==5){$FiveTimes[3]++;}
            elseif($totalCounterForThisUserMonth3==6){$sixTimes[3]++;}
            elseif($totalCounterForThisUserMonth3>=7){$sevenTimes[3]++;}

            if($totalCounterForThisUserMonth4==1){$oneTime[4]++;}
            elseif($totalCounterForThisUserMonth4==2){$twoTimes[4]++;}
            elseif($totalCounterForThisUserMonth4==3){$threeTimes[4]++;}
            elseif($totalCounterForThisUserMonth4==4){$fourTimes[4]++;}
            elseif($totalCounterForThisUserMonth4==5){$FiveTimes[4]++;}
            elseif($totalCounterForThisUserMonth4==6){$sixTimes[4]++;}
            elseif($totalCounterForThisUserMonth4>=7){$sevenTimes[4]++;}

            if($totalCounterForThisUserMonth5==1){$oneTime[5]++;}
            elseif($totalCounterForThisUserMonth5==2){$twoTimes[5]++;}
            elseif($totalCounterForThisUserMonth5==3){$threeTimes[5]++;}
            elseif($totalCounterForThisUserMonth5==4){$fourTimes[5]++;}
            elseif($totalCounterForThisUserMonth5==5){$FiveTimes[5]++;}
            elseif($totalCounterForThisUserMonth5==6){$sixTimes[5]++;}
            elseif($totalCounterForThisUserMonth5>=7){$sevenTimes[5]++;}

            if($totalCounterForThisUserMonth6==1){$oneTime[6]++;}
            elseif($totalCounterForThisUserMonth6==2){$twoTimes[6]++;}
            elseif($totalCounterForThisUserMonth6==3){$threeTimes[6]++;}
            elseif($totalCounterForThisUserMonth6==4){$fourTimes[6]++;}
            elseif($totalCounterForThisUserMonth6==5){$FiveTimes[6]++;}
            elseif($totalCounterForThisUserMonth6==6){$sixTimes[6]++;}
            elseif($totalCounterForThisUserMonth6>=7){$sevenTimes[6]++;}

            if($totalCounterForThisUserMonth7==1){$oneTime[7]++;}
            elseif($totalCounterForThisUserMonth7==2){$twoTimes[7]++;}
            elseif($totalCounterForThisUserMonth7==3){$threeTimes[7]++;}
            elseif($totalCounterForThisUserMonth7==4){$fourTimes[7]++;}
            elseif($totalCounterForThisUserMonth7==5){$FiveTimes[7]++;}
            elseif($totalCounterForThisUserMonth7==6){$sixTimes[7]++;}
            elseif($totalCounterForThisUserMonth7>=7){$sevenTimes[7]++;}

            if($totalCounterForThisUserMonth8==1){$oneTime[8]++;}
            elseif($totalCounterForThisUserMonth8==2){$twoTimes[8]++;}
            elseif($totalCounterForThisUserMonth8==3){$threeTimes[8]++;}
            elseif($totalCounterForThisUserMonth8==4){$fourTimes[8]++;}
            elseif($totalCounterForThisUserMonth8==5){$FiveTimes[8]++;}
            elseif($totalCounterForThisUserMonth8==6){$sixTimes[8]++;}
            elseif($totalCounterForThisUserMonth8>=7){$sevenTimes[8]++;}

            if($totalCounterForThisUserMonth9==1){$oneTime[9]++;}
            elseif($totalCounterForThisUserMonth9==2){$twoTimes[9]++;}
            elseif($totalCounterForThisUserMonth9==3){$threeTimes[9]++;}
            elseif($totalCounterForThisUserMonth9==4){$fourTimes[9]++;}
            elseif($totalCounterForThisUserMonth9==5){$FiveTimes[9]++;}
            elseif($totalCounterForThisUserMonth9==6){$sixTimes[9]++;}
            elseif($totalCounterForThisUserMonth9>=7){$sevenTimes[9]++;}

            if($totalCounterForThisUserMonth10==1){$oneTime[10]++;}
            elseif($totalCounterForThisUserMonth10==2){$twoTimes[10]++;}
            elseif($totalCounterForThisUserMonth10==3){$threeTimes[10]++;}
            elseif($totalCounterForThisUserMonth10==4){$fourTimes[10]++;}
            elseif($totalCounterForThisUserMonth10==5){$FiveTimes[10]++;}
            elseif($totalCounterForThisUserMonth10==6){$sixTimes[10]++;}
            elseif($totalCounterForThisUserMonth10>=7){$sevenTimes[10]++;}

            if($totalCounterForThisUserMonth11==1){$oneTime[11]++;}
            elseif($totalCounterForThisUserMonth11==2){$twoTimes[11]++;}
            elseif($totalCounterForThisUserMonth11==3){$threeTimes[11]++;}
            elseif($totalCounterForThisUserMonth11==4){$fourTimes[11]++;}
            elseif($totalCounterForThisUserMonth11==5){$FiveTimes[11]++;}
            elseif($totalCounterForThisUserMonth11==6){$sixTimes[11]++;}
            elseif($totalCounterForThisUserMonth11>=7){$sevenTimes[11]++;}

            if($totalCounterForThisUserMonth12==1){$oneTime[12]++;}
            elseif($totalCounterForThisUserMonth12==2){$twoTimes[12]++;}
            elseif($totalCounterForThisUserMonth12==3){$threeTimes[12]++;}
            elseif($totalCounterForThisUserMonth12==4){$fourTimes[12]++;}
            elseif($totalCounterForThisUserMonth12==5){$FiveTimes[12]++;}
            elseif($totalCounterForThisUserMonth12==6){$sixTimes[12]++;}
            elseif($totalCounterForThisUserMonth12>=7){$sevenTimes[12]++;}

        }
    }


	?>
            var idx = 1;
            returning_visitors_monthly_<?php echo $counter;?>_options = {

                // Add timeline
                timeline: {
                    x: 10,
                    x2: 10,
                    data: [
                        '{{date("Y")}}-01-01', '{{date("Y")}}-02-01', '{{date("Y")}}-03-01', '{{date("Y")}}-04-01', '{{date("Y")}}-05-01',
                        { name:'{{date("Y")}}-06-01', symbol: 'emptyStar2', symbolSize: 8 },
                        '{{date("Y")}}-07-01', '{{date("Y")}}-08-01', '{{date("Y")}}-09-01', '{{date("Y")}}-10-01', '{{date("Y")}}-11-01',
                        { name:'{{date("Y")}}-12-01', symbol: 'star2', symbolSize: 8 }
                    ],
                    label: {
                        formatter: function(s) {
                            return s.slice(0, 7);
                        }
                    },
                    autoPlay: true,
                    playInterval: 3000
                },

                // Set options
                options: [
                    {

                        // Add title
                        title: {
                            text: 'Monthly Returned Visitors',
                            subtext: 'Know more about your customers loyality',
                            x: 'center'
                        },
                        // Enable drag recalculate
                        calculable: true,
                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            formatter: "{a} <br/>{b}: {c} ({d}%)"
                        },

                        // Add legend
                        legend: {
                            x: 'left',
                            orient: 'vertical',
                            data: ['One Time','Two Times','Three Times','Four Times','Five Times','Six Times','More Than Seven Times']
                        },

                        // Display toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: false,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        pie: 'Switch to pies',
                                        funnel: 'Switch to funnel',
                                    },
                                    type: ['pie', 'funnel'],
                                    option: {
                                        funnel: {
                                            x: '25%',
                                            width: '50%',
                                            funnelAlign: 'left',
                                            max: 1700
                                        }
                                    }
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },

                        // Add series
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            center: ['50%', '50%'],
                            radius: '60%',
                            data: [
                                {value: {{ $oneTime[1] }}, name: 'One Time'},
                                {value: {{ $twoTimes[1] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[1] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[1] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[1] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[1] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[1] }}, name: 'More Than Seven Times'}
                            ]
                        }]
                    },

                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[2] }}, name: 'One Time'},
                                {value: {{ $twoTimes[2] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[2] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[2] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[2] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[2] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[2] }}, name: 'More Than Seven Times'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[3] }}, name: 'One Time'},
                                {value: {{ $twoTimes[3] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[3] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[3] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[3] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[3] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[3] }}, name: 'More Than Seven Times'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[4] }}, name: 'One Time'},
                                {value: {{ $twoTimes[4] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[4] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[4] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[4] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[4] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[4] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[5] }}, name: 'One Time'},
                                {value: {{ $twoTimes[5] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[5] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[5] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[5] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[5] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[5] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[6] }}, name: 'One Time'},
                                {value: {{ $twoTimes[6] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[6] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[6] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[6] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[6] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[6] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[7] }}, name: 'One Time'},
                                {value: {{ $twoTimes[7] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[7] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[7] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[7] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[7] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[7] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[8] }}, name: 'One Time'},
                                {value: {{ $twoTimes[8] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[8] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[8] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[8] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[8] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[8] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[9] }}, name: 'One Time'},
                                {value: {{ $twoTimes[9] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[9] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[9] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[9] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[9] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[9] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[10] }}, name: 'One Time'},
                                {value: {{ $twoTimes[10] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[10] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[10] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[10] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[10] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[10] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[11] }}, name: 'One Time'},
                                {value: {{ $twoTimes[11] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[11] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[11] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[11] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[11] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[11] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Returned',
                            type: 'pie',
                            data: [
                                {value: {{ $oneTime[12] }}, name: 'One Time'},
                                {value: {{ $twoTimes[12] }}, name: 'Two Times'},
                                {value: {{ $threeTimes[12] }}, name: 'Three Times'},
                                {value: {{ $fourTimes[12] }}, name: 'Four Times'},
                                {value: {{ $FiveTimes[12] }}, name: 'Five Times'},
                                {value: {{ $sixTimes[12] }}, name: 'Six Times'},
                                {value: {{ $sevenTimes[12] }}, name: 'More Than Seven Times'}                            
                                ]
                        }]
                    }
                ]
            };


@endif
<!--  ----------------------------------------------------------------------------------------------------------------- -->
	<?php } ?>
@endsection
@section('options')
		<?php 
	$counter=0;	
	foreach($branches as $branche){
		$branche_name = $branche->name;
		$counter++;
	?>
    @if(isset($monitorInternetUsage)) monitor_internet_usage_<?php echo $counter;?>.setOption(monitor_internet_usage_<?php echo $counter;?>_options); @endif
	@if(isset($helicopterViewOnInternet)) helicopter_view_on_internet_traffic_<?php echo $counter;?>.setOption(helicopter_view_on_internet_traffic_<?php echo $counter;?>_options); @endif
    @if(isset($timeTracker)) time_tracker_<?php echo $counter;?>.setOption(time_tracker_<?php echo $counter;?>_options); @endif

    @if(isset($monitorOnlineUsers)) monitor_online_users_<?php echo $counter;?>.setOption(monitor_online_users_<?php echo $counter;?>_options); @endif

    @if(isset($returningVisitorsWeekly)) returning_visitors_weekly_<?php echo $counter;?>.setOption(returning_visitors_weekly_<?php echo $counter;?>_options); @endif
    @if(isset($returningVisitorsMonhly)) returning_visitors_monthly_<?php echo $counter;?>.setOption(returning_visitors_monthly_<?php echo $counter;?>_options); @endif

	<?php }?>
@endsection
@section('resize')
	<?php 
	$counter=0;	
	foreach($branches as $branche){
		$branche_name = $branche->name;
		$counter++;
	?>	
	@if(isset($monitorInternetUsage)) monitor_internet_usage_<?php echo $counter;?>.resize(); @endif
	@if(isset($helicopterViewOnInternet)) helicopter_view_on_internet_traffic_<?php echo $counter;?>.resize(); @endif
	@if(isset($timeTracker)) time_tracker_<?php echo $counter;?>.resize(); @endif
	@if(isset($monitorOnlineUsers)) monitor_online_users_<?php echo $counter;?>.resize(); @endif

	@if(isset($returningVisitorsWeekly)) returning_visitors_weekly_<?php echo $counter;?>.resize(); @endif
    @if(isset($returningVisitorsMonhly)) returning_visitors_monthly_<?php echo $counter;?>.resize(); @endif

	<?php }?>
@endsection
@section('html')
	<?php 
	$counter=0;	
	foreach($branches as $branche){
		$branche_name = $branche->name;
		$counter++;
	?>
<?php /* ?>
<!-- Columns timeline
<div class="panel panel-flat col-lg-12">
    <div class="panel-heading">
        <h5 class="panel-title"><i class="icon-airplane3"></i> Helicopter view on internet traffic</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="monitor_internet_usage_<?php echo $counter;?>"></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="helicopter_view_on_internet_traffic_<?php echo $counter;?>"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php */ ?>
<!-- /columns timeline -->

         <!-- Monitor online users -->
        @if(isset($monitorOnlineUsers)) 
        <div class="panel panel-flat col-lg-6">
            <div class="panel-heading">
                <h5 class="panel-title">Monitor online users ( Branch : {{$branche_name}} )</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="monitor_online_users_<?php echo $counter;?>"></div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Monitor online users -->

        <!-- Monitor internet usage -->
        @if(isset($monitorInternetUsage)) 
        <div class="panel panel-flat col-lg-6">
            <div class="panel-heading">
                <h5 class="panel-title">Monitor internet usage ( Branch : {{$branche_name}} )</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="monitor_internet_usage_<?php echo $counter;?>"></div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Monitor internet usage -->

        <!-- Helicopter view on internet traffic -->
        @if(isset($helicopterViewOnInternet)) 
            @if($dashboardType->value=="internetManagement")
            <div class="panel panel-flat col-lg-12">
            @else
            <div class="panel panel-flat col-lg-6">
            @endif
                <div class="panel-heading">
                    <h5 class="panel-title">Helicopter view on internet traffic ( Branch : {{$branche_name}} )</h5>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="chart-container has-scroll">
                        <div class="chart has-fixed-height has-minimum-width" id="helicopter_view_on_internet_traffic_<?php echo $counter;?>"></div>
                    </div>
                </div>
            </div>
        @endif
        <!-- /Helicopter view on internet traffic -->


		<!-- Time tracker -->
        @if(isset($timeTracker)) 
            @if($dashboardType->value=="marketing" && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
            <div class="panel panel-flat col-lg-12">
            @else
            <div class="panel panel-flat col-lg-12">
            @endif
                <div class="panel-heading">
                    <h5 class="panel-title">Time tracker ( Branch : {{$branche_name}} )</h5>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="chart-container has-scroll">
                        <div class="chart has-fixed-height has-minimum-width" id="time_tracker_<?php echo $counter;?>"></div>
                    </div>
                </div>
            </div>
        @endif
	    <!-- /Time tracker -->



        <?php /* ?>
        <!-- returning visitors weekly 
        <div class="panel panel-flat col-lg-6">
            <div class="panel-heading">
                <h5 class="panel-title">Returning Visitors Weekly ( Branch : {{$branche_name}} )</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <div class="chart-container has-scroll">
                    <div class="chart has-fixed-height has-minimum-width" id="returning_visitors_weekly_<?php echo $counter;?>"></div>
                </div>
            </div>
        </div>
        <!-- /returning visitors weekly -->



        <!-- returning visitors Monthly 
        <div class="panel panel-flat col-lg-6">
            <div class="panel-heading">
                <h5 class="panel-title">Returning visitors Monthly ( Branch : {{$branche_name}} )</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <div class="chart-container has-scroll">
                    <div class="chart has-fixed-height has-minimum-width" id="returning_visitors_monthly_<?php echo $counter;?>"></div>
                </div>
            </div>
        </div>
        <!-- Returning visitors monthly  -->
        <?php */?>
        <!-- Returning visitors-->
        @if(isset($returningVisitorsWeekly) and isset($returningVisitorsMonhly)) 
        <div class="panel panel-flat col-lg-12">
            <div class="panel-heading">
                <h5 class="panel-title">Returning visitors ( Branch : {{$branche_name}} )</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="chart has-fixed-height" id="returning_visitors_weekly_<?php echo $counter;?>"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="chart has-fixed-height" id="returning_visitors_monthly_<?php echo $counter;?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Returning visitors -->




	<?php } ?>

@endsection
<?php } ?>