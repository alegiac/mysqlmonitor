<?php

namespace App\Http\Controllers;
use App\Classes\Helpers;
use Illuminate\Support\Facades\Request;
use App\Models\SpeedxTablesHistories;
use Illuminate\Support\Facades\Cache;


class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $app_helper;
    protected $request;
    protected $db;
    public function __construct()
    {
        $this->request = Request::all();
        $this->db = app('db');
    }

    public function dashboard(){

        $params = array();

        $my_stat = Helpers::global_stats();
        $my_var = Helpers::global_vars();

        $params['uptime']    = Helpers::getUptime($my_stat['Uptime']);
        $params['simple_stats'] = Helpers::recommendations($my_stat,$my_var)['simple_stats'];
        $params['db_size'] = Helpers::get_db_size();
        $params['count_tables'] = Helpers::count_tables();





        return Helpers::getView(__FUNCTION__,get_class($this),$params);



    }

    public function getCurrentThreads(){
        $result['result'] = false;
        if(Request::ajax()) {

            $query_connected = "show status where `variable_name` = 'Threads_connected'";
            $query_running = "show status where `variable_name` = 'Threads_running'";

            $connected = $this->db->select($query_connected);
            $running = $this->db->select($query_running);


            $connections = array(
                'connected' => $connected[0]->Value,
                'running' => $running[0]->Value,
            );


            if (!Cache::has('current_connections')) {

                $cached_content[] = $connections;

                $encoded = json_encode ($cached_content);
                Cache::forever('current_connections', $encoded);
            }else{
                $get_cache = Cache::get('current_connections');
                $decoded = json_decode($get_cache);

                $decoded = (array)$decoded;
                $count_inc = count($decoded) +1;
                $decoded[] = (object)$connections;

                if($count_inc == 37){
                    unset($decoded[0]);
                    $decoded = array_values($decoded);
                }

                $encoded = json_encode ($decoded);
                Cache::forever('current_connections', $encoded);

            }

            $get_cache = Cache::get('current_connections');

            $result['result'] = true;
            $result['connections'] = json_decode($get_cache);


            return response()->json($result);
        }

    }



}
