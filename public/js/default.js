var speedX = {

    _sub_folder:'',
    _ajax_optimize_table:function(table){

        return $.ajax({

            url: speedX._sub_folder+'/tables',
            type: 'post',
            cache: false,
            dataType: 'json',
            method:'post',
            data: 'action=optimize&table='+table,
            beforeSend: function() {
                $('td#td_'+table).html('<img src="images/ajax-loader.gif">');
                $('button#optimize_tables_btn').attr('disabled',true);
            },
            success: function(data) {
                $('button#optimize_tables_btn').attr('disabled',false);
                $('td#td_'+table).html('<i class="fa fa-check fa-4"></i>');
                speedX.optimize_call_first();

            },
            error: function(xhr, textStatus, thrownError) {

            }
        });
    },
    optimize_call_first:function(){

        $( "input.table_checkbox" ).each(function( index ) {
            if($(this).prop('checked')){
                var sc = speedX._ajax_optimize_table($(this).val());
            }
        });



    },
    _ajax_current_connections:function(table){

        return $.ajax({

            url: speedX._sub_folder+'/current_threads',
            type: 'post',
            cache: false,
            dataType: 'json',
            method:'post',
            data: 'action=optimize',
            beforeSend: function() {

            },
            success: function(data) {

                $connections = data.connections;
                var _con = [];
                var _run = [];
                for (var i = 0; i < $connections.length; ++i) {

                    $connected = $connections[i].connected;
                    $running = $connections[i].running;


                    //console.log($connections[i].connected);
                    _con.push([i, $connected]);
                    _run.push([i, $running]);

                }

                var plot = $.plot("#placeholder3xx3", [
                    {data: _con,color: ["rgba(38, 185, 154, 0.31)"],label: "Connected",lines: { show: true, fill: true }},
                    {data: _run,color: ["rgba(3, 88, 106, 0.3)"],label: "Running",lines: { show: true, fill: true }}

                ], {
                    series: {
                        curvedLines: {
                            apply: true,
                            active: true,
                            monotonicFit: true
                        }
                    },

                    xaxis: {
                        show: true
                    },


                    grid: {
                        borderWidth: {
                            top: 0,
                            right: 0,
                            bottom: 1,
                            left: 1
                        },
                        borderColor: {
                            bottom: "#7F8790",
                            left: "#7F8790"
                        }
                    }
                });


            },
            error: function(xhr, textStatus, thrownError) {

            }
        });
    },
    _ajax_processes_list:function(){
        return $.ajax({

            url: speedX._sub_folder+'/processes_list',
            type: 'post',
            cache: false,
            dataType: 'json',
            method:'post',
            data: 'action=optimize',
            beforeSend: function() {
                $('div#processes_list').html('<img src="images/ajax-loader.gif">');

            },
            success: function(data) {
                $('div#processes_list').html(data.partial);

            },
            error: function(xhr, textStatus, thrownError) {

            }
        });
    },

}