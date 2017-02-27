<?php $__env->startSection('style'); ?>
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script src="../vendors/DateJS/build/date.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <script src="../vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- Flot plugins -->
    <script type="text/javascript">

        $(document).ready(function() {


            $(".sparkline_line").sparkline('html', {
                type: 'line',
                lineColor: '#26B99A',
                chartRangeMin:0 ,
                fillColor: '#ffffff',
                width: '100%',
                spotColor: '#34495E',
                minSpotColor: '#34495E'
            });

            $(".sparkline_bar").sparkline('html', {
                type: 'bar',
                chartRangeMin:0 ,
                colorMap: {
                    '7': '#a1a1a1'
                },
                barColor: '#26B99A',
                width: '100%'
            });


            $('li.table_list_tables').click(function(){

                $tb_nm = $(this).children('a').text();
               $('li.table_list_tables').css('background-color','');
               $('li.table_list_tables').css('color','');
               $('li.table_list_tables').children('a').css('color','');

                $(this).css('background-color','#169F85');
                $(this).css('color','#ffffff');
                $(this).children('a').css('color','#ffffff');

                $('h2#table_name').text('Table : '+$tb_nm);
            });


            //define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
            var chartColours = ['#96CA59', '#169F85', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];

            //generate random number for charts
            randNum = function() {
                return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
            };

            var d1 = [];
            var d2 = [];


            <?php
            $first_day = '';
            $day_cnt =   0;

            ?>
            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ky => $vl): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <?php


            $date_format = substr($ky,0,4).'-'.substr($ky,4,2).'-'.substr($ky,6,2);
            $str_time = strtotime($date_format).'000';


            ?>
                d1.push([<?php echo e($str_time); ?>, <?php echo e(array_sum($stats[$ky]['records'])); ?>]);
                d2.push([<?php echo e($str_time); ?>, <?php echo e(array_sum($stats[$ky]['size'])/1024/1024); ?>]);

            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


            var tickSize = [1, "day"];
            var tformat = "%d/%m/%y";




            var datasets = {
                "records": {
                    label: "Records",
                    data: d1,
                    lines: {
                        fillColor: "rgba(150, 202, 89, 0.12)"
                    },
                    points: {
                        fillColor: "#fff"
                    }
                },
                "size": {
                    label: "Size (MB)",
                    data: d2,
                    lines: {
                        fillColor: "rgba(22, 159, 133, 0.12)"
                    }, //#96CA59 rgba(150, 202, 89, 0.42)
                    points: {
                        fillColor: "#fff"
                    }
                }
            };

            var choiceContainer = $("#choices");
            choiceContainer.empty();
            $.each(datasets, function(key, val) {

                var chk = key == 'records'  ? "checked='checked'" : '';

                choiceContainer.append("<div class='checkbox'><label for='id" + key + "'><input type='checkbox' name='" + key +
                    "' "+chk+" id='id" + key + "' class='flat'>" +
                    "<span class='text'>"+val.label
                    + "</span></label></div>");
            });
            choiceContainer.find("input").click(plotAccordingToChoices);
            function plotAccordingToChoices() {

                var data = [];

                choiceContainer.find("input:checked").each(function () {
                    var key = $(this).attr("name");
                    if (key && datasets[key]) {
                        data.push(datasets[key]);
                    }
                });

                var options = {
                    grid: {
                        show: true,
                        aboveData: true,
                        color: "#3f3f3f",
                        labelMargin: 10,
                        axisMargin: 0,
                        borderWidth: 0,
                        borderColor: null,
                        minBorderMargin: 5,
                        clickable: true,
                        hoverable: true,
                        autoHighlight: true,
                        mouseActiveRadius: 100
                    },
                    series: {
                        lines: {
                            show: true,
                            fill: true,
                            lineWidth: 2,
                            steps: false
                        },
                        points: {
                            show: true,
                            radius: 4.5,
                            symbol: "circle",
                            lineWidth: 3.0
                        }
                    },
                    legend: {
                        position: "ne",
                        margin: [0, -25],
                        noColumns: 0,
                        labelBoxBorderColor: null,
                        labelFormatter: function(label, series) {
                            // just add some space to labes
                            return label + '&nbsp;&nbsp;';
                        },
                        width: 40,
                        height: 1
                    },
                    colors: chartColours,
                    shadowSize: 0,
                    tooltip: true, //activate tooltip
                    tooltipOpts: {
                        content: "%s: %y.0",
                        xDateFormat: "%d/%m",
                        shifts: {
                            x: -30,
                            y: -50
                        },
                        defaultTheme: false
                    },
                    yaxis: {
                        min: 0
                    },
                    xaxis: {
                        mode: "time",
                        minTickSize: tickSize,
                        timeformat: tformat,

                    }
                };




                if (data.length > 0) {
                    var placeholder = $("#placeholder33x");

                    var plot = $.plot(placeholder, data, options);

                    placeholder.bind("plotselected", function (event, ranges) {

                        var zoom = $("#zoom").is(":checked");

                        if (zoom) {
                            var plot = $.plot(placeholder, data, $.extend(true, {}, options, {
                                xaxis: {
                                    min: ranges.xaxis.from,
                                    max: ranges.xaxis.to
                                }
                            }));
                        }
                    });
                    placeholder.bind("plotunselected", function (event) {
                        // Do Some Work
                    });
                    $("#clearSelection").click(function () {
                        plot.clearSelection();
                    });
                    $("#setSelection").click(function () {
                        plot.setSelection({
                            xaxis: {
                                from: 1,
                                to: 2
                            }
                        });
                    });


                }
            }
            $("<div id='tooltip'></div>").css({
                position: "absolute",
                display: "none",
                border: "1px solid #fff",
                padding: "2px",
                "background-color": "#000",
                opacity: 0.80,
                color:"#fff",
            }).appendTo("body");

            $("#placeholder33x").bind("plothover", function (event, pos, item) {



                if (item) {
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(0);

                    $("#tooltip").html( y)
                    //$("#tooltip").html(item.series.label + " of " + x + " = " + y)
                        .css({top: item.pageY+5, left: item.pageX+5})
                        .fadeIn(200);
                } else {
                    $("#tooltip").hide();
                }

            });


            plotAccordingToChoices();


        var cb = function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('input#date_r').val(start.format('YYYYMMDD') + '-' + end.format('YYYYMMDD'));
                document.location.href= '/tables/history?date_range='+start.format('YYYYMMDD') + '-' + end.format('YYYYMMDD')
            };

            var optionSet1 = {
                startDate: moment().subtract(6, 'days'),
                endDate: moment(),
                minDate: '01/01/2016',
                maxDate: '<?php echo e(date('m/d/Y')); ?>',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Clear',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            };
            $('#reportrange span').html(moment().subtract(6, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

            $('input#date_r').val(moment().subtract(6, 'days').format('YYYYMMDD') + '-' + moment().format('YYYYMMDD'));

            $('#reportrange').daterangepicker(optionSet1, cb);
            $('#reportrange').on('show.daterangepicker', function() {
                console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function() {
                console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
            });
            $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
                console.log("cancel event fired");
            });
            $('#options1').click(function() {
                $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function() {
                $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function() {
                $('#reportrange').data('daterangepicker').remove();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="right_col" role="main">
    <div class="">


        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 id="table_name">Summary</h2>
                        <div class="filter">
                            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <span></span> <b class="caret"></b>
                            </div>
                            <input type="hidden" id="date_r">

                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">



                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <div class="demo-container" style="height:280px">
                                <div id="placeholder33x" class="demo-placeholder"></div>
                            </div>

                        </div>

                        <div class="col-md-1 col-sm-1 col-xs-1">

                            <p id="choices"></p>

                        </div>



                    </div>
                </div>
            </div>


        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 id="table_name">Tables</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="20%">Table</th>
                                <th width="40%">Records</th>
                                <th width="40%">Size</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tb => $ky): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr>
                                <th> <i class="fa fa-table"></i> <?php echo e($tb); ?></th>
                                <td><span class="sparkline_line"><?php echo e(implode(',',$tb_h[$tb]['records'])); ?></span></td>
                                <td> <span class="sparkline_bar"><?php echo e(implode(',',$tb_h[$tb]['size'])); ?></span></td>

                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                            </tbody>
                        </table>




                    </div>
                </div>
            </div>
        </div>



    </div>

</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout_index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>