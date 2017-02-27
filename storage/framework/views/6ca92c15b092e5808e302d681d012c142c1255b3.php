<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>



    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <!-- Flot -->
    <script>
        $(document).ready(function() {



            speedX._ajax_current_connections();
            setInterval(function(){ speedX._ajax_current_connections(); }, 5000);

        });


    </script>
    <script>
        $(document).ready(function() {



        });


        var theme = {
            color: [
                '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
                '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
            ],

            title: {
                itemGap: 8,
                textStyle: {
                    fontWeight: 'normal',
                    color: '#408829'
                }
            },

            dataRange: {
                color: ['#1f610a', '#97b58d']
            },

            toolbox: {
                color: ['#408829', '#408829', '#408829', '#408829']
            },

            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.5)',
                axisPointer: {
                    type: 'line',
                    lineStyle: {
                        color: '#408829',
                        type: 'dashed'
                    },
                    crossStyle: {
                        color: '#408829'
                    },
                    shadowStyle: {
                        color: 'rgba(200,200,200,0.3)'
                    }
                }
            },

            dataZoom: {
                dataBackgroundColor: '#eee',
                fillerColor: 'rgba(64,136,41,0.2)',
                handleColor: '#408829'
            },
            grid: {
                borderWidth: 0
            },

            categoryAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },

            valueAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitArea: {
                    show: true,
                    areaStyle: {
                        color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },
            timeline: {
                lineStyle: {
                    color: '#408829'
                },
                controlStyle: {
                    normal: {color: '#408829'},
                    emphasis: {color: '#408829'}
                }
            },

            k: {
                itemStyle: {
                    normal: {
                        color: '#68a54a',
                        color0: '#a9cba2',
                        lineStyle: {
                            width: 1,
                            color: '#408829',
                            color0: '#86b379'
                        }
                    }
                }
            },
            map: {
                itemStyle: {
                    normal: {
                        areaStyle: {
                            color: '#ddd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    },
                    emphasis: {
                        areaStyle: {
                            color: '#99d2dd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    }
                }
            },
            force: {
                itemStyle: {
                    normal: {
                        linkStyle: {
                            strokeColor: '#408829'
                        }
                    }
                }
            },
            chord: {
                padding: 4,
                itemStyle: {
                    normal: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    },
                    emphasis: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    }
                }
            },
            gauge: {
                startAngle: 225,
                endAngle: -45,
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                        width: 8
                    }
                },
                axisTick: {
                    splitNumber: 10,
                    length: 12,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: 'auto'
                    }
                },
                splitLine: {
                    length: 18,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                pointer: {
                    length: '90%',
                    color: 'auto'
                },
                title: {
                    textStyle: {
                        color: '#333'
                    }
                },
                detail: {
                    textStyle: {
                        color: 'auto'
                    }
                }
            },
            textStyle: {
                fontFamily: 'Arial, Verdana, sans-serif'
            }
        };




        var gauge_option = {

            toolbox: {
                show: false

            },
            series: [{
                type: 'gauge',
                center: ['50%', '50%'],
                startAngle: 140,
                endAngle: -140,
                min: 0,
                max: 100,
                precision: 0,
                splitNumber: 10,
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [
                            [0.2, '#26B99A'],
                            [0.4, 'orange'],
                            [0.8, '#3498DB'],
                            [1, '#E74C3C']
                        ],
                        width: 30
                    }
                },
                axisTick: {
                    show: true,
                    splitNumber: 5,
                    length: 8,
                    lineStyle: {
                        color: '#eee',
                        width: 1,
                        type: 'solid'
                    }
                },
                axisLabel: {
                    show: true,
                    formatter: function(v) {
                        switch (v + '') {
                            case '10':
                                return '';
                            case '30':
                                return '';
                            case '60':
                                return '';
                            case '90':
                                return '';
                            default:
                                return '';
                        }
                    },
                    textStyle: {
                        color: '#333'
                    }
                },
                splitLine: {
                    show: true,
                    length: 30,
                    lineStyle: {
                        color: '#eee',
                        width: 2,
                        type: 'solid'
                    }
                },
                pointer: {
                    length: '80%',
                    width: 8,
                    color: 'auto'
                },

                detail: {
                    show: true,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderWidth: 0,
                    borderColor: '#ccc',
                    width: 100,
                    height: 40,
                    offsetCenter: ['-60%', 0],
                    formatter: '{value}%',
                    textStyle: {
                        color: 'auto',
                        fontSize: 20
                    }
                },
                data: [{
                    value: 90

                }]
            }]
        }






        var pct_max_used_memory = echarts.init(document.getElementById('pct_max_used_memory'), theme);
        gauge_option.series[0].data[0].value = '<?php echo e($simple_stats['pct_max_used_memory']); ?>';
        pct_max_used_memory.setOption(gauge_option);


        var pct_slow_queries = echarts.init(document.getElementById('pct_slow_queries'), theme);
        gauge_option.series[0].data[0].value = '<?php echo e($simple_stats['pct_slow_queries']); ?>';
        pct_slow_queries.setOption(gauge_option);


        var pct_connections_used = echarts.init(document.getElementById('pct_connections_used'), theme);
        gauge_option.series[0].data[0].value = '<?php echo e($simple_stats['pct_connections_used']); ?>';
        pct_connections_used.setOption(gauge_option);

        var pct_connections_aborted = echarts.init(document.getElementById('pct_connections_aborted'), theme);
        gauge_option.series[0].data[0].value = '<?php echo e($simple_stats['pct_connections_aborted']); ?>';
        pct_connections_aborted.setOption(gauge_option);




    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">

                </div>

                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row top_tiles">
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-hourglass-o"></i></div>
                        <div class="count">
                            <?php echo e($uptime['days']); ?>:<?php echo e($uptime['hours']); ?>:<?php echo e($uptime['minutes']); ?>:<?php echo e($uptime['seconds']); ?>


                        </div>
                        <h3>Mysql Uptime</h3>
                        <p>Days:Hours:Minutes:Seconds</p>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-database"></i></div>
                        <div class="count"><?php echo e(\App\Classes\Helpers::format_size($db_size[0]->total)); ?></div>
                        <h3>Database Size</h3>
                        <p>Database : <b><?php echo e(env('DB_DATABASE')); ?></b></p>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-dot-circle-o"></i></div>
                        <div class="count"><?php echo e(\App\Classes\Helpers::format_size($db_size[0]->free)); ?></div>
                        <h3>Free Size</h3>
                        <p><b><?php echo e(env('DB_DATABASE')); ?></b> Free Database Size</p>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-table"></i></div>
                        <div class="count"><?php echo e($count_tables[0]->count_tables); ?></div>
                        <h3>Tables</h3>
                        <p><b><?php echo e(env('DB_DATABASE')); ?></b> Total Tables</p>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="dashboard_graph x_panel">
                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Current Connections</h3>
                            </div>

                        </div>
                        <div class="x_content">
                            <div class="demo-container" style="height:250px">
                                <div id="placeholder3xx3" class="demo-placeholder" style="width: 100%; height:250px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">



                <div class="col-md-3 col-sm-6 col-xs-6">

                    <div class="x_panel">
                        <div class="x_title" style="text-align: center">
                            <h2>Max Used Memory </h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content" style="text-align: center">

                            <div id="pct_max_used_memory" style="height:250px;"></div>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-6">

                    <div class="x_panel">
                        <div class="x_title" style="text-align: center">
                            <h2>Slow Queries</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content" style="text-align: center">

                            <div id="pct_slow_queries" style="height:250px;"></div>

                        </div>
                    </div>
                </div>



                <div class="col-md-3 col-sm-6 col-xs-6">

                    <div class="x_panel">
                        <div class="x_title" style="text-align: center">
                            <h2>Used Connections</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content" style="text-align: center">

                            <div id="pct_connections_used" style="height:250px;"></div>

                        </div>
                    </div>
                </div>


                <div class="col-md-3 col-sm-6 col-xs-6">

                    <div class="x_panel">
                        <div class="x_title" style="text-align: center">
                            <h2>Aborted Connections</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content" style="text-align: center">

                            <div id="pct_connections_aborted" style="height:250px;"></div>

                        </div>
                    </div>
                </div>







            </div>



        </div>

    </div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout_index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>