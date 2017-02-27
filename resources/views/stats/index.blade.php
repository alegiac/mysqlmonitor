@extends('layout_index')
@section('style')
@endsection

@section('script')

@endsection
@section('content')

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
                    <div class="x_title">
                        <h2>Engines</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Engine</th>
                                <th>Support</th>
                                <th>Is Default</th>
                                <th>Transactions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($engines as $eng)
                            <tr>
                                <td>{{$eng->ENGINE}}</td>
                                <td>{!! $eng->SUPPORT == 'YES' || $eng->SUPPORT == 'DEFAULT' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>' !!}</td>
                                <td>{!! $eng->SUPPORT == 'DEFAULT' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>' !!}</td>
                                <td>{!! $eng->TRANSACTIONS == 'YES' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>' !!}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


        </div>


        <div class="row">





            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Engines Size</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Engine</th>
                                <th>Total Size</th>
                                <th>Table Number</th>
                                <th>Data Size</th>
                                <th>Index Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($engine_size as $es)
                                <tr>
                                    <td>{{$es->ENGINE}}</td>
                                    <td>{{\App\Classes\Helpers::format_size($es->dli)}}</td>
                                    <td>{{$es->ce}}</td>
                                    <td>{{\App\Classes\Helpers::format_size($es->dl)}}</td>
                                    <td>{{\App\Classes\Helpers::format_size($es->il)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Fragmented Tables</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Table</th>
                                <th>Free</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_free = 0 ;?>
                            @foreach($fragmented_tables as $fr)
                                <?php $total_free += $fr->DATA_FREE; ?>
                                <tr>
                                    <td>{{$fr->table_name}}</td>
                                    <td>{{\App\Classes\Helpers::format_size($fr->DATA_FREE)}}</td>

                                </tr>

                            @endforeach
                            <tr>
                                <td style="text-align: right; font-weight: bold">Total Free : </td>
                                <td style="font-weight: bold">{{\App\Classes\Helpers::format_size($total_free)}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>

    </div>

</div>




@endsection