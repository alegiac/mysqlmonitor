@extends('layout_index')
@section('style')
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
@endsection

@section('script')
    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
    <script>



        $(document).ready(function() {
            $('button#optimize_tables_btn').click(function(){

                speedX.optimize_call_first();
            });
            var handleDataTableButtons = function() {
                if ($("#datatable-buttons").length) {
                    $("#datatable-buttons").DataTable({
                        dom: "Bfrtip",
                        iDisplayLength:"500",
                        buttons: [
                            {
                                extend: "copy",
                                className: "btn-sm"
                            },
                            {
                                extend: "csv",
                                className: "btn-sm"
                            },
                            {
                                extend: "excel",
                                className: "btn-sm"
                            },
                            {
                                extend: "pdfHtml5",
                                className: "btn-sm"
                            },
                            {
                                extend: "print",
                                className: "btn-sm"
                            },
                        ],
                        responsive: true
                    });
                }
            };

            TableManageButtons = function() {
                "use strict";
                return {
                    init: function() {
                        handleDataTableButtons();
                    }
                };
            }();

            TableManageButtons.init();
        });
    </script>
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


            <div class="col-md-12 col-xs-12" style="bottom: 0px; clear: both;display: block; position: fixed; margin-left: -10px;  z-index: 99999999; background-color: #fff; padding: 10px; border-top: solid 1px #2a3f54">

                <div class="col-md-offset-5 col-xs-offset-4">
                    <button type="button" class="btn btn-success" id="optimize_tables_btn"><i class="fa fa-database"></i> Optimize Selected Tables</button>
                </div>

            </div>



            <div class="col-md-12 col-sm-12 col-xs-12">



                <div class="x_panel">
                    <div class="x_title">
                        <h2>Tables </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">


                        <div class="table-responsive">

                            <table class="table table-striped table-bordered jambo_table bulk_action" id="datatable-buttons">
                                <thead>
                                <tr class="headings">
                                    <th>
                                        <input type="checkbox" id="check-all" class="flat">
                                    </th>
                                    <th class="column-title">Name </th>
                                    <th class="column-title">Records </th>
                                    <th class="column-title">Data Size </th>
                                    <th class="column-title">Index Length </th>
                                    <th class="column-title">Type </th>
                                    <th class="column-title last">Overhead </th>

                                    <th class="bulk-actions" colspan="7">
                                        <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $records = 0;
                                $size = 0;
                                $index = 0;
                                $overhead = 0;
                                ?>
                                @foreach($db_tables as $dt)
                                    <?php
                                    $records += $dt->Rows;
                                    $size += $dt->Data_length;
                                    $index += $dt->Index_length;

                                             ?>
                                <tr class="even pointer">
                                    <td class="a-center" id="td_{{$dt->Name}}">
                                        <input type="checkbox" class="flat table_checkbox" name="table_records" value="{{$dt->Name}}">
                                    </td>
                                    <td class=" ">{{$dt->Name}}</td>
                                    <td class=" ">{{number_format($dt->Rows,0)}}</td>
                                    <td class=" ">{{\App\Classes\Helpers::format_size($dt->Data_length)}}</td>
                                    <td class=" ">{{\App\Classes\Helpers::format_size($dt->Index_length)}}</td>
                                    <td class=" ">{{$dt->Engine}}</td>

                                    @if ($dt->Engine != 'InnoDB')
                                    <?php  $overhead += $dt->Data_free; ?>
                                        <td class="a-right a-right ">
                                            <span class="label {{$dt->Data_free > 0 ? 'label-success' :'label-danger'}}"> {{\App\Classes\Helpers::format_size($dt->Data_free)}}</span>
                                           </td>

                                    @else
                                        <td class="a-right a-right ">-</td>
                                    @endif

                                </tr>
                                @endforeach
                                <tr style="font-weight: bold">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>{{number_format($records,0)}}</td>
                                    <td class=" ">{{\App\Classes\Helpers::format_size($size)}}</td>
                                    <td class=" ">{{\App\Classes\Helpers::format_size($index)}}</td>
                                    <td class=" "></td>


                                    <td class="a-right a-right last">{{\App\Classes\Helpers::format_size($overhead)}}</td>


                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


        </div>

    </div>

</div>




@endsection