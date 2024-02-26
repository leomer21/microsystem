<!-- Sales stats -->
<div class="timeline-row">
    <div class="timeline-icon">
        @if(isset(Auth::user()->photo))
            <a href="#"><img src="upload/photo/{{Auth::user()->photo}}" title=""></a>
        @else
            <a href="#"><img src="assets/images/demo/users/face11.jpg" title=""></a>
        @endif

    </div>

    <div class="panel panel-flat timeline-content">
        <div class="panel-heading">
            <h6 class="panel-title">  Revenue Daily Statistics</h6>
            <div class="heading-elements">
                <!--<span class="heading-text"><i class="icon-history position-left text-success"></i> Updated 3 hours ago</span>-->

                <ul class="icons-list">

                </ul>
            </div>
        </div>

        <div class="panel-body">
            <div class="chart-container">
                <div class="chart has-fixed-height" id="sales" style="height: 1200px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- /sales stats -->
<script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>

<script>
    $(function() {
        // Charts
        // ------------------------------

        // Set paths
        require.config({
            paths: {
                echarts: 'assets/js/plugins/visualization/echarts'
            }
        });


        // Configuration
        require(
                [
                    'echarts',
                    'echarts/theme/limitless',
                    'echarts/chart/line',   // load-on-demand, don't forget the Magic switch type.
                    'echarts/chart/bar'
                ],
                // Charts setup
                function (ec, limitless) {
                    var sales = ec.init(document.getElementById('sales'), limitless);

                    <?php
                            $noOfDays=cal_days_in_month(CAL_GREGORIAN,date("m"),date("Y"));
                            $firstDayCurrentMonth=date("Y-m")."-01";
                            $lastDayCurrentMonth=date('Y-m-t', strtotime($firstDayCurrentMonth));

                            ?>
                            sales_options = {
                        // Setup grid
                        grid: {
                            x: 45,
                            x2: 15,
                            y: 35,
                            y2: 25
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            }
                        },

                        // Add legend
                        legend: {
                            @if(Auth::user()->type == 2)
                            data:['Charged Packages', 'Make Payment', 'Add Credit']
                            @elseif(Auth::user()->type == 1)
                            data:['Charged Packages', 'Resellers Revenue', 'Add Reseller Credit']
                            @endif
                        },

                        // Enable drag recalculate
                        calculable: true,

                        // Horizontal axis
                        xAxis: [{
                            type: 'value'
                        }],

                        // Vertical axis
                        yAxis: [{
                            type: 'category',
                            axisTick: {
                                show: false
                            },
                            data: [
                                <?php
                                $counter=1;
                                for($i=$noOfDays;$i>=1;$i--)
                                {
                                    if($counter!=1){echo ",";}
                                    echo "'".$i.date("-m")."'";
                                    $counter++;
                                }
                                ?>
                            ]
                        }],

                        // Add series
                        series: [
                            {
                                @if(Auth::user()->type == 2)
                                name: 'Charged Packages',
                                @elseif(Auth::user()->type == 1)
                                name: 'Charged Packages',
                                @endif

                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: true,
                                            position: 'inside'
                                        }
                                    }
                                },
                                data: [
                                    <?php

                                    $counter=1;
                                    for($i=$noOfDays;$i>=1;$i--)
                                    {
                                        if($counter!=1){echo ",";}$counter++;
                                        if(Auth::user()->type == 2)//Reseller
                                        {
                                            $dayValue=App\History::where('operation','reseller_charge_package')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('package_price');
                                        }
                                        elseif(Auth::user()->type == 1)//Admin
                                        {
                                            //$dayValue=App\History::where('add_date',date("Y-m-").$i)->orWhere(function ($query) {$query->where('operation','user_charge_package')->where('operation','reseller_charge_package');})->sum('package_price');
											$totalOfUserChargedPackages = App\History::where('add_date',date("Y-m-").$i)->where('operation','user_charge_package')->sum('package_price');
											$totalOfResellerChargedPackages = App\History::where('add_date',date("Y-m-").$i)->where('operation','reseller_charge_package')->sum('package_price');
											
											$dayValue = $totalOfUserChargedPackages + $totalOfResellerChargedPackages;
                                        }
                                        if(isset($dayValue)){echo $dayValue; unset($dayValue);}
                                        else{echo "0";}
                                    }

                                    ?>
                                ]
                            },
                            {
                                @if(Auth::user()->type == 2)
                                name: 'Add Credit',
                                @elseif(Auth::user()->type == 1)
                                name: 'Add Reseller Credit',
                                @endif

                                type: 'bar',
                                stack: 'Total',
                                barWidth: 5,
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: true
                                        }
                                    }
                                },
                                data: [
                                    <?php
                                    $counter=1;
                                    for($i=$noOfDays;$i>=1;$i--)
                                    {
                                        if($counter!=1){echo ",";}$counter++;
                                        if(Auth::user()->type == 2)//Reseller
                                        {
                                            $dayValue=App\History::where('operation','reseller_credit')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('details');
                                            if(isset($dayValue)){echo $dayValue; unset($dayValue);}
                                        }
                                        elseif(Auth::user()->type == 1)//Admin
                                        {
                                            $dayValue=App\History::where('operation','reseller_credit')->where('add_date',date("Y-m-").$i)->sum('details');
                                            if(isset($dayValue)){echo "-".$dayValue; unset($dayValue);}
                                        }

                                        else{echo "0";}
                                    }
                                    ?>
                                ]
                            },
                            {
                                @if(Auth::user()->type == 2)
                                name: 'Make Payment',
                                @elseif(Auth::user()->type == 1)
                                name: 'Resellers Revenue',
                                @endif

                                type: 'bar',
                                stack: 'Total',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: true,
                                            position: 'left'
                                        }
                                    }
                                },
                                data: [
                                    <?php
                                    $counter=1;
                                    for($i=$noOfDays;$i>=1;$i--)
                                    {
                                        if($counter!=1){echo ",";}$counter++;
                                        if(Auth::user()->type == 2)//Reseller
                                        {
                                            $dayValue=App\History::where('operation','reseller_payment')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('details');
                                            if(isset($dayValue)){echo "-".$dayValue; unset($dayValue);}

                                        }
                                        elseif(Auth::user()->type == 1)//Admin
                                        {
                                            $dayValue=App\History::where('operation','reseller_payment')->where('add_date',date("Y-m-").$i)->sum('details');
                                            if(isset($dayValue)){echo $dayValue; unset($dayValue);}
                                        }
                                        if(isset($dayValue)){echo "-".$dayValue; unset($dayValue);}
                                        else{echo "0";}
                                    }
                                    ?>
                                ]
                            }
                        ]
                    };

                    //
                    // Plans chart options
                    //

                    // Text label options
                    var labelRight = {normal: {color: '#FF7043', label: {position: 'right'}}};

                    //
                    // Apply options
                    //
                    sales.setOption(sales_options);

                    //
                    // Resize chart
                    //

                    window.onresize = function () {
                        setTimeout(function (){
                            sales.resize();
                        }, 200);
                    }
                    // Resize in tabs
                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        sales.resize();
                    });

                }

        );
    });
</script>