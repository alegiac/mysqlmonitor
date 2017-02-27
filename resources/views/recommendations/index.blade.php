@extends('layout_index')
@section('style')
    <style>

    </style>


@endsection

@section('script')
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <script>
        var opts = {
            lines: 10,
            angle: 0,
            lineWidth: 0.4,
            pointer: {
                length: 0.75,
                strokeWidth: 0.042,
                color: '#1D212A'
            },
            limitMax: 'false',
            colorStart: '#1ABC9C',
            colorStop: '#1ABC9C',
            strokeColor: '#F0F3F3',
            generateGradient: true
        };
        var target = document.getElementById('foo'),
            gauge = new Gauge(target).setOptions(opts);

        gauge.maxValue = 100;
        gauge.animationSpeed = 150;
        gauge.set(0.1);
        gauge.setTextField(document.getElementById("gauge-text"));



        $(document).ready(function() {

            $('button#request_rec').click(function(){

                $('#rec_val').hide();
                $('#rec_vas').hide();
                $(this).attr('disabled',true);
                gauge.set(100);
                var intrv  = window.setInterval( function() {
                    checkGauge(intrv);
                }, 1000)

            });





        });

        function checkGauge(intrv) {
          $crnt = $('#gauge-text').html();
          if($crnt == '100'){
            $('#rec_val').fadeIn();
            $('#rec_vas').fadeIn();
              $('button#request_rec').attr('disabled',false);
              clearInterval(intrv);
          }
        }




    </script>
@endsection
@section('content')
<input type="hidden" id="gauge_input">
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

            <div class="row">





                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            @if(isset($warning_uptime))
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Uptime Warning : </strong> {{$warning_uptime}}
                            </div>
                            @endif

                            <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center ;border: 1px solid #abd9ea; border-radius: 5px">

                                <div class="col-md-4 col-sm-4 col-xs-2" >
                                    <span class="pull-right">
                                    <span id="gauge-text" class="gauge-value">0</span>
                                    <span id="" class="gauge-value"> % </span>

                                </span></div>
                                <div class="col-md-3 col-sm-3 col-xs-8" > <canvas  id="foo"  style="float: none; width: 100%"></canvas></div>

                                <div class="col-md-4 col-sm-4 col-xs-2"><span id="goal-text" class="goal-value pull-left">100% </span></div>

                                <div class="col-md-12 col-sm-9 col-xs-12" style="margin-bottom: 15px">
                                    <button type="button" class="btn btn-success btn-lg btn-block" id="request_rec"><i class="fa fa-cogs"></i> Request  For Recommendations</button>

                                </div>
                            </div>


                            <div class="clearfix"></div>



                        </div>
                    </div>
                </div>


                <div class="col-md-12 col-sm-12 col-xs-12" id="rec_val" style="display: none">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Recommendations </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <ul class="list-unstyled timeline" style="margin-top: 10px">

                                @foreach($recomend['rec'] as $r => $v)


                                    @if(count($v) > 0)
                                    @foreach($v as $rc)
                                        <li>
                                            <div class="block" style="min-height: 65px; padding-top: 20px; margin-left: 150px">
                                                <div class="tags" style="width: 130px">
                                                    <a href="" class="tag">
                                                        <span>{{$r}}</span>
                                                    </a>
                                                </div>
                                                <div class="block_content">
                                                    <h2 class="title">
                                                        {{$rc}}
                                                    </h2>


                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    @endif
                                @endforeach

                            </ul>

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12" id="rec_vas" style="display: none">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Recommended Values </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <ul class="to_do">
                                @foreach($recomend['vars'] as $r => $v)
                                <li>
                                    <p>
                                    <div class="" style="position: relative;"></div> {{$v}} </p>
                                </li>
                                    @endforeach

                            </ul>



                        </div>
                    </div>
                </div>


            </div>



        </div>

    </div>




@endsection