
<script>
$(function () {

    // Set paths
    // ------------------------------

    require.config({
        paths: {
            echarts: 'assets/js/plugins/visualization/echarts'
        }
    });


    // Configuration
    // ------------------------------

    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/pie',
            'echarts/chart/funnel',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/k',
            'echarts/chart/radar',
            'echarts/chart/gauge',
            'echarts/chart/line',
            'echarts/chart/bar',
            'echarts/chart/chord'


        ],

        <?php
        // general variables
        // $currDate=date("Y-m-d");

        $firstDayMonth1=date("Y")."-01-01";
        $lastDayMonth1=date('Y-m-t', strtotime($firstDayMonth1));
        $firstDayMonth[1]=$firstDayMonth1;
        $lastDayMonth[1]=$lastDayMonth1;

        $firstDayMonth2=date("Y")."-02-01";
        $lastDayMonth2=date('Y-m-t', strtotime($firstDayMonth2));
        $firstDayMonth[2]=$firstDayMonth2;
        $lastDayMonth[2]=$lastDayMonth2;

        $firstDayMonth3=date("Y")."-03-01";
        $lastDayMonth3=date('Y-m-t', strtotime($firstDayMonth3));
        $firstDayMonth[3]=$firstDayMonth3;
        $lastDayMonth[3]=$lastDayMonth3;

        $firstDayMonth4=date("Y")."-04-01";
        $lastDayMonth4=date('Y-m-t', strtotime($firstDayMonth4));
        $firstDayMonth[4]=$firstDayMonth4;
        $lastDayMonth[4]=$lastDayMonth4;

        $firstDayMonth5=date("Y")."-05-01";
        $lastDayMonth5=date('Y-m-t', strtotime($firstDayMonth5));
        $firstDayMonth[5]=$firstDayMonth5;
        $lastDayMonth[5]=$lastDayMonth5;

        $firstDayMonth6=date("Y")."-06-01";
        $lastDayMonth6=date('Y-m-t', strtotime($firstDayMonth6));
        $firstDayMonth[6]=$firstDayMonth6;
        $lastDayMonth[6]=$lastDayMonth6;

        $firstDayMonth7=date("Y")."-07-01";
        $lastDayMonth7=date('Y-m-t', strtotime($firstDayMonth7));
        $firstDayMonth[7]=$firstDayMonth7;
        $lastDayMonth[7]=$lastDayMonth7;

        $firstDayMonth8=date("Y")."-08-01";
        $lastDayMonth8=date('Y-m-t', strtotime($firstDayMonth8));
        $firstDayMonth[8]=$firstDayMonth8;
        $lastDayMonth[8]=$lastDayMonth8;

        $firstDayMonth9=date("Y")."-09-01";
        $lastDayMonth9=date('Y-m-t', strtotime($firstDayMonth9));
        $firstDayMonth[9]=$firstDayMonth9;
        $lastDayMonth[9]=$lastDayMonth9;

        $firstDayMonth10=date("Y")."-10-01";
        $lastDayMonth10=date('Y-m-t', strtotime($firstDayMonth10));
        $firstDayMonth[10]=$firstDayMonth10;
        $lastDayMonth[10]=$lastDayMonth10;

        $firstDayMonth11=date("Y")."-11-01";
        $lastDayMonth11=date('Y-m-t', strtotime($firstDayMonth11));
        $firstDayMonth[11]=$firstDayMonth11;
        $lastDayMonth[11]=$lastDayMonth11;

        $firstDayMonth12=date("Y")."-12-01";
        $lastDayMonth12=date('Y-m-t', strtotime($firstDayMonth12));
        $firstDayMonth[12]=$firstDayMonth12;
        $lastDayMonth[12]=$lastDayMonth12;

        // getting the dashboard Type value from the first lines in ajax.blade.php 
                
        if($dashboardType->value=="all" or !isset($dashboardType->value)){

            $marketing_campaigns=1;//Marketing **0.5s
            $os_statistics=1;//Marketing **0.6s
            $browsers_statistics=1; //Marketing **0.7s
            $registered_users_segments=1;//Marketing
            $revenue_timeline1=1; //Revenue **0.5s
            //  $revenue_timeline2=1; //Revenue LOAD 1~1.5 second **>3min
            $gender_statistics=1; //Marketing **0.5s
            $top_branches_revenue=1; //Revenue **0.5s
            $new_registrations_vs_returned_visits=1; //Marketing  LOAD 0.5 second **0.8s
            $smart_board=1; //Techinial LOAD 0.25 ~ 0.5 second **0.6s
            $user_countries=1;//Marketing
            $helicopterViewOnNetworks=1;//Techinial  LOAD 6.5 second **34s
            $helicopterViewOnBranches=1;//Techinial LOAD 10.5 second **33s
            $helicopterViewOnGroups=1;//Techinial  LOAD 10.5 second **33s
             
            $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',1)->get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $trackCustomersInBranches=1; //Marketing  **36s
                $trackConcurrentInBranches=1; // Marketing  and techinial ** 1s
            }

        }elseif($dashboardType->value=="internetManagement"){

            $smart_board=1; //Techinial
            $helicopterViewOnNetworks=1; //Techinial
            $helicopterViewOnBranches=1; //Techinial
            $helicopterViewOnGroups=1; //Techinial
            
            $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',1)->get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $trackCustomersInBranches=1; //Marketing 
                $trackConcurrentInBranches=1; // Marketing  and techinial
             }
        }elseif($dashboardType->value=="marketing"){

            $new_registrations_vs_returned_visits=1; //Marketing
            $gender_statistics=1; //Marketing
            $registered_users_segments=1;//Marketing
            $marketing_campaigns=1;//Marketing
            $os_statistics=1;//Marketing
            $browsers_statistics=1; //Marketing
            $user_countries=1;//Marketing
            
            $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',1)->get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $trackCustomersInBranches=1; //Marketing 
                $trackConcurrentInBranches=1; // Marketing  and techinial
            }
            
        }elseif($dashboardType->value=="revenue_stream"){

            $revenue_timeline1=1; //Revenue
            $revenue_timeline2=1; //Revenue
            $top_branches_revenue=1; //Revenue
        }elseif($dashboardType->value=="helicopterViewOnNetwork"){
            $helicopterViewOnNetworks=1;//Techinial  
        }elseif($dashboardType->value=="helicopterViewOnBranches"){
            $helicopterViewOnBranches=1; //Techinial
        }elseif($dashboardType->value=="helicopterViewOnGroups"){
            $helicopterViewOnGroups=1; //Techinial
        }elseif($dashboardType->value=="smartBoard"){
            $smart_board=1; //Techinial
        }elseif($dashboardType->value=="branchesComparison"){
            $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',1)->get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $trackCustomersInBranches=1; //Marketing 
                $trackConcurrentInBranches=1; // Marketing  and techinial
            }
        }

        // stop registered_users_segments because it takes alot of time 
        $database =  app('App\Http\Controllers\Controller')->configuration();
        if($database != "demo"){unset($registered_users_segments);}

        ?>
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Charts setup
        function (ec, limitless) {
			// Initialize charts
			// ------------------------------
			@yield('var')
            @if(isset($marketing_campaigns) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var marketing_campaigns = ec.init(document.getElementById('marketing_campaigns'), limitless); @endif

            //var basic_donut = ec.init(document.getElementById('basic_donut'), limitless);
            @if(isset($os_statistics)) var os_statistics = ec.init(document.getElementById('os_statistics'), limitless); @endif

            @if(isset($browsers_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var browsers_statistics = ec.init(document.getElementById('browsers_statistics'), limitless); @endif
            @if(isset($registered_users_segments) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var registered_users_segments = ec.init(document.getElementById('registered_users_segments'), limitless); @endif
            @if(isset($revenue_timeline1) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) var revenue_timeline1= ec.init(document.getElementById('revenue_timeline1'), limitless); @endif
            @if(isset($revenue_timeline2) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) var revenue_timeline2= ec.init(document.getElementById('revenue_timeline2'), limitless); @endif

            @if(isset($gender_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var gender_statistics = ec.init(document.getElementById('gender_statistics'), limitless); @endif

            @if(isset($top_branches_revenue) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) var top_branches_revenue = ec.init(document.getElementById('top_branches_revenue'), limitless); @endif
            @if(isset($new_registrations_vs_returned_visits) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var new_registrations_vs_returned_visits = ec.init(document.getElementById('new_registrations_vs_returned_visits'), limitless); @endif
            @if(isset($smart_board) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) var smart_board = ec.init(document.getElementById('smart_board'), limitless); @endif

            @if(isset($helicopterViewOnNetworks)) 
                @foreach(App\Network::where('state','1')->get() as $network) 
                    <?php $networkMonthExist=App\Models\RadacctNetworkMonthes::where('network_id',$network->id)->get(); ?>
                    @if(isset($networkMonthExist) and count($networkMonthExist)>0)
                        var helicopterViewOnNetworks_{{$network->id}} = ec.init(document.getElementById('helicopterViewOnNetworks_<?php echo $network->id;?>'), limitless); 
                    @endif
                @endforeach 
            @endif
            @if(isset($helicopterViewOnBranches)) 
                @foreach(App\Branches::where('state','1')->get() as $branch) 
                <?php $branchMonthExist=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get(); ?>
                @if(isset($branchMonthExist) and count($branchMonthExist)>0)
                    var helicopterViewOnBranches_{{$branch->id}} = ec.init(document.getElementById('helicopterViewOnBranches_<?php echo $branch->id;?>'), limitless); 
                @endif
                @endforeach 
            @endif
            @if(isset($helicopterViewOnGroups)) 
                @foreach(App\Groups::where('is_active','1')->where('as_system','0')->limit(3)->get() as $group)
                    <?php $groupMonthExist=App\Models\RadacctGroupMonthes::where('group_id',$group->id)->get(); ?>
                    @if(isset($groupMonthExist) and count($groupMonthExist)>0)
                        var helicopterViewOnGroups_{{$group->id}} = ec.init(document.getElementById('helicopterViewOnGroups_<?php echo $group->id;?>'), limitless); 
                    @endif
                @endforeach 
            @endif
            
            @if(isset($trackCustomersInBranches)) 
                var trackCustomersInBranches = ec.init(document.getElementById('trackCustomersInBranches'), limitless);
            @endif

            @if(isset($trackConcurrentInBranches)) 
                var trackConcurrentInBranches = ec.init(document.getElementById('trackConcurrentInBranches'), limitless);
            @endif
            
            // Charts setup
            // ------------------------------
			@yield('data')

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // Smart Board
        //
        @if(isset($smart_board) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)

            smart_board_options = {

                // Add title
                title: {
                    text: 'Smart Board',
                    subtext: 'All in one',
                    x: 'right'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: function (params) {
                        if (params.indicator2) { // is edge
                            return params.indicator2 + ': ' + params.indicator;
                        }
                        else { // is node
                            return params.name
                        }
                    }
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data:[
                    <?php
                    $getAllNetworks=App\Network::where('state','1')->get();
                    $totalUsersAllBranches=0;
                    $totalActiveUsersAllBranches=0;
                    $totalUsersAllGroups=0;
                    $totalActiveUsersAllGroups=0;
                    $justCounter=1;

                    if(isset($getAllNetworks))
                    {
                        foreach($getAllNetworks as $network){

                            // Network Branches
                            $getNetworkBranches=App\Branches::where('state','1')->where('network_id',$network->id)->get();
                            if(isset($getNetworkBranches))
                            {
                                foreach($getNetworkBranches as $branch)
                                {
                                    $getUsersBranches=App\Users::where('branch_id',$branch->id)->count();
                                    $totalUsersAllBranches+=$getUsersBranches;
                                    $getActiveUsersBranch=App\Models\UserActive::where('branch_id',$branch->id)->count();
                                    $totalActiveUsersAllBranches+=$getActiveUsersBranch;
                                    //if($justCounter!=1){echo ",";}
                                    //$justCounter++;
                                    //echo "{name: 'Branch: $branch->name $getActiveUsersBranch / $getUsersBranches'}";
                                }

                            }
                            // Group Branches
                            $getNetworkGroups=App\Groups::where('is_active','1')->where('network_id',$network->id)->where('as_system','0')->get();
                            if(isset($getNetworkGroups))
                            {
                                foreach($getNetworkGroups as $group)
                                {
                                    $getUsersGroups=App\Users::where('group_id',$group->id)->count();
                                    $totalUsersAllGroups+=$getUsersGroups;
                                    $getActiveUsersGroup=App\Models\UserActive::where('group_id',$group->id)->count();
                                    $totalActiveUsersAllGroups+=$getActiveUsersGroup;
                                    //if($justCounter!=1){echo ",";}
                                    //$justCounter++;
                                    //echo "{name: 'G: $group->name $getActiveUsersGroup / $getUsersGroups'}";
                                }

                            }
                            // Print Network detaild
                            if($justCounter!=1){echo ",";}
                            $justCounter++;
                            echo "'$network->name Branches $totalActiveUsersAllBranches/$totalUsersAllBranches'";
                            if($justCounter!=1){echo ",";}
                            echo "'$network->name Groups $totalActiveUsersAllGroups/$totalUsersAllGroups'";
                        }
                    } ?>
                    ]
                },

                // Add series
                series: [
                    {
                        type: 'chord',
                        sort: 'ascending',
                        sortSub: 'descending',
                        showScale: false,
                        itemStyle: {
                            normal: {
                                label: {
                                    rotate: true
                                }
                            }
                        },
                        nodes: [
                        <?php
                        //$getAllNetworks=App\Network::where('state','1')->get();
                        //$totalUsersAllBranches=0;
                        //$totalActiveUsersAllBranches=0;
                        //$totalUsersAllGroups=0;
                        //$totalActiveUsersAllGroups=0;
                        $justCounter=1;

                        if(isset($getAllNetworks))
                        {
                            foreach($getAllNetworks as $network){

                                // Network Branches
                                $getNetworkBranches=App\Branches::where('state','1')->where('network_id',$network->id)->get();
                                if(isset($getNetworkBranches))
                                {
                                    foreach($getNetworkBranches as $branch)
                                    {
                                        $getUsersBranches=App\Users::where('branch_id',$branch->id)->count();
                                        //$totalUsersAllBranches+=$getUsersBranches;
                                        $getActiveUsersBranch=App\Models\UserActive::where('branch_id',$branch->id)->count();
                                        //$totalActiveUsersAllBranches+=$getActiveUsersBranch;
                                        if($justCounter!=1){echo ",";}
                                        $justCounter++;
                                        echo "{name: 'B: $branch->name $getActiveUsersBranch/$getUsersBranches'}";
                                    }

                                }
                                // Group Branches
                                $getNetworkGroups=App\Groups::where('is_active','1')->where('network_id',$network->id)->where('as_system','0')->get();
                                if(isset($getNetworkGroups))
                                {
                                    foreach($getNetworkGroups as $group)
                                    {
                                        $getUsersGroups=App\Users::where('group_id',$group->id)->count();
                                        //$totalUsersAllGroups+=$getUsersGroups;
                                        $getActiveUsersGroup=App\Models\UserActive::where('group_id',$group->id)->count();
                                        //$totalActiveUsersAllGroups+=$getActiveUsersGroup;
                                        if($justCounter!=1){echo ",";}
                                        $justCounter++;
                                        echo "{name: 'G: $group->name $getActiveUsersGroup/$getUsersGroups'}";
                                    }

                                }
                                // Print Network detaild
                                if($justCounter!=1){echo ",";}
                                $justCounter++;
                                echo "{name: '$network->name Branches $totalActiveUsersAllBranches/$totalUsersAllBranches'}";
                                if($justCounter!=1){echo ",";}
                                echo "{name: '$network->name Groups $totalActiveUsersAllGroups/$totalUsersAllGroups'}";
                            }
                        } ?>
                        ],
                        links: [
                        <?php
                        //$getAllNetworks=App\Network::where('state','1')->get();
                        //$totalUsersAllBranches=0;
                        //$totalActiveUsersAllBranches=0;
                        //$totalUsersAllGroups=0;
                        //$totalActiveUsersAllGroups=0;
                        $justCounter=1;

                        if(isset($getAllNetworks))
                        {
                            foreach($getAllNetworks as $network){

                                // Network Branches
                                $getNetworkBranches=App\Branches::where('state','1')->where('network_id',$network->id)->get();
                                if(isset($getNetworkBranches))
                                {
                                    foreach($getNetworkBranches as $branch)
                                    {
                                        $getUsersBranches=App\Users::where('branch_id',$branch->id)->count();
                                        //$totalUsersAllBranches+=$getUsersBranches;
                                        $getActiveUsersBranch=App\Models\UserActive::where('branch_id',$branch->id)->count();
                                        //$totalActiveUsersAllBranches+=$getActiveUsersBranch;
                                        if($justCounter!=1){echo ",";}
                                        $justCounter++;
                                        echo "{source: '$network->name Branches $totalActiveUsersAllBranches/$totalUsersAllBranches', target: 'B: $branch->name $getActiveUsersBranch/$getUsersBranches', weight: 0.9, name: 'Effectiveness'}";
                                        if($justCounter!=1){echo ",";}
                                        echo "{target: '$network->name Branches $totalActiveUsersAllBranches/$totalUsersAllBranches', source: 'B: $branch->name $getActiveUsersBranch/$getUsersBranches', weight: 1}";
                                    }

                                }
                                // Group Branches
                                $getNetworkGroups=App\Groups::where('is_active','1')->where('network_id',$network->id)->get();
                                if(isset($getNetworkGroups))
                                {
                                    foreach($getNetworkGroups as $group)
                                    {
                                        $getUsersGroups=App\Users::where('group_id',$group->id)->count();
                                        //$totalUsersAllGroups+=$getUsersGroups;
                                        $getActiveUsersGroup=App\Models\UserActive::where('group_id',$group->id)->count();
                                        //$totalActiveUsersAllGroups+=$getActiveUsersGroup;
                                        if($justCounter!=1){echo ",";}
                                        $justCounter++;
                                        echo "{source: '$network->name Groups $totalActiveUsersAllGroups/$totalUsersAllGroups', target: 'G: $group->name $getActiveUsersGroup/$getUsersGroups', weight: 0.9, name: 'Effectiveness'}";
                                        if($justCounter!=1){echo ",";}
                                        echo "{target: '$network->name Groups $totalActiveUsersAllGroups/$totalUsersAllGroups', source: 'G: $group->name $getActiveUsersGroup/$getUsersGroups', weight: 1}";
                                    }
                                }
                            }
                        } ?>

                        ]
                    }
                ]
            };


        @endif

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // Top Branches Revenue
        //
        @if(isset($top_branches_revenue) && App\Settings::where('type', 'commercial_enable')->value('state') == 1)
            <?php
			$totalChargedCardOperation = App\History::where('operation','Charged card')->sum('package_price');
			$totalResellerOperation = App\History::where('operation','reseller_charge_package')->sum('package_price');
			$totalPaypalOperation = App\History::where('operation','paypal_charge_card')->sum('package_price');
			 
			//$AllBranchesRevenue=App\History::where('operation','Charged card')->orWhere('operation','reseller_charge_package')->orWhere('operation','paypal_charge_card')->sum('package_price'); 
			$AllBranchesRevenue= $totalChargedCardOperation + $totalResellerOperation + $totalPaypalOperation;
			if(!isset($AllBranchesRevenue)){$AllBranchesRevenue=0;}
            $getAllBranches=App\Branches::where('state','1')->get();
            $currency=App\Settings::where('type','currency')->value('value');
            ?>

            top_branches_revenue_options = {

                // Add colors
                color: [
                    'rgba(255, 69, 0, 0.5)',
                    'rgba(255, 150, 0, 0.5)',
                    'rgba(255, 200, 0, 0.5)',
                    'rgba(155, 200, 50, 0.5)',
                    'rgba(55, 200, 100, 0.5)'
                ],

                // Add title
                title: {
                    text: 'Identify Winner Branch',
                    subtext: 'Total Revenue: {{$AllBranchesRevenue}}{{$currency}}',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c}%"
                },

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    y: 75,
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

                // Add legend
                legend: {
                    data: [
                        <?php
                        if(isset($getAllBranches))
                        {
                            $justCounter=1;
                            foreach ($getAllBranches as $branch)
                            {
                                $currentBranchRevenue=App\History::where('branch_id',$branch->id)->where(function ($query) {
                                $query->orWhere('operation', 'Charged card')
                                    ->orWhere('operation', 'reseller_charge_package')
                                    ->orWhere('operation', 'paypal_charge_card');
                                })->sum('package_price');
                                //if(!isset($currentBranchRevenue)){$currentBranchRevenue=0;}
                                if($justCounter==1){echo "'"."$branch->name $currentBranchRevenue $currency"."'";}
                                else{echo ",'"."$branch->name $currentBranchRevenue $currency"."'";}
                                $justCounter++;
                            }
                        }
                        ?>
                    ],
                    orient: 'vertical',
                    x: 'left',
                    y: 75
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [
                    {
                        name: 'Expected',
                        type: 'funnel',
                        y: '17.5%',
                        x: '25%',
                        x2: '25%',
                        width: '50%',
                        height: '80%',
                        itemStyle: {
                            normal: {
                                label: {
                                    formatter: '{b}'
                                },
                                labelLine: {
                                    show: false
                                }
                            },
                            emphasis: {
                                label: {
                                    position: 'inside',
                                    formatter: '{b}: {c}%'
                                }
                            }
                        },
                        data: [
                            <?php
                            if(isset($getAllBranches))
                            {
                                $justCounter=1;
                                foreach ($getAllBranches as $branch)
                                {
                                    $currentBranchRevenue=App\History::where('branch_id',$branch->id)->where(function ($query) {
                                    $query->orWhere('operation', 'Charged card')
                                        ->orWhere('operation', 'reseller_charge_package')
                                        ->orWhere('operation', 'paypal_charge_card');
                                    })->sum('package_price');
                                    if(!isset($AllBranchesRevenue) or $AllBranchesRevenue==0 or !isset($currentBranchRevenue) or $currentBranchRevenue==0){$currentBranchesRevenuePercentage=0;}
                                    else{$currentBranchesRevenuePercentage=round(($currentBranchRevenue/$AllBranchesRevenue) *100,0);}

                                    if($justCounter!=1){echo ",";}
                                    echo "{value: $currentBranchesRevenuePercentage, name: '$branch->name $currentBranchRevenue $currency'}";
                                    $justCounter++;
                                }
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'Result',
                        type: 'funnel',
                        y: '17.5%',
                        x: '25%',
                        x2: '25%',
                        width: '50%',
                        height: '80%',
                        maxSize: '80%',
                        itemStyle: {
                            normal: {
                                borderColor: '#fff',
                                borderWidth: 2,
                                label: {
                                    position: 'inside',
                                    formatter: '{c}%',
                                    textStyle: {
                                        color: '#fff'
                                    }
                                }
                            },
                            emphasis: {
                                label: {
                                    position: 'inside',
                                    formatter: '{b}: {c}%'
                                }
                            }
                        },
                        data: [
                            <?php
                            //$getAllBranches=App\Branches::where('state','1')->get();
                            //$AllBranchesRevenue=App\History::where('operation','Charged card')->orWhere('operation','reseller_charge_package')->orWhere('operation','paypal_charge_card')->sum('package_price');
                            if(isset($getAllBranches))
                            {
                                $justCounter=1;
                                foreach ($getAllBranches as $branch)
                                {
                                    $currentBranchRevenue=App\History::where('branch_id',$branch->id)->where(function ($query) {
                                    $query->orWhere('operation', 'Charged card')
                                        ->orWhere('operation', 'reseller_charge_package')
                                        ->orWhere('operation', 'paypal_charge_card');
                                    })->sum('package_price');
                                    if(!isset($AllBranchesRevenue) or $AllBranchesRevenue==0 or !isset($currentBranchRevenue) or $currentBranchRevenue==0){$currentBranchesRevenuePercentage=0;}
                                    else{$currentBranchesRevenuePercentage=round(($currentBranchRevenue/$AllBranchesRevenue) *100,0);}

                                    if($justCounter!=1){echo ",";}
                                    echo "{value: $currentBranchesRevenuePercentage, name: '$branch->name $currentBranchRevenue $currency'}";
                                    $justCounter++;
                                }
                            }
                            ?>
                        ]
                    }
                ]
            };
        @endif
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // new_registrations_vs_returned_visits - All Branches
        //
        @if(isset($new_registrations_vs_returned_visits) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
            <?php

            $getAllBranches=App\Branches::where('state','1')->get();

            // New Registrations
            $firstDayCurrentMonth=date("Y-m")."-01 00:00:00";
            $lastDayCurrentMonth=date('Y-m-t', strtotime($firstDayCurrentMonth))." 23:59:59";
            $totalNewRegistrations=App\Users::whereBetween('created_at',[$firstDayCurrentMonth, $lastDayCurrentMonth])->count(); if(!isset($totalNewRegistrations)){$totalNewRegistrations=0;}

            // Returned Visitors
            $firstDayCurrentMonthFormatB=date("Y-m")."-01";
            $lastDayCurrentMonthFormatB=date('Y-m-t', strtotime($firstDayCurrentMonthFormatB));
            $totalReturnVisits=array();
            if(isset($getAllBranches))
            {
                $getTotalReturnVisits = App\Models\UsersRadacct::whereBetween('dates',[$firstDayCurrentMonthFormatB, $lastDayCurrentMonthFormatB])->get();
                if(isset($getTotalReturnVisits))
                {
                    foreach($getTotalReturnVisits as $returnedUser)
                    {
                        $totalReturnVisits[$returnedUser->u_id]=1;
                    }
                }
            }
            if(isset($totalReturnVisits))
            {
                $finalTotalReturnVisits=count($totalReturnVisits);
            }else{
                $finalTotalReturnVisits=0;
            }



            ?>

            new_registrations_vs_returned_visits_options = {

                // Add title
                title: {
                    text: 'Customers Loyalty',
                    subtext: 'New Users: {{$totalNewRegistrations}}, Returned Visitors: {{$finalTotalReturnVisits}}',
                    y: 100
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c}%"
                },

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    y: 'center',
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

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    y: '40%',
                    data: [
                    <?php
                    if($getAllBranches)
                    {
                        $justCounter=1;
                        foreach($getAllBranches as $branch)
                        {
                            $currentBranchNewRegistrations=App\Users::where('branch_id',$branch->id)->whereBetween('created_at',[$firstDayCurrentMonth, $lastDayCurrentMonth])->count(); if(!isset($currentBranchNewRegistrations)){$currentBranchNewRegistrations=0;}
                            echo "'Reg: $branch->name"." ".$currentBranchNewRegistrations."',";
                        }
                        foreach($getAllBranches as $branch)
                        {
                            $currentBranchVisitors=array();
                            if($justCounter==1){echo "''";}
                            $getTotalReturnVisits = App\Models\UsersRadacct::where('branch_id',$branch->id)->whereBetween('dates',[$firstDayCurrentMonthFormatB, $lastDayCurrentMonthFormatB])->get();
                                if(isset($getTotalReturnVisits))
                                {
                                    foreach($getTotalReturnVisits as $returnedUser)
                                    {
                                        $currentBranchVisitors[$returnedUser->u_id]=1;
                                    }
                                }
                                if(isset($currentBranchVisitors))
                                {$finalCurrentBranchVisitors=count($currentBranchVisitors);}
                                else{$finalCurrentBranchVisitors=0;}

                            echo ",'Visits: $branch->name"." ".$finalCurrentBranchVisitors."'";
                            $justCounter++;
                            unset($currentBranchVisitors);
                            unset($finalCurrentBranchVisitors);
                        }
                    }
                    ?>
                    ]
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [
                    {
                        name: 'Total New Registrations: {{$totalNewRegistrations}}',
                        type: 'funnel',
                        x: '35%',
                        width: '40%',
                        height: '43%',
                        y: '3%',
                        itemStyle: {
                            normal: {
                                label: {
                                    position: 'left'
                                }
                            },
                            emphasis: {
                                label: {
                                    position: 'inside',
                                    formatter: '{c}%'
                                }
                            }
                        },
                        data: [
                            <?php
                            if(isset($getAllBranches))
                            {
                                $justCounter=1;
                                foreach($getAllBranches as $branch)
                                {
                                    $currentBranchNewRegistrations=App\Users::where('branch_id',$branch->id)->whereBetween('created_at',[$firstDayCurrentMonth, $lastDayCurrentMonth])->count(); if(!isset($currentBranchNewRegistrations)){$currentBranchNewRegistrations=0;}
                                    if(!isset($totalNewRegistrations) or $totalNewRegistrations==0){$currentBranchNewRegistrationsPersentage=0;}else{$currentBranchNewRegistrationsPersentage=round(($currentBranchNewRegistrations/$totalNewRegistrations)*100,0); if(!isset($currentBranchNewRegistrationsPersentage)){$currentBranchNewRegistrationsPersentage=0;}}
                                    if($justCounter!=1){echo ",";}
                                    echo "{value: $currentBranchNewRegistrationsPersentage";
                                    echo ",name: 'Reg: $branch->name"." ".$currentBranchNewRegistrations."'}";
                                    $justCounter++;
                                }
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'Total Returned Visitors: {{$finalTotalReturnVisits}}',
                        type: 'funnel',
                        x: '35%',
                        width: '40%',
                        height: '43%',
                        y: '55%',
                        sort: 'ascending',
                        itemStyle: {
                            normal: {
                                label: {
                                    position: 'right'
                                }
                            },
                            emphasis: {
                                label: {
                                    position: 'inside',
                                    formatter: '{c}%'
                                }
                            }
                        },
                        data: [
                            <?php
                            if(isset($getAllBranches))
                            {
                                $justCounter=1;
                                foreach($getAllBranches as $branch)
                                {
                                    $currentBranchVisitors=array();
                                    $getTotalReturnVisits = App\Models\UsersRadacct::where('branch_id',$branch->id)->whereBetween('dates',[$firstDayCurrentMonthFormatB, $lastDayCurrentMonthFormatB])->get();
                                        if(isset($getTotalReturnVisits))
                                        {
                                            foreach($getTotalReturnVisits as $returnedUser)
                                            {
                                                $currentBranchVisitors[$returnedUser->u_id]=1;
                                            }
                                        }
                                        if(isset($currentBranchVisitors))
                                        {$finalCurrentBranchVisitors=count($currentBranchVisitors);}
                                        else{$finalCurrentBranchVisitors=0;}
                                    // percentage
                                    if(!isset($finalCurrentBranchVisitors) or $finalCurrentBranchVisitors==0){$currentBranchVisitorsPercentage=0;}else{$currentBranchVisitorsPercentage=round(($finalCurrentBranchVisitors/$finalTotalReturnVisits)*100,0); if(!isset($currentBranchVisitorsPercentage)){$currentBranchVisitorsPercentage=0;}}
                                    if($justCounter!=1){echo ",";}
                                    echo "{value: $currentBranchVisitorsPercentage";
                                    echo ",name: 'Visits: $branch->name"." ".$finalCurrentBranchVisitors."'}";
                                    $justCounter++;
                                    unset($currentBranchVisitors);
                                    unset($finalCurrentBranchVisitors);

                                }
                            }
                            ?>
                        ]
                    }
                ]
            };

        @endif

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // Gender statistics options
        //
        @if(isset($gender_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) 
            // Data style
            var dataStyle = {
                normal: {
                    label: {show: false},
                    labelLine: {show: false}
                }
            };

            // Placeholder style
            var placeHolderStyle = {
                normal: {
                    color: 'rgba(0,0,0,0)',
                    label: {show: false},
                    labelLine: {show: false}
                },
                emphasis: {
                    color: 'rgba(0,0,0,0)'
                }
            };

            // Gender statistics
            gender_statistics_options = {
                <?php
                    $allUsersCount=App\Users::count();
                    if($allUsersCount != 0){
                        $maleCount=App\Users::where('u_gender','1')->count();
                        $malePercentage=round(($maleCount/$allUsersCount) *100,0);
                        $malePercentageRemining=100-$malePercentage;
                        $femaleCount=App\Users::where('u_gender','0')->count();
                        $femalePercentage=round(($femaleCount/$allUsersCount) *100,0);
                        $femalePercentageRemining=100-$malePercentage;
                        $unknownUsersGender=App\Users::where('u_gender','2')->count();
                        $unknownUsersPercentage=round(($unknownUsersGender/$allUsersCount) *100,0);
                        $unknownPercentageRemining=100-$malePercentage;
                    }
                    if(!isset($malePercentage)) { $malePercentage = 0;}
                    if(!isset($malePercentageRemining)) { $malePercentageRemining = 0;}
                    if(!isset($femalePercentage)) { $femalePercentage = 0;}
                    if(!isset($femalePercentageRemining)) { $femalePercentageRemining = 0;}
                    if(!isset($unknownUsersPercentage)) { $unknownUsersPercentage = 0;}
                    if(!isset($unknownPercentageRemining)) { $unknownPercentageRemining = 0;}
                ?>
                // Add title
                title: {
                    text: 'Gender!',
                    subtext: '',
                    x: 'center',
                    y: 'center',
                    itemGap: 10,
                    textStyle: {
                        color: 'rgba(30,144,255,0.8)',
                        fontSize: 19,
                        fontWeight: '500'
                    }
                },

                // Add tooltip
                tooltip: {
                    show: true,
                    //formatter: "{a} <br/>{b}: {c} ({d}%)"
                    formatter: "{a} <br/>{b} users"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: document.getElementById('gender_statistics').offsetWidth / 2,
                    y: 30,
                    x: '55%',
                    itemGap: 15,
                    data: ['{{$malePercentage}}% Male','{{$femalePercentage}}% Female','{{$unknownUsersPercentage}}% Unknown']
                },

                // Add series
                series: [
                    {
                        name: 'Male Gender',
                        type: 'pie',
                        clockWise: false,
                        radius: ['75%', '90%'],
                        itemStyle: dataStyle,
                        data: [
                            {
                                value: {{$malePercentage}},
                                name: '{{$malePercentage}}% Male'
                            },
                            {
                                value:{{$malePercentageRemining}},
                                name: '',
                                itemStyle: placeHolderStyle
                            }
                        ]
                    },

                    {
                        name: 'Female Gender',
                        type:'pie',
                        clockWise: false,
                        radius: ['60%', '75%'],
                        itemStyle: dataStyle,
                        data: [
                            {
                                value: {{$femalePercentage}},
                                name: '{{$femalePercentage}}% Female'
                            },
                            {
                                value: {{$femalePercentageRemining}},
                                name: '',
                                itemStyle: placeHolderStyle
                            }
                        ]
                    },

                    {
                        name: 'Unknown Gender',
                        type: 'pie',
                        clockWise: false,
                        radius: ['45%', '60%'],
                        itemStyle: dataStyle,
                        data: [
                            {
                                value: {{$unknownUsersPercentage}},
                                name: '{{$unknownUsersPercentage}}% Unknown'
                            },
                            {
                                value: {{$unknownPercentageRemining}},
                                name: '',
                                itemStyle: placeHolderStyle
                            }
                        ]
                    }
                ]
            };

        @endif
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        @if(isset($revenue_timeline1) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) 

            <!-- revenue_timeline1  -->
            <?php

            $percentageOfBuyingPackages=array();
            $resellerRevenue=array();
            $paypalRevenue=array();
            $totalRevenue=array();

            $packagesRevenue[1]=0;$resellerRevenue[1]=0;$paypalRevenue[1]=0;$totalRevenue[1]=0;$packagesRevenueCounter[1]=0;$resellersRevenueCounter[1]=0;$paypalRevenueCounter[1]=0;
            $packagesRevenue[2]=0;$resellerRevenue[2]=0;$paypalRevenue[2]=0;$totalRevenue[2]=0;$packagesRevenueCounter[2]=0;$resellersRevenueCounter[2]=0;$paypalRevenueCounter[2]=0;
            $packagesRevenue[3]=0;$resellerRevenue[3]=0;$paypalRevenue[3]=0;$totalRevenue[3]=0;$packagesRevenueCounter[3]=0;$resellersRevenueCounter[3]=0;$paypalRevenueCounter[3]=0;
            $packagesRevenue[4]=0;$resellerRevenue[4]=0;$paypalRevenue[4]=0;$totalRevenue[4]=0;$packagesRevenueCounter[4]=0;$resellersRevenueCounter[4]=0;$paypalRevenueCounter[4]=0;
            $packagesRevenue[5]=0;$resellerRevenue[5]=0;$paypalRevenue[5]=0;$totalRevenue[5]=0;$packagesRevenueCounter[5]=0;$resellersRevenueCounter[5]=0;$paypalRevenueCounter[5]=0;
            $packagesRevenue[6]=0;$resellerRevenue[6]=0;$paypalRevenue[6]=0;$totalRevenue[6]=0;$packagesRevenueCounter[6]=0;$resellersRevenueCounter[6]=0;$paypalRevenueCounter[6]=0;
            $packagesRevenue[7]=0;$resellerRevenue[7]=0;$paypalRevenue[7]=0;$totalRevenue[7]=0;$packagesRevenueCounter[7]=0;$resellersRevenueCounter[7]=0;$paypalRevenueCounter[7]=0;
            $packagesRevenue[8]=0;$resellerRevenue[8]=0;$paypalRevenue[8]=0;$totalRevenue[8]=0;$packagesRevenueCounter[8]=0;$resellersRevenueCounter[8]=0;$paypalRevenueCounter[8]=0;
            $packagesRevenue[9]=0;$resellerRevenue[9]=0;$paypalRevenue[9]=0;$totalRevenue[9]=0;$packagesRevenueCounter[9]=0;$resellersRevenueCounter[9]=0;$paypalRevenueCounter[9]=0;
            $packagesRevenue[10]=0;$resellerRevenue[10]=0;$paypalRevenue[10]=0;$totalRevenue[10]=0;$packagesRevenueCounter[10]=0;$resellersRevenueCounter[10]=0;$paypalRevenueCounter[10]=0;
            $packagesRevenue[11]=0;$resellerRevenue[11]=0;$paypalRevenue[11]=0;$totalRevenue[11]=0;$packagesRevenueCounter[11]=0;$resellersRevenueCounter[11]=0;$paypalRevenueCounter[11]=0;
            $packagesRevenue[12]=0;$resellerRevenue[12]=0;$paypalRevenue[12]=0;$totalRevenue[12]=0;$packagesRevenueCounter[12]=0;$resellersRevenueCounter[12]=0;$paypalRevenueCounter[12]=0;



            //$currDate="2016-10-18";

            $getAllRevenues = App\History::where('operation','Charged card')->orWhere('operation','reseller_charge_package')->orWhere('operation','paypal_charge_card')->whereBetween('add_date',[$firstDayMonth1, $lastDayMonth12])->get();
            foreach($getAllRevenues as $revenues){

                for($i=1;$i<=12;$i++)
                {
                    if(date($revenues->add_date) >= date($firstDayMonth[$i]) and date($revenues->add_date) <= date($lastDayMonth[$i]))
                    {
                        if($revenues->operation=="Charged card"){$packagesRevenueCounter[$i]++;$packagesRevenue[$i]+=$revenues->package_price; $totalRevenue[$i]+=$revenues->package_price;}
                        if($revenues->operation=="reseller_charge_package"){$resellersRevenueCounter[$i]++;$resellerRevenue[$i]+=$revenues->package_price; $totalRevenue[$i]+=$revenues->package_price;}
                        if($revenues->operation=="paypal_charge_card"){$paypalRevenueCounter[$i]++;$paypalRevenue[$i]+=$revenues->package_price; $totalRevenue[$i]+=$revenues->package_price;}
                    }
                }
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ?>
            revenue_timeline1_options = {

                        // Setup timeline
                        timeline: {
                            data: [
                                '{{date("Y")}}-01-01', '{{date("Y")}}-02-01', '{{date("Y")}}-03-01', '{{date("Y")}}-04-01', '{{date("Y")}}-05-01',
                                { name:'{{date("Y")}}-06-01', symbol: 'emptyStar2', symbolSize: 8 },
                                '{{date("Y")}}-07-01', '{{date("Y")}}-08-01', '{{date("Y")}}-09-01', '{{date("Y")}}-10-01', '{{date("Y")}}-11-01',
                                { name:'{{date("Y")}}-12-01', symbol: 'star2', symbolSize: 8 }
                            ],
                            x: 10,
                            x2: 10,
                            label: {
                                formatter: function(s) {
                                    return s.slice(0, 7);
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
                                    data: ['Total Revenue','Cards','Cards Count','Reseller','Reseller Count','PayPal','PayPal Count']
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
                                    data: ['Internet Revenue stream']
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
                                        name: 'Total Revenue',
                                        type: 'bar',
                                        yAxisIndex: 1,
                                        data: [{{$totalRevenue[1]}}]
                                    },
                                    {
                                        name: 'Cards',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$packagesRevenue[1]}}]
                                    },
                                    {
                                        name: 'Cards Count',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$packagesRevenueCounter[1]}}]
                                    },
                                    {
                                        name: 'Reseller',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$resellerRevenue[1]}}]
                                    },
                                    {
                                        name: 'Reseller Count',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$resellersRevenueCounter[1]}}]
                                    },
                                    {
                                        name: 'PayPal',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$paypalRevenue[1]}}]
                                    },
                                    {
                                        name: 'PayPal Count',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$paypalRevenueCounter[1]}}]
                                    }
                                ]
                            },
                            // third week
                            {
                                series: [
                                    {data: [{{$totalRevenue[2]}}]},
                                    {data: [{{$packagesRevenue[2]}}]},
                                    {data: [{{$packagesRevenueCounter[2]}}]},
                                    {data: [{{$resellerRevenue[2]}}]},
                                    {data: [{{$resellersRevenueCounter[2]}}]},
                                    {data: [{{$paypalRevenue[2]}}]},
                                    {data: [{{$paypalRevenueCounter[2]}}]}

                                ]
                            },

                            // second week
                            {
                                series: [
                                    {data: [{{$totalRevenue[3]}}]},
                                    {data: [{{$packagesRevenue[3]}}]},
                                    {data: [{{$packagesRevenueCounter[3]}}]},
                                    {data: [{{$resellerRevenue[3]}}]},
                                    {data: [{{$resellersRevenueCounter[3]}}]},
                                    {data: [{{$paypalRevenue[3]}}]},
                                    {data: [{{$paypalRevenueCounter[3]}}]}
                                    ]
                            },

                            // first week
                            {
                                series: [
                                    {data: [{{$totalRevenue[4]}}]},
                                    {data: [{{$packagesRevenue[4]}}]},
                                    {data: [{{$packagesRevenueCounter[4]}}]},
                                    {data: [{{$resellerRevenue[4]}}]},
                                    {data: [{{$resellersRevenueCounter[4]}}]},
                                    {data: [{{$paypalRevenue[4]}}]},
                                    {data: [{{$paypalRevenueCounter[4]}}]}
                                ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[5]}}]},
                                    {data: [{{$packagesRevenue[5]}}]},
                                    {data: [{{$packagesRevenueCounter[5]}}]},
                                    {data: [{{$resellerRevenue[5]}}]},
                                    {data: [{{$resellersRevenueCounter[5]}}]},
                                    {data: [{{$paypalRevenue[5]}}]},
                                    {data: [{{$paypalRevenueCounter[5]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[6]}}]},
                                    {data: [{{$packagesRevenue[6]}}]},
                                    {data: [{{$packagesRevenueCounter[6]}}]},
                                    {data: [{{$resellerRevenue[6]}}]},
                                    {data: [{{$resellersRevenueCounter[6]}}]},
                                    {data: [{{$paypalRevenue[6]}}]},
                                    {data: [{{$paypalRevenueCounter[6]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[7]}}]},
                                    {data: [{{$packagesRevenue[7]}}]},
                                    {data: [{{$packagesRevenueCounter[7]}}]},
                                    {data: [{{$resellerRevenue[7]}}]},
                                    {data: [{{$resellersRevenueCounter[7]}}]},
                                    {data: [{{$paypalRevenue[7]}}]},
                                    {data: [{{$paypalRevenueCounter[7]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[8]}}]},
                                    {data: [{{$packagesRevenue[8]}}]},
                                    {data: [{{$packagesRevenueCounter[8]}}]},
                                    {data: [{{$resellerRevenue[8]}}]},
                                    {data: [{{$resellersRevenueCounter[8]}}]},
                                    {data: [{{$paypalRevenue[8]}}]},
                                    {data: [{{$paypalRevenueCounter[8]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[9]}}]},
                                    {data: [{{$packagesRevenue[9]}}]},
                                    {data: [{{$packagesRevenueCounter[9]}}]},
                                    {data: [{{$resellerRevenue[9]}}]},
                                    {data: [{{$resellersRevenueCounter[9]}}]},
                                    {data: [{{$paypalRevenue[9]}}]},
                                    {data: [{{$paypalRevenueCounter[9]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[10]}}]},
                                    {data: [{{$packagesRevenue[10]}}]},
                                    {data: [{{$packagesRevenueCounter[10]}}]},
                                    {data: [{{$resellerRevenue[10]}}]},
                                    {data: [{{$resellersRevenueCounter[10]}}]},
                                    {data: [{{$paypalRevenue[10]}}]},
                                    {data: [{{$paypalRevenueCounter[10]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[11]}}]},
                                    {data: [{{$packagesRevenue[11]}}]},
                                    {data: [{{$packagesRevenueCounter[11]}}]},
                                    {data: [{{$resellerRevenue[11]}}]},
                                    {data: [{{$resellersRevenueCounter[11]}}]},
                                    {data: [{{$paypalRevenue[11]}}]},
                                    {data: [{{$paypalRevenueCounter[11]}}]}
                                    ]
                            },
                            {
                                series: [
                                    {data: [{{$totalRevenue[12]}}]},
                                    {data: [{{$packagesRevenue[12]}}]},
                                    {data: [{{$packagesRevenueCounter[12]}}]},
                                    {data: [{{$resellerRevenue[12]}}]},
                                    {data: [{{$resellersRevenueCounter[12]}}]},
                                    {data: [{{$paypalRevenue[12]}}]},
                                    {data: [{{$paypalRevenueCounter[12]}}]}
                                    ]
                            }
                        ]
                    };
        <!-- End Of revenue_timeline1 -------------------------------------------------------------------------------------------------------------------- -->
        @endif

        @if(isset($revenue_timeline2) && App\Settings::where('type', 'commercial_enable')->value('state') == 1)

            <!-- revenue_timeline2 code  -->
            <?php

            $percentageOfBuyingPackages=array();
            $countOfBuyingPackages=array();
            $newRegistrations=array();
            $soldPackages=array();
            $totalQuotaUsage=array();
            $downloadUsage=array();
            $uploadUsage=array();

            $percentageOfBuyingPackages[1]=0;$countOfBuyingPackages[1]=0;$newRegistrations[1]=0;$soldPackages[1]=0;$totalQuotaUsage[1]=0;$downloadUsage[1]=0;$uploadUsage[1]=0;
            $percentageOfBuyingPackages[2]=0;$countOfBuyingPackages[2]=0;$newRegistrations[2]=0;$soldPackages[2]=0;$totalQuotaUsage[2]=0;$downloadUsage[2]=0;$uploadUsage[2]=0;
            $percentageOfBuyingPackages[3]=0;$countOfBuyingPackages[3]=0;$newRegistrations[3]=0;$soldPackages[3]=0;$totalQuotaUsage[3]=0;$downloadUsage[3]=0;$uploadUsage[3]=0;
            $percentageOfBuyingPackages[4]=0;$countOfBuyingPackages[4]=0;$newRegistrations[4]=0;$soldPackages[4]=0;$totalQuotaUsage[4]=0;$downloadUsage[4]=0;$uploadUsage[4]=0;
            $percentageOfBuyingPackages[5]=0;$countOfBuyingPackages[5]=0;$newRegistrations[5]=0;$soldPackages[5]=0;$totalQuotaUsage[5]=0;$downloadUsage[5]=0;$uploadUsage[5]=0;
            $percentageOfBuyingPackages[6]=0;$countOfBuyingPackages[6]=0;$newRegistrations[6]=0;$soldPackages[6]=0;$totalQuotaUsage[6]=0;$downloadUsage[6]=0;$uploadUsage[6]=0;
            $percentageOfBuyingPackages[7]=0;$countOfBuyingPackages[7]=0;$newRegistrations[7]=0;$soldPackages[7]=0;$totalQuotaUsage[7]=0;$downloadUsage[7]=0;$uploadUsage[7]=0;
            $percentageOfBuyingPackages[8]=0;$countOfBuyingPackages[8]=0;$newRegistrations[8]=0;$soldPackages[8]=0;$totalQuotaUsage[8]=0;$downloadUsage[8]=0;$uploadUsage[8]=0;
            $percentageOfBuyingPackages[9]=0;$countOfBuyingPackages[9]=0;$newRegistrations[9]=0;$soldPackages[9]=0;$totalQuotaUsage[9]=0;$downloadUsage[9]=0;$uploadUsage[9]=0;
            $percentageOfBuyingPackages[10]=0;$countOfBuyingPackages[10]=0;$newRegistrations[10]=0;$soldPackages[10]=0;$totalQuotaUsage[10]=0;$downloadUsage[10]=0;$uploadUsage[10]=0;
            $percentageOfBuyingPackages[11]=0;$countOfBuyingPackages[11]=0;$newRegistrations[11]=0;$soldPackages[11]=0;$totalQuotaUsage[11]=0;$downloadUsage[11]=0;$uploadUsage[11]=0;
            $percentageOfBuyingPackages[12]=0;$countOfBuyingPackages[12]=0;$newRegistrations[12]=0;$soldPackages[12]=0;$totalQuotaUsage[12]=0;$downloadUsage[12]=0;$uploadUsage[12]=0;


            // getting ( Percentage of buying packages  -  Count of buying packages  - New Registrations  )
            $getActiveUsers=App\Users::where('Registration_type','2')->where('u_state','1')->get();
            $counterAllUsers=0;
            /*echo " Awl Elsharh".strtotime("2016-01-10");
            echo " nos elshahr".strtotime("2016-01-15");
            echo " A5er Elsharh".strtotime("2016-01-31");*/
            if(isset($getActiveUsers))
            {
                foreach ($getActiveUsers as $user)
                {
                    $counterAllUsers++;
                    for($i=1;$i<=12;$i++)// 12 months
                    {
                        $loopDate=explode(" ",$user->created_at);
                        $userCreationDate=strtotime($loopDate[0]);
                        $firstDate=strtotime($firstDayMonth[$i]);
                        $lastDate=strtotime($lastDayMonth[$i]);

                        if($userCreationDate>=$firstDate && $userCreationDate<=$lastDate)
                        {
                            $newRegistrations[$i]++;
                        }

                        if(App\History::where('u_id',$user->u_id)->where('operation','user_charge_package')->whereBetween('add_date',[$firstDayMonth[$i], $lastDayMonth[$i]])->count() >= 1)
                        {
                            $countOfBuyingPackages[$i]++;
                            $percentageOfBuyingPackages[$i] = round(($countOfBuyingPackages[$i]/$counterAllUsers) *100,0);
                        }


                    }
                }
            }

            // getting ( Sold Packages  )
            $getAllHistories = App\History::where('operation','user_charge_package')->whereBetween('add_date',[$firstDayMonth1, $lastDayMonth12])->get();
            foreach($getAllHistories as $history){

                for($i=1;$i<=12;$i++)// 12 months
                {
                    if(date($history->add_date) >= date($firstDayMonth[$i]) and date($history->add_date) <= date($lastDayMonth[$i]))
                    {
                        if($history->operation=="user_charge_package"){$soldPackages[$i]++;}
                    }
                }
            }

            // getting ( Total Quota Usage - Download Usage - Upload Usage )
            $getAllUsage= App\Models\UsersRadacct::whereBetween('dates',[$firstDayMonth1, $lastDayMonth12])->get();
            foreach($getAllUsage as $internet){

                for($i=1;$i<=12;$i++)// 12 months
                {
                    if(date($internet->dates) >= date($firstDayMonth[$i]) and date($internet->dates) <= date($lastDayMonth[$i]))
                    {
                        $uploadUsage[$i]+=round($internet->acctinputoctets/1024/1024/1024,1);
                        $downloadUsage[$i]+=round($internet->acctoutputoctets/1024/1024/1024,1);
                        $totalQuotaUsage[$i]+=round($internet->acctinputoctets/1024/1024/1024+$internet->acctoutputoctets/1024/1024/1024,1);
                    }
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ?>

            revenue_timeline2_options = {

                        // Setup timeline
                        timeline: {
                            data: [
                                '{{date("Y")}}-01-01', '{{date("Y")}}-02-01', '{{date("Y")}}-03-01', '{{date("Y")}}-04-01', '{{date("Y")}}-05-01',
                                { name:'{{date("Y")}}-06-01', symbol: 'emptyStar2', symbolSize: 8 },
                                '{{date("Y")}}-07-01', '{{date("Y")}}-08-01', '{{date("Y")}}-09-01', '{{date("Y")}}-10-01', '{{date("Y")}}-11-01',
                                { name:'{{date("Y")}}-12-01', symbol: 'star2', symbolSize: 8 }
                            ],
                            x: 10,
                            x2: 10,
                            label: {
                                formatter: function(s) {
                                    return s.slice(0, 7);
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
                                    data: ['Percentage of buying packages','Count of buying packages','New Registrations','Sold Packages','Total Quota Usage','Download','Upload']
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
                                    data: ['Internet Revenue stream']
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
                                        name: 'Percentage of buying packages',
                                        type: 'bar',
                                        yAxisIndex: 1,
                                        data: [{{$percentageOfBuyingPackages[1]}}]
                                    },
                                    {
                                        name: 'Count of buying packages',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$countOfBuyingPackages[1]}}]
                                    },
                                    {
                                        name: 'New Registrations',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$newRegistrations[1]}}]
                                    },
                                    {
                                        name: 'Sold Packages',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$soldPackages[1]}}]
                                    },
                                    {
                                        name: 'Total Quota Usage',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$totalQuotaUsage[1]}}]
                                    },
                                    {
                                        name: 'Download',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$downloadUsage[1]}}]
                                    },
                                    {
                                        name: 'Upload',
                                        yAxisIndex: 1,
                                        type: 'bar',
                                        data: [{{$uploadUsage[1]}}]
                                    }
                                ]
                            },

                            <?php
                            for($i=2;$i<=12;$i++)// 11 months
                            {
                                echo"//$i
                                {
                                    series: [
                                        {data: [$percentageOfBuyingPackages[$i]]},
                                        {data: [$countOfBuyingPackages[$i]]},
                                        {data: [$newRegistrations[$i]]},
                                        {data: [$soldPackages[$i]]},
                                        {data: [$totalQuotaUsage[$i]]},
                                        {data: [$downloadUsage[$i]]},
                                        {data: [$uploadUsage[$i]]}
                                    ]
                                }
                                ";
                                if($i!=12){echo',';}
                            }
                            ?>

                        ]
                    };
        <!-- End Of revenue_timeline2 Counts -------------------------------------------------------------------------------------------------------------------- -->
        @endif

        // Marketing campaigns
        @if(isset($marketing_campaigns) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) 
            <!--   ----------------------------------------------------------------------------------------------------- -->

            marketing_campaigns_options = {

                // Add title
                title: {
                    text: 'Track your campaigns',
                    subtext: '',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['Email Campaigns', 'SMS Campaigns', 'WhatsApp Campaign', 'Push Notifications']
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
                                    y: '20%',
                                    width: '50%',
                                    height: '70%',
                                    funnelAlign: 'left',
                                    max: 1548
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

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                    name: 'Count',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    data: [
                        {value: {{ App\History::where('operation','email_campaign')->whereBetween('add_date',[$statisticsStartDate, $statisticsEndDate])->count() }}, name: 'Email Campaigns'},
                        {value: {{ App\History::where('operation','SMS_campaign')->whereBetween('add_date',[$statisticsStartDate, $statisticsEndDate])->count() }}, name: 'SMS Campaigns'},
                        {value: {{ App\History::where('operation','localMessages_campaign')->whereBetween('add_date',[$statisticsStartDate, $statisticsEndDate])->count() }}, name: 'WhatsApp Campaign'},
                        {value: {{ App\History::where('operation','pushNotification_campaign')->whereBetween('add_date',[$statisticsStartDate, $statisticsEndDate])->count() }}, name: 'Push Notifications'}
                    ]
                }]
            };


            //
            // Basic donut options
            //

            /*basic_donut_options = {

                // Add title
                title: {
                    text: 'Browser popularity',
                    subtext: 'Open source information',
                    x: 'center'
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['Agilecrm','Salesforce']
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
                                    y: '20%',
                                    width: '50%',
                                    height: '70%',
                                    funnelAlign: 'left',
                                    max: 1548
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

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [
                    {
                        name: 'Browsers',
                        type: 'pie',
                        radius: ['50%', '70%'],
                        center: ['50%', '57.5%'],
                        itemStyle: {
                            normal: {
                                label: {
                                    show: true
                                },
                                labelLine: {
                                    show: true
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true,
                                    formatter: '{b}' + '\n\n' + '{c} ({d}%)',
                                    position: 'center',
                                    textStyle: {
                                        fontSize: '17',
                                        fontWeight: '500'
                                    }
                                }
                            }
                        },

                        data: [
                            {value: {{ $agile_count }}, name: 'Agilecrm'},
                            {value: 310, name: 'Salesforce'}
                        ]
                    }
                ]
            };*/


            //
            // Infographic donut options
            //

            // Data style
            var dataStyle = {
                normal: {
                    label: {show: false},
                    labelLine: {show: false}
                }
            };

            // Placeholder style
            var placeHolderStyle = {
                normal: {
                    color: 'rgba(0,0,0,0)',
                    label: {show: false},
                    labelLine: {show: false}
                },
                emphasis: {
                    color: 'rgba(0,0,0,0)'
                }
            };

        @endif
        <!-- ----------------------------------------------------------------------------------------------------------------- -->
        //
        // OS statistics
        //
        @if(isset($os_statistics)) 

            os_statistics_options = {

                // Add title
                title: {
                    text: 'OS statistics',
                    subtext: 'Based on shared research',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    x: 'left',
                    y: 'top',
                    orient: 'vertical',
                    data: ['Windows OS','Windows Phone','Mac OS','IOS','Android','Chrome OS','Linux','BlackBerry']
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
                            type: ['pie', 'funnel']
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

                // Add series
                series: [
                    {
                        name: 'Online users',
                        type: 'pie',
                        radius: ['15%', '73%'],
                        center: ['50%', '57%'],
                        roseType: 'area',

                        // Funnel
                        width: '40%',
                        height: '78%',
                        x: '30%',
                        y: '17.5%',
                        max: 450,
                        sort: 'ascending',

                        data: [
                            {value: {{ App\Models\Visitors::where('os','Windows')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Windows OS'},
                            {value: {{ App\Models\Visitors::where('os','Windows Phone')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Windows Phone'},
                            {value: {{ App\Models\Visitors::where('os','OS X')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Mac OS'},
                            {value: {{ App\Models\Visitors::where('os','iOS')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'IOS'},
                            {value: {{ App\Models\Visitors::where('os','Android')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Android'},
                            {value: {{ App\Models\Visitors::where('os','Chrome OS')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Chrome OS'},
                            {value: {{ App\Models\Visitors::where('os','Linux')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Linux'},
                            {value: {{ App\Models\Visitors::where('os','BlackBerry')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'BlackBerry'}
                        ]
                    }
                ]
            };
        @endif
        <!-- --------------------------------------------------------------------------------------------------------------- -->
        //
        // Browsers statistics
        //
        @if(isset($browsers_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)

            browsers_statistics_options = {

                // Add title
                title: {
                    text: 'Browsers statistics',
                    subtext: 'Based on shared research',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    x: 'left',
                    y: 'top',
                    orient: 'vertical',
                    data: ['Firefox','Chrome','Safari','Microsoft Edge','Internet Explorer','Pocket Internet Explorer']
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
                            type: ['pie', 'funnel']
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

                // Add series
                series: [
                    {
                        name: 'Online users',
                        type: 'pie',
                        radius: ['15%', '73%'],
                        center: ['50%', '57%'],
                        roseType: 'area',

                        // Funnel
                        width: '40%',
                        height: '78%',
                        x: '30%',
                        y: '17.5%',
                        max: 450,

                        data: [

                            {value: {{ App\Models\Visitors::where('browser','Chrome')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Chrome'},
                            {value: {{ App\Models\Visitors::where('browser','Safari')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Safari'},
                            {value: {{ App\Models\Visitors::where('browser','Mozilla')->orWhere('browser','Firefox')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Firefox'},
                            {value: {{ App\Models\Visitors::where('browser','Microsoft Edge')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Microsoft Edge'},
                            {value: {{ App\Models\Visitors::where('browser','Internet Explorer')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Internet Explorer'},
                            {value: {{ App\Models\Visitors::where('browser','Pocket Internet Explorer')->whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->count() }}, name: 'Pocket Internet Explorer'}
                        ]
                    }
                ]
            };


                    //
                    // Multiple donuts options
                    //

                    // Top text label
                    var labelTop = {
                        normal: {
                            label: {
                                show: true,
                                position: 'center',
                                formatter: '{b}\n',
                                textStyle: {
                                    baseline: 'middle',
                                    fontWeight: 300,
                                    fontSize: 15
                                }
                            },
                            labelLine: {
                                show: false
                            }
                        }
                    };

                    // Format bottom label
                    var labelFromatter = {
                        normal: {
                            label: {
                                formatter: function (params) {
                                    return '\n\n' + (100 - params.value) + '%'
                                }
                            }
                        }
                    }

                    // Bottom text label
                    var labelBottom = {
                        normal: {
                            color: '#eee',
                            label: {
                                show: true,
                                position: 'center',
                                textStyle: {
                                    baseline: 'middle'
                                }
                            },
                            labelLine: {
                                show: false
                            }
                        },
                        emphasis: {
                            color: 'rgba(0,0,0,0)'
                        }
                    };

                    // Set inner and outer radius
                    var radius = [60, 75];
                    <?php
                        if(isset($registered_users_segments)){

                            $totalUsers = App\Users::whereBetween('created_at',[$statisticsStartDate." 00:00:00", $statisticsEndDate." 23:59:59"])->get();
                            $counterTotalUsers=0;
                            $counterFacebookUsers=0;
                            $counterTwitterUsers=0;
                            $counterGoogleUsers=0;
                            $counterLinkedinUsers=0;
                            $counterSignupUsers=0;
                            if(isset($totalUsers)){
                                foreach($totalUsers as $cruntUser) {
                                    $counterTotalUsers++;
                                    if(isset($cruntUser->facebook_id)){$counterFacebookUsers ++;}
                                    elseif(isset($cruntUser->twitter_id)){$counterTwitterUsers ++;}
                                    elseif(isset($cruntUser->google_id)){$counterGoogleUsers ++;}
                                    elseif(isset($cruntUser->linkedin_id)){$counterLinkedinUsers ++;}
                                    elseif(!isset($cruntUser->facebook_id) && !isset($cruntUser->linkedin_id) && !isset($cruntUser->google_id) && !isset($cruntUser->twitter_id)){$counterSignupUsers ++;}
                                }
                            }

                            $percentageFacebook=0;
                            $percentageTwitter=0;
                            $percentageGoogle=0;
                            $percentageLinkedin=0;
                            $percentageSignup=0;
                            if($counterTotalUsers!=0){
                                $percentageFacebook=round(($counterFacebookUsers/$counterTotalUsers) *100,0);
                                $percentageTwitter=round(($counterTwitterUsers/$counterTotalUsers) *100,0);
                                $percentageGoogle=round(($counterGoogleUsers/$counterTotalUsers) *100,0);
                                $percentageLinkedin=round(($counterLinkedinUsers/$counterTotalUsers) *100,0);
                                $percentageSignup=round(($counterSignupUsers/ $counterTotalUsers) *100,0);
                            }
                        }
                    ?>
        @endif
        <!-- -------------------------------------------------------------------------------------------------------------- -->
        // Registered users segments
        @if(isset($registered_users_segments) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)

                    registered_users_segments_options = {

                        // Add title
                        title: {
                            text: 'Take decision for your next campaign',
                            subtext: 'On social media chancels',
                            x: 'center'
                        },

                        // Add legend
                        legend: {
                            x: 'center',
                            y: '80%',
                            data: ['Facebook', 'Google+','Twitter', 'Linkedin', 'Sign up']
                        },

                        // Add series
                        series: [
                            {
                                type: 'pie',
                                center: ['10%', '50%'],
                                radius: radius,
                                itemStyle: labelFromatter,
                                data: [
                                    {name: 'other', value: {{ 100 - $percentageFacebook }}, itemStyle: labelBottom},
                                    {name: 'Facebook', value: {{ $percentageFacebook }},itemStyle: labelTop}
                                ]
                            },
                            {
                                type: 'pie',
                                center: ['30%', '50%'],
                                radius: radius,
                                itemStyle: labelFromatter,
                                data: [
                                    {name: 'other', value: {{ 100 - $percentageGoogle }}, itemStyle: labelBottom},
                                    {name: 'Google+', value: {{ $percentageGoogle }},itemStyle: labelTop}
                                ]
                            },
                            {
                                type: 'pie',
                                center: ['50%', '50%'],
                                radius: radius,
                                itemStyle: labelFromatter,
                                data: [
                                    {name: 'other', value: {{ 100 - $percentageTwitter }}, itemStyle: labelBottom},
                                    {name: 'Twitter', value: {{ $percentageTwitter }},itemStyle: labelTop}
                                ]
                            },
                            {
                                type: 'pie',
                                center: ['70%', '50%'],
                                radius: radius,
                                itemStyle: labelFromatter,
                                data: [
                                    {name: 'other', value: {{ 100 - $percentageLinkedin }}, itemStyle: labelBottom},
                                    {name: 'Linkedin', value: {{ $percentageLinkedin }},itemStyle: labelTop}
                                ]
                            },
                            {
                                type: 'pie',
                                center: ['90%', '50%'],
                                radius: radius,
                                itemStyle: labelFromatter,
                                data: [
                                    {name:'other', value:{{ 100 - $percentageSignup }}, itemStyle: labelBottom},
                                    {name:'Sign up', value:{{$percentageSignup }},itemStyle: labelTop}
                                ]
                            }
                        ]
                    };
        @endif

        <!--   -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Helicopter view on Networks  -->
        @if(isset($helicopterViewOnNetworks))    
            @foreach(App\Network::where('state','1')->get() as $network)
                <?php

                $networkMonths=App\Models\RadacctNetworkMonthes::get();
                if(isset($networkMonths) and count($networkMonths)>0){
                    $networkMonthsCounter=count($networkMonths);

                    ?>
                    helicopterViewOnNetworks_<?php echo $network->id;?>_options = {

                                // Setup timeline
                                timeline: {
                                    data: [
                                        <?php 
                                        $justCounter2=0;
                                        foreach($networkMonths as $currMonth)
                                        {
                                            $justCounter2++;
                                            echo "'".$currMonth->month."'";
                                            if($justCounter2!=$networkMonthsCounter){echo ",";}
                                        }
                                        ?>
                                    ],
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
                                            data: ['Total Usage (GB)','Upload (GB)','Download (GB)','Time (Hours)','New users','Online users','Concurrent devices']
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
                                            data: [
                                                <?php 
                                                for($i=1;$i<=31;$i++)
                                                {
                                                    echo "'".$i."'";
                                                    if($i!=31){echo ",";}
                                                }
                                                ?>
                                                ]
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
                                        <?php 
                                            $justCounter4=0;
                                            $justCounter5=0;
                                            foreach($networkMonths as $currMonth)
                                            {
                                                $justCounter4++;
                                                if($justCounter4==1){
                                                    ?>
                                                    series: [
                                                        {
                                                            name: 'Total Usage (GB)',
                                                            type: 'bar',
                                                            yAxisIndex: 1,
                                                            data: [
                                                                <?php 
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                                }
                                                                
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['total'])){
                                                                        echo $finalValue[$number]['total'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Upload (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php
                                                                //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get(); 
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Download (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Time (Hours)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['time'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'New users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php 
                                                                // $firstDayThisMonth="2020-12-01 00:00:00";
                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValueForNewUsers[$number])){
                                                                        echo $finalValueForNewUsers[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                            ]
                                                        },
                                                        {
                                                            name: 'Online users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                // if(isset($allDayData)){unset($allDayData);}
                                                                // $allDayData=App\Models\RadacctNetworkUsers::where('month', $currMonth->month)->get();
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                                //     else{$finalValue[$record->day]++;}
                                                                // }

                                                                $allDayData=App\Models\RadacctNetworkUsers::where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]=$record->online_days;
                                                                }
                                                                
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Concurrent devices',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                
                                                                $firstDayThisMonth=$currMonth->month."-01";
                                                                $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));

                                                                $allDayData=App\History::where('network_id',$network->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $dateExplode = explode("-", $record->add_date);
                                                                    $finalValue[ $dateExplode[2] ] = $record->details;
                                                                }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        }
                                                    ]
                                    },

                                                <?php
                                                }else{
                                                    ?>
                                                    {
                                                        series: [
                                                            <?php 
                                                            ///////////////////////////////// Total Usage (GB)
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                            }

                                                            for($i=1;$i<=31;$i++)
                                                            {
                                                                echo "'";
                                                                if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    if(isset($finalValue[$number]['total'])){
                                                                        echo $finalValue[$number]['total'];
                                                                    }else{echo "0";}
                                                                echo "'";
                                                                if($i!="31"){echo ",";}
                                                            }
                                                            echo "]},";
                                                            //////////////////////////////////// Upload (GB)
                                                            echo "{data: [";
                                                            // //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get(); 
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// Download (GB)
                                                            echo "{data: [";
                                                            //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// Time (Hours)
                                                            echo "{data: [";
                                                            //$allDayData=App\Models\RadacctNetworkDays::where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['time'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// New users
                                                            echo "{data: [";

                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValueForNewUsers[$number])){
                                                                            echo $finalValueForNewUsers[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                                
                                                            echo "]},";
                                                            //////////////////////////////////// Online users
                                                            echo "{data: [";
                                                            // if(isset($allDayData)){unset($allDayData);}
                                                            // $allDayData=App\Models\RadacctNetworkUsers::where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            //     else{$finalValue[$record->day]++;}
                                                            // }
                                                            $allDayData=App\Models\RadacctNetworkUsers::where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]=$record->online_days;
                                                            }
                                                            
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            echo "]},";
                                                            //////////////////////////////////// concurrent devices
                                                            echo "{data: [";
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $firstDayThisMonth=$currMonth->month."-01";
                                                                $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));
                                                                $allDayData=App\History::where('network_id',$network->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
    
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $dateExplode = explode("-", $record->add_date);
                                                                    $finalValue[ $dateExplode[2] ] = $record->details;
                                                                }
                                                                for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValue[$number])){
                                                                            echo $finalValue[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                            echo "]}";
                                                            ////////////////////////////////////
                                                            ?>
                                                        
                                                        ]
                                                    }
                                                    <?php
                                                    if($justCounter4!=$networkMonthsCounter){echo ",";}
                                                }// end else if($justCounter4==1)
                                            
                                            }// end for each
                                            ?>

                                ]
                            };
                    <?php } ?>
            @endforeach            
        @endif
        <!--   -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Helicopter view on Branches  -->
        @if(isset($helicopterViewOnBranches))    
            @foreach(App\Branches::where('state','1')->get() as $branch)
                <?php

                $branchMonths=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get();
                if(isset($branchMonths) and count($branchMonths)>0){
                    $branchMonthsCounter=count($branchMonths);

                    ?>
                    helicopterViewOnBranches_<?php echo $branch->id;?>_options = {

                                // Setup timeline
                                timeline: {
                                    data: [
                                    <?php 
                                    $justCounter2=0;
                                    foreach($branchMonths as $currMonth)
                                    {
                                        $justCounter2++;
                                        echo "'".$currMonth->month."'";
                                        if($justCounter2!=$branchMonthsCounter){echo ",";}
                                    }
                                    ?>
                                    ],
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
                                            data: ['Total Usage (GB)','Upload (GB)','Download (GB)','Time (Hours)','New users','Online users','Concurrent devices']
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
                                            data: [
                                                <?php 
                                                for($i=1;$i<=31;$i++)
                                                {
                                                    echo "'".$i."'";
                                                    if($i!=31){echo ",";}
                                                }
                                                ?>
                                                ]
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
                                        <?php 
                                            $justCounter4=0;
                                            $justCounter5=0;
                                            foreach($branchMonths as $currMonth)
                                            {
                                                $justCounter4++;
                                                if($justCounter4==1){
                                                    ?>
                                                    series: [
                                                        {
                                                            name: 'Total Usage (GB)',
                                                            type: 'bar',
                                                            yAxisIndex: 1,
                                                            data: [
                                                                <?php 
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $allDayData=App\Models\RadacctBranchDays::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                                }
                                                                    for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValue[$i]['total'])){
                                                                            echo $finalValue[$i]['total'];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }

                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Upload (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Download (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Time (Hours)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'New users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php 
                                                                // $firstDayThisMonth="2020-12-01 00:00:00";
                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::where('branch_id',$branch->id)->whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValueForNewUsers[$number])){
                                                                        echo $finalValueForNewUsers[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                            ]
                                                        },
                                                        {
                                                            name: 'Online users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                // if(isset($allDayData)){unset($allDayData);}
                                                                // $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                                //     else{$finalValue[$record->day]++;}
                                                                // }
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]=$record->online_days;
                                                                }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Concurrent devices',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $firstDayThisMonth=$currMonth->month."-01";
                                                                $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));

                                                                $allDayData=App\History::where('branch_id',$branch->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $dateExplode = explode("-", $record->add_date);
                                                                    $finalValue[ $dateExplode[2] ] = $record->details;
                                                                }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        }
                                                    ]
                                    },

                                                <?php
                                                }else{
                                                    ?>
                                                    {
                                                        series: [
                                                            <?php 
                                                            ///////////////////////////////////  Total Usage (GB)
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $allDayData=App\Models\RadacctBranchDays::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                            }
                                                            
                                                            for($i=1;$i<=31;$i++)
                                                            {
                                                                echo "'";
                                                                if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    if(isset($finalValue[$number]['total'])){
                                                                        echo $finalValue[$number]['total'];
                                                                    }else{echo "0";}
                                                                echo "'";
                                                                if($i!="31"){echo ",";}
                                                            }
                                                            echo "]},";
                                                            //////////////////////////////////// Upload (GB)
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// Download (GB)
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// Time (Hours)
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['time'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// New users
                                                            echo "{data: [";

                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::where('branch_id',$branch->id)->whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValueForNewUsers[$number])){
                                                                            echo $finalValueForNewUsers[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                                
                                                            echo "]},";
                                                            //////////////////////////////////// Online users
                                                            echo "{data: [";
                                                            // if(isset($allDayData)){unset($allDayData);}
                                                            // $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            //     else{$finalValue[$record->day]++;}
                                                            // }
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]=$record->online_days;
                                                            }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            echo "]},";
                                                            //////////////////////////////////// concurrent devices
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $firstDayThisMonth=$currMonth->month."-01";
                                                            $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));
                                                            $allDayData=App\History::where('branch_id',$branch->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();

                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $dateExplode = explode("-", $record->add_date);
                                                                $finalValue[ $dateExplode[2] ] = $record->details;
                                                            }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            echo "]}";
                                                            ////////////////////////////////////
                                                            ?>
                                                        
                                                        ]
                                                    }
                                                    <?php
                                                    if($justCounter4!=$branchMonthsCounter){echo ",";}
                                                }// end else if($justCounter4==1)
                                            
                                            }// end for each
                                            ?>

                                ]
                            };
                    <?php } ?>
            @endforeach            
        @endif
        <!--   -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Helicopter view on Groups  -->
        @if(isset($helicopterViewOnGroups))    
            @foreach(App\Groups::where('is_active','1')->where('as_system','0')->limit(10)->get() as $group)
                <?php

                $groupMonths=App\Models\RadacctGroupMonthes::where('group_id',$group->id)->get();
                if(isset($groupMonths) and count($groupMonths)>0){
                    $groupMonthsCounter=count($groupMonths);

                    ?>
                    helicopterViewOnGroups_<?php echo $group->id;?>_options = {

                                // Setup timeline
                                timeline: {
                                    data: [
                                    <?php 
                                    $justCounter2=0;
                                    foreach($groupMonths as $currMonth)
                                    {
                                        $justCounter2++;
                                        echo "'".$currMonth->month."'";
                                        if($justCounter2!=$groupMonthsCounter){echo ",";}
                                    }
                                    ?>
                                    ],
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
                                            data: ['Total Usage (GB)','Upload (GB)','Download (GB)','Time (Hours)','New users','Online users','Concurrent devices']
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
                                            data: [
                                                <?php 
                                                for($i=1;$i<=31;$i++)
                                                {
                                                    echo "'".$i."'";
                                                    if($i!=31){echo ",";}
                                                }
                                                ?>
                                                ]
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
                                        <?php 
                                            $justCounter4=0;
                                            $justCounter5=0;
                                            foreach($groupMonths as $currMonth)
                                            {
                                                $justCounter4++;
                                                if($justCounter4==1){
                                                    ?>
                                                    series: [
                                                        {
                                                            name: 'Total Usage (GB)',
                                                            type: 'bar',
                                                            yAxisIndex: 1,
                                                            data: [
                                                                <?php 
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $allDayData=App\Models\RadacctGroupDays::where('group_id',$group->id)->where('month', $currMonth->month)->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                    $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                                }
                                                                    for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValue[$i]['total'])){
                                                                            echo $finalValue[$i]['total'];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }

                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Upload (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Download (GB)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Time (Hours)',
                                                            yAxisIndex: 1,
                                                            type: 'bar',
                                                            data: [
                                                                <?php 
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                                // }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['time'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'New users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php 
                                                                // $firstDayThisMonth="2020-12-01 00:00:00";
                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::where('group_id',$group->id)->whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValueForNewUsers[$number])){
                                                                        echo $finalValueForNewUsers[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                            ]
                                                        },
                                                        {
                                                            name: 'Online users',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                // $allDayData=App\Models\RadacctGroupUsers::where('group_id',$group->id)->where('month', $currMonth->month)->get();
                                                                // if(isset($finalValue)){unset($finalValue);}
                                                                // foreach($allDayData as $record){
                                                                //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                                //     else{$finalValue[$record->day]++;}
                                                                // }
                                                                $allDayData=App\Models\RadacctGroupUsers::where('group_id',$group->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $finalValue[$record->day]=$record->online_days;
                                                                }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        },
                                                        {
                                                            name: 'Concurrent devices',
                                                            yAxisIndex: 1,
                                                            type: 'line',
                                                            data: [
                                                                <?php
                                                                if(isset($allDayData)){unset($allDayData);}
                                                                $firstDayThisMonth=$currMonth->month."-01";
                                                                $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));

                                                                $allDayData=App\History::where('group_id',$group->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
                                                                if(isset($finalValue)){unset($finalValue);}
                                                                foreach($allDayData as $record){
                                                                    $dateExplode = explode("-", $record->add_date);
                                                                    $finalValue[ $dateExplode[2] ] = $record->details;
                                                                }

                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                                
                                                                ?>
                                                                ]
                                                        }
                                                    ]
                                    },

                                                <?php
                                                }else{
                                                    ?>
                                                    {
                                                        series: [
                                                            <?php 
                                                            ///////////////////////////////// Total Usage (GB)
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $allDayData=App\Models\RadacctGroupDays::where('group_id',$group->id)->where('month', $currMonth->month)->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]['total']=round($record->total/1024/1024/1024,1);
                                                                $finalValue[$record->day]['upload']=round($record->acctinputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['download']=round($record->acctoutputoctets/1024/1024/1024,1);
                                                                $finalValue[$record->day]['time']=round($record->acctsessiontime/60/60,0);
                                                            }
                                                            
                                                            for($i=1;$i<=31;$i++)
                                                            {
                                                                echo "'";
                                                                if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    if(isset($finalValue[$number]['total'])){
                                                                        echo $finalValue[$number]['total'];
                                                                    }else{echo "0";}
                                                                echo "'";
                                                                if($i!="31"){echo ",";}
                                                            }
                                                            echo "]},";
                                                            ////////////////////////////////////
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctinputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['upload'])){
                                                                        echo $finalValue[$number]['upload'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            ////////////////////////////////////
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctoutputoctets/1024/1024/1024,1);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                if(isset($finalValue[$number]['download'])){
                                                                        echo $finalValue[$number]['download'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            ////////////////////////////////////
                                                            echo "{data: [";
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     $finalValue[$record->day]=round($record->acctsessiontime/60/60,0);
                                                            // }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number]['time'])){
                                                                        echo $finalValue[$number]['time'];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            
                                                            echo "]},";
                                                            //////////////////////////////////// New users
                                                            echo "{data: [";

                                                                $firstDayThisMonthAndTime=$currMonth->month."-01 00:00:00";
                                                                $lastDayThisMonthAndTime=date('Y-m-t', strtotime($firstDayThisMonthAndTime))." 23:59:59";
                                                                $newUsersData=App\Users::where('group_id',$group->id)->whereBetween('created_at', [$firstDayThisMonthAndTime, $lastDayThisMonthAndTime] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
                                                                
                                                                if(isset($finalValueForNewUsers)){unset($finalValueForNewUsers);}
                                                                foreach($newUsersData as $record){
                                                                    $dateExplode = explode("-", $record->date);
                                                                    $finalValueForNewUsers[ $dateExplode[2] ] = $record->new_users_per_day;
                                                                }
                                                        
                                                                for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValueForNewUsers[$number])){
                                                                            echo $finalValueForNewUsers[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                                
                                                            echo "]},";
                                                            //////////////////////////////////// Online users
                                                            echo "{data: [";

                                                            if(isset($allDayData)){unset($allDayData);}
                                                            // $allDayData=App\Models\RadacctGroupUsers::where('group_id',$group->id)->where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            //     else{$finalValue[$record->day]++;}
                                                            // }
                                                            $allDayData=App\Models\RadacctGroupUsers::where('group_id',$group->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]=$record->online_days;
                                                            }

                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            echo "]},";
                                                            //////////////////////////////////// concurrent devices
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $firstDayThisMonth=$currMonth->month."-01";
                                                            $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));
                                                            $allDayData=App\History::where('group_id',$group->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();

                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $dateExplode = explode("-", $record->add_date);
                                                                $finalValue[ $dateExplode[2] ] = $record->details;
                                                            }
                                                            for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                            echo "]}";
                                                            ////////////////////////////////////
                                                            ?>
                                                        
                                                        ]
                                                    }
                                                    <?php
                                                    if($justCounter4!=$groupMonthsCounter){echo ",";}
                                                }// end else if($justCounter4==1)
                                            
                                            }// end for each
                                            ?>

                                ]
                            };
                    <?php } ?>
            @endforeach            
        @endif
        <!--   ---------------------------------------------------------------------------------------------------------------- -->
        <!-- trackCustomersInBranches  -->
        @if(isset($trackCustomersInBranches))    
            <?php

            $branchMonths=App\Models\RadacctBranchMonthes::get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $branchMonthsCounter=count($branchMonths);
                ?>
                trackCustomersInBranches_options = {

                            // Setup timeline
                            timeline: {
                                data: [
                                    <?php 
                                    $justCounter2=0;
                                    foreach($branchMonths as $currMonth)
                                    {
                                        $justCounter2++;
                                        echo "'".$currMonth->month."'";
                                        if($justCounter2!=$branchMonthsCounter){echo ",";}
                                    }
                                    ?>
                                ],
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
                                        //data: ['Total Usage (GB)','Upload (GB)','Download (GB)','Time (Hours)','Online users']
                                        data: [
                                        @foreach(App\Branches::where('state','1')->get() as $branch)
                                            '{{ $branch->name }}',
                                        @endforeach
                                        ]
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
                                        data: [
                                            <?php 
                                            for($i=1;$i<=31;$i++)
                                            {
                                                echo "'".$i."'";
                                                if($i!=31){echo ",";}
                                            }
                                            ?>
                                            ]
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
                                    <?php 
                                        $justCounter4=0;
                                        $justCounter5=0;
                                        foreach($branchMonths as $currMonth)
                                        {
                                            $justCounter4++;
                                            if($justCounter4==1)
                                            {
                                                ?>
                                                series: [
                                                    <?php
                                                    $branchCounter=1;
                                                    foreach(App\Branches::where('state','1')->get() as $branch)
                                                    {
                                                        if($branchCounter==1)
                                                        {
                                                            ?>
                                                            {
                                                                name: '{{$branch->name}}',
                                                                type: 'line',
                                                                yAxisIndex: 1,
                                                                data: [
                                                                    <?php 
                                                                    if(isset($allDayData)){unset($allDayData);}
                                                                    // $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                                    // if(isset($finalValue)){unset($finalValue);}
                                                                    // foreach($allDayData as $record){
                                                                    //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                                    //     else{$finalValue[$record->day]++;}
                                                                    // }

                                                                    $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                                    if(isset($finalValue)){unset($finalValue);}
                                                                    foreach($allDayData as $record){
                                                                        $finalValue[$record->day]=$record->online_days;
                                                                    }
                                                                        
                                                                        for($i=1;$i<=31;$i++)
                                                                        {
                                                                            echo "'";
                                                                            if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                            if(isset($finalValue[$number])){
                                                                                echo $finalValue[$number];
                                                                            }else{echo "0";}
                                                                            echo "'";
                                                                            if($i!="31"){echo ",";}
                                                                        }

                                                                    
                                                                    ?>
                                                                    ]
                                                            },
                                                            <?php

                                                        }else{ //java script code changed after first branch
                                                            ?>
                                                            {
                                                                name: '{{$branch->name}}',
                                                                yAxisIndex: 1,
                                                                type: 'line',
                                                                data: [
                                                                    <?php 
                                                                    if(isset($allDayData)){unset($allDayData);}
                                                                    // $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                                    // if(isset($finalValue)){unset($finalValue);}
                                                                    // foreach($allDayData as $record){
                                                                    //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                                    //     else{$finalValue[$record->day]++;}
                                                                    // }
                                                                    $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                                    if(isset($finalValue)){unset($finalValue);}
                                                                    foreach($allDayData as $record){
                                                                        $finalValue[$record->day]=$record->online_days;
                                                                    }

                                                                    for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValue[$number])){
                                                                            echo $finalValue[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                                    
                                                                    ?>
                                                                ]
                                                            },
                                                        <?php
                                                        }

                                                        $branchCounter++;

                                                    }// End foreach(App\Branches::where('state','1')->get() as $branch)
                                                    ?>
                                                ]
                                            },

                                            <?php
                                            // counter more than one
                                            }else{
                                                ?>
                                                {
                                                    series: [
                                                        <?php 
                                                        foreach(App\Branches::where('state','1')->get() as $branch)
                                                        {
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            // $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->get();
                                                            // if(isset($finalValue)){unset($finalValue);}
                                                            // foreach($allDayData as $record){
                                                            //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            //     else{$finalValue[$record->day]++;}
                                                            // }
                                                            $allDayData=App\Models\RadacctBranchUsers::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $finalValue[$record->day]=$record->online_days;
                                                            }
                                                            
                                                            for($i=1;$i<=31;$i++)
                                                            {
                                                                echo "'";
                                                                if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                echo "'";
                                                                if($i!="31"){echo ",";}
                                                            }
                                                            echo "]},";
                                                        }

                                                        ////////////////////////////////////
                                                        ?>
                                                    ]
                                                }
                                                <?php
                                                if($justCounter4!=$branchMonthsCounter){echo ",";}
                                            }// end else if($justCounter4==1)
                                        
                                        }// end foreach
                                        ?>
                                    
                            ]
                };
            <?php 
            } ?>
                
        @endif
        <!--   ---------------------------------------------------------------------------------------------------------------- -->
        <!-- trackConcurrentInBranches  -->
        @if(isset($trackConcurrentInBranches))    
            <?php

            $branchMonths=App\Models\RadacctBranchMonthes::get();
            if(isset($branchMonths) and count($branchMonths)>0){
                $branchMonthsCounter=count($branchMonths);
                ?>
                trackConcurrentInBranches_options = {

                            // Setup timeline
                            timeline: {
                                data: [
                                    <?php 
                                    $justCounter2=0;
                                    foreach($branchMonths as $currMonth)
                                    {
                                        $justCounter2++;
                                        echo "'".$currMonth->month."'";
                                        if($justCounter2!=$branchMonthsCounter){echo ",";}
                                    }
                                    ?>
                                ],
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
                                        //data: ['Total Usage (GB)','Upload (GB)','Download (GB)','Time (Hours)','Online users']
                                        data: [
                                        @foreach(App\Branches::where('state','1')->get() as $branch)
                                            '{{ $branch->name }}',
                                        @endforeach
                                        ]
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
                                        data: [
                                            <?php 
                                            for($i=1;$i<=31;$i++)
                                            {
                                                echo "'".$i."'";
                                                if($i!=31){echo ",";}
                                            }
                                            ?>
                                            ]
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
                                    <?php 
                                        $justCounter4=0;
                                        $justCounter5=0;
                                        foreach($branchMonths as $currMonth)
                                        {
                                            $justCounter4++;
                                            if($justCounter4==1)
                                            {
                                                ?>
                                                series: [
                                                    <?php
                                                    $branchCounter=1;
                                                    foreach(App\Branches::where('state','1')->get() as $branch)
                                                    {
                                                        if($branchCounter==1)
                                                        {
                                                            ?>
                                                            {
                                                                name: '{{$branch->name}}',
                                                                type: 'line',
                                                                yAxisIndex: 1,
                                                                data: [
                                                                    <?php 
                                                                    if(isset($allDayData)){unset($allDayData);}
                                                                    
                                                                    
                                                                    $firstDayThisMonth=$currMonth->month."-01";
                                                                    $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));

                                                                    $allDayData=App\History::where('branch_id',$branch->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
                                                                    if(isset($finalValue)){unset($finalValue);}
                                                                    foreach($allDayData as $record){
                                                                        $dateExplode = explode("-", $record->add_date);
                                                                        $finalValue[ $dateExplode[2] ] = $record->details;
                                                                    }

                                                                        for($i=1;$i<=31;$i++)
                                                                        {
                                                                            echo "'";
                                                                            if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                            
                                                                            if(isset($finalValue[$number])){
                                                                                echo $finalValue[$number];
                                                                            }else{echo "0";}
                                                                            echo "'";
                                                                            if($i!="31"){echo ",";}
                                                                        }

                                                                    
                                                                    ?>
                                                                    ]
                                                            },
                                                            <?php

                                                        }else{ //java script code changed after first branch
                                                            ?>
                                                            {
                                                                name: '{{$branch->name}}',
                                                                yAxisIndex: 1,
                                                                type: 'line',
                                                                data: [
                                                                    <?php 
                                                                    $firstDayThisMonth=$currMonth->month."-01";
                                                                    $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));

                                                                    $allDayData=App\History::where('branch_id',$branch->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
                                                                    if(isset($finalValue)){unset($finalValue);}
                                                                    foreach($allDayData as $record){
                                                                        $dateExplode = explode("-", $record->add_date);
                                                                        $finalValue[ $dateExplode[2] ] = $record->details;
                                                                    }

                                                                    for($i=1;$i<=31;$i++)
                                                                    {
                                                                        echo "'";
                                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                        
                                                                        if(isset($finalValue[$number])){
                                                                            echo $finalValue[$number];
                                                                        }else{echo "0";}
                                                                        echo "'";
                                                                        if($i!="31"){echo ",";}
                                                                    }
                                                                    
                                                                    ?>
                                                                ]
                                                            },
                                                        <?php
                                                        }

                                                        $branchCounter++;

                                                    }// End foreach(App\Branches::where('state','1')->get() as $branch)
                                                    ?>
                                                ]
                                            },

                                            <?php
                                            // counter more than one
                                            }else{
                                                ?>
                                                {
                                                    series: [
                                                        <?php 
                                                        foreach(App\Branches::where('state','1')->get() as $branch)
                                                        {
                                                            echo "{data: [";
                                                            if(isset($allDayData)){unset($allDayData);}
                                                            $firstDayThisMonth=$currMonth->month."-01";
                                                            $lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth));
                                                            $allDayData=App\History::where('branch_id',$branch->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();

                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){
                                                                $dateExplode = explode("-", $record->add_date);
                                                                $finalValue[ $dateExplode[2] ] = $record->details;
                                                            }
                                                            
                                                            for($i=1;$i<=31;$i++)
                                                            {
                                                                echo "'";
                                                                if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                echo "'";
                                                                if($i!="31"){echo ",";}
                                                            }
                                                            echo "]},";
                                                        }

                                                        ////////////////////////////////////
                                                        ?>
                                                    ]
                                                }
                                                <?php
                                                if($justCounter4!=$branchMonthsCounter){echo ",";}
                                            }// end else if($justCounter4==1)
                                        
                                        }// end foreach
                                        ?>
                                    
                            ]
                };
            <?php 
            } ?>
                
        @endif
        <!--   ---------------------------------------------------------------------------------------------------------------- -->
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        @if(isset($user_countries))
                // moved to home.blade.php under title "GEO chart"
        @endif
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Apply options
        // ------------------------------
        @yield('options')
            
            @if(isset($marketing_campaigns) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) marketing_campaigns.setOption(marketing_campaigns_options); @endif
            
            @if(isset($os_statistics)) os_statistics.setOption(os_statistics_options); @endif

            @if(isset($browsers_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) browsers_statistics.setOption(browsers_statistics_options); @endif
            @if(isset($registered_users_segments) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) registered_users_segments.setOption(registered_users_segments_options); @endif

            @if(isset($revenue_timeline1) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) revenue_timeline1.setOption(revenue_timeline1_options); @endif
            @if(isset($revenue_timeline2) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) revenue_timeline2.setOption(revenue_timeline2_options); @endif

            @if(isset($gender_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) gender_statistics.setOption(gender_statistics_options); @endif

            @if(isset($top_branches_revenue) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) top_branches_revenue.setOption(top_branches_revenue_options); @endif
            @if(isset($new_registrations_vs_returned_visits) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) new_registrations_vs_returned_visits.setOption(new_registrations_vs_returned_visits_options); @endif
            @if(isset($smart_board) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) smart_board.setOption(smart_board_options); @endif
    
            @if(isset($helicopterViewOnNetworks)) 
                @foreach(App\Network::where('state','1')->get() as $network) 
                    <?php $networkMonthExist=App\Models\RadacctNetworkMonthes::where('network_id',$network->id)->get(); ?>
                    @if(isset($networkMonthExist) and count($networkMonthExist)>0)
                        helicopterViewOnNetworks_<?php echo $network->id;?>.setOption(helicopterViewOnNetworks_<?php echo $network->id;?>_options); 
                    @endif
                @endforeach 
            @endif
            @if(isset($helicopterViewOnBranches)) 
                @foreach(App\Branches::where('state','1')->get() as $branch) 
                    <?php $branchMonthExist=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get(); ?>
                    @if(isset($branchMonthExist) and count($branchMonthExist)>0)
                        helicopterViewOnBranches_<?php echo $branch->id;?>.setOption(helicopterViewOnBranches_<?php echo $branch->id;?>_options); 
                    @endif
                @endforeach 
            @endif
            @if(isset($helicopterViewOnGroups)) 
                @foreach(App\Groups::where('is_active','1')->where('as_system','0')->limit(3)->get() as $group) 
                    <?php $groupMonthExist=App\Models\RadacctGroupMonthes::where('group_id',$group->id)->get(); ?>
                    @if(isset($groupMonthExist) and count($groupMonthExist)>0)
                        helicopterViewOnGroups_<?php echo $group->id;?>.setOption(helicopterViewOnGroups_<?php echo $group->id;?>_options); 
                    @endif
                @endforeach 
            @endif
            
            @if(isset($trackCustomersInBranches)) 
                trackCustomersInBranches.setOption(trackCustomersInBranches_options);
            @endif

            @if(isset($trackConcurrentInBranches)) 
                trackConcurrentInBranches.setOption(trackConcurrentInBranches_options);
            @endif

            // Resize charts
            // ------------------------------

            window.onresize = function () {
                setTimeout(function (){
                    @yield('resize')
					@if(isset($marketing_campaigns) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) marketing_campaigns.resize(); @endif
                    //basic_donut.resize();
                    @if(isset($os_statistics)) os_statistics.resize(); @endif

                    @if(isset($browsers_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) browsers_statistics.resize(); @endif
                    @if(isset($registered_users_segments) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) registered_users_segments.resize(); @endif

                    @if(isset($revenue_timeline1) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) revenue_timeline1.resize(); @endif
                    @if(isset($revenue_timeline2) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) revenue_timeline2.resize(); @endif

                    @if(isset($gender_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) gender_statistics.resize(); @endif

                    @if(isset($top_branches_revenue) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) top_branches_revenue.resize(); @endif
                    @if(isset($new_registrations_vs_returned_visits) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) new_registrations_vs_returned_visits.resize(); @endif
                    @if(isset($smart_board) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) smart_board.resize(); @endif

                    @if(isset($helicopterViewOnNetworks)) 
                        @foreach(App\Network::where('state','1')->get() as $network) 
                            <?php $networkMonthExist=App\Models\RadacctNetworkMonthes::where('network_id',$network->id)->get(); ?>
                            @if(isset($networkMonthExist) and count($networkMonthExist)>0)
                                helicopterViewOnNetworks_<?php echo $network->id;?>.resize(); 
                            @endif
                        @endforeach 
                    @endif
                    @if(isset($helicopterViewOnBranches)) 
                        @foreach(App\Branches::where('state','1')->get() as $branch) 
                            <?php $branchMonthExist=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get(); ?>
                            @if(isset($branchMonthExist) and count($branchMonthExist)>0)
                                helicopterViewOnBranches_<?php echo $branch->id;?>.resize(); 
                            @endif
                        @endforeach 
                    @endif
                    @if(isset($helicopterViewOnGroups)) 
                        @foreach(App\Groups::where('is_active','1')->where('as_system','0')->limit(3)->get() as $group) 
                            <?php $groupMonthExist=App\Models\RadacctGroupMonthes::where('group_id',$group->id)->get(); ?>
                            @if(isset($groupMonthExist) and count($groupMonthExist)>0)
                                helicopterViewOnGroups_<?php echo $group->id;?>.resize(); 
                            @endif
                        @endforeach 
                    @endif
                    
                    @if(isset($trackCustomersInBranches)) 
                        trackCustomersInBranches.resize();
                    @endif

                    @if(isset($trackConcurrentInBranches)) 
                        trackConcurrentInBranches.resize();
                    @endif

                }, 100);
            }
        }
    );
});

        

</script>

<!------------ -->


<!-- --------- -->

<div class="row">
    <div class="col-lg-12">
		@yield('html')

            <!-- revenue_timeline1 -->
            @if(isset($revenue_timeline1) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) 
                <div class="panel panel-flat col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Internet Revenue stream </h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="revenue_timeline1"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /revenue_timeline1 -->

            <!-- revenue_timeline2 Counter -->
            @if(isset($revenue_timeline2) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) 
                <div class="panel panel-flat col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Statistics</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="revenue_timeline2"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /revenue_timeline2 Counter -->



            <!-- OS statistics -->
            @if(isset($os_statistics))
                <div class="panel panel-flat col-md-6">
                    <div class="panel-heading">
                        <h5 class="panel-title">OS statistics ( {{$statisticsType}} )</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="os_statistics"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /OS statisticse -->

            <!-- Browsers statistics -->
            @if(isset($browsers_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="panel panel-flat col-md-6">
                    <div class="panel-heading">
                        <h5 class="panel-title">Browsers statistics ( {{$statisticsType}} ) </h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="browsers_statistics"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Browsers statistics -->

            <!-- Marketing campaigns -->
            @if(isset($marketing_campaigns) && App\Settings::where('type', 'marketing_enable')->value('state') == 1) 
                <div class="panel panel-flat col-md-6">
                    <div class="panel-heading">
                        <h5 class="panel-title">Marketing campaigns ( {{$statisticsType}} )</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>

                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="marketing_campaigns"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Marketing campaigns -->

            <!-- Gender statistics -->
            @if(isset($gender_statistics) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="col-lg-6">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Gender statistics</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="gender_statistics"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Gender statistics -->

            

            <!-- Registered users segments -->
            @if(isset($registered_users_segments) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="col-lg-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Registered users segments ( {{$statisticsType}} )</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>

                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="registered_users_segments" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Registered users segments -->

            <!-- Top Branches Revenue -->
            @if(isset($top_branches_revenue) && App\Settings::where('type', 'commercial_enable')->value('state') == 1) 
                @if($dashboardType->value=="revenue_stream" && App\Settings::where('type', 'commercial_enable')->value('state') == 1)
                <div class="col-lg-12">
                @else
                <div class="col-lg-6">
                @endif
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Top Branches Revenue</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>


                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="top_branches_revenue"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Top Branches Revenue -->

            <!-- New Registrations VS returned Visitors -->
            @if(isset($new_registrations_vs_returned_visits) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                @if($dashboardType->value=="marketing" && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="col-lg-12">
                @else
                <div class="col-lg-6">
                @endif
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">New Registrations VS returned Visitors (Top Branches Monthly)</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>


                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="new_registrations_vs_returned_visits"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /New Registrations VS returned Visitors -->

            <!-- Smart Board -->
            @if(isset($smart_board) && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="col-lg-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Track assigned users, Online users for each Branch and Group</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>


                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="smart_board" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /Smart Board -->
            <!-- Smart Board -->

            <!-- /Smart Board -->
            

            <!-- helicopterViewOnNetworks -->
            @if(isset($helicopterViewOnNetworks))
                @foreach(App\Network::where('state','1')->get() as $network)
                    <?php $networkMonthExist=App\Models\RadacctNetworkMonthes::where('network_id',$network->id)->get(); ?>
                    @if(isset($networkMonthExist) and count($networkMonthExist)>0)
                        <div class="panel panel-flat col-lg-12">
                            <div class="panel-heading">
                                <h5 class="panel-title">Helicopter view on network ( {{$network->name}} )</h5>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="chart-container has-scroll">
                                    <div class="chart has-fixed-height has-minimum-width" id="helicopterViewOnNetworks_<?php echo $network->id;?>"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            
            <!-- /helicopterViewOnNetworks -->



            <!-- helicopterViewOnBranches -->
            @if(isset($helicopterViewOnBranches))
                @foreach(App\Branches::where('state','1')->get() as $branch)
                <?php $branchMonthExist=App\Models\RadacctBranchMonthes::where('branch_id',$branch->id)->get(); ?>
                @if(isset($branchMonthExist) and count($branchMonthExist)>0)
                    <div class="panel panel-flat col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Helicopter view on branch ( {{$branch->name}} )</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="helicopterViewOnBranches_<?php echo $branch->id;?>"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            @endif
            
            <!-- /helicopterViewOnBranches -->



            <!-- helicopterViewOnGroups -->
            @if(isset($helicopterViewOnGroups))
                @foreach(App\Groups::where('is_active','1')->where('as_system','0')->limit(3)->get() as $group)
                    <?php $groupMonthExist=App\Models\RadacctGroupMonthes::where('group_id',$group->id)->get(); ?>
                    @if(isset($groupMonthExist) and count($groupMonthExist)>0)
                        <div class="panel panel-flat col-lg-12">
                            <div class="panel-heading">
                                <h5 class="panel-title">Helicopter view on group ( {{$group->name}} )</h5>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="chart-container has-scroll">
                                    <div class="chart has-fixed-height has-minimum-width" id="helicopterViewOnGroups_<?php echo $group->id;?>"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            
            <!-- /helicopterViewOnGroups -->
            
            <!-- trackCustomersInBranches -->
            @if(isset($trackCustomersInBranches))
                
                <div class="panel panel-flat col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Track customers in your branches</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="trackCustomersInBranches"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /trackCustomersInBranches -->

            <!-- trackConcurrentInBranches -->
            @if(isset($trackConcurrentInBranches))
                
                <div class="panel panel-flat col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Discover number of simultaneously customers at the same time</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="trackConcurrentInBranches"></div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /trackConcurrentInBranches -->
           
		</div>
    </div>
</div>




