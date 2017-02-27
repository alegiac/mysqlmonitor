<?php

namespace App\Http\Controllers;
use App\Classes\Helpers;
use Symfony\Component\Console\Helper\Helper;
use Illuminate\Support\Facades\Request;
use App\Models\SpeedxTablesHistories;

class StatsController extends Controller
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

    public function index(){

        $params['database']    = env('DB_DATABASE');
        $params['mysql_version']    = $this->db->select("SELECT VERSION() AS version");
        $params['database_size']    = 'unknown';
        $params['free_size']    = 'unknown';


        //Gloabl Stats
        $my_stat = Helpers::global_stats();
        $my_var = Helpers::global_vars();


        //Engines
        $engines = Helpers::engines();
        $params['engines']    = $engines;
        //Engine Size
        $engine_size = Helpers::engine_sizes();
        $params['engine_size']    = $engine_size;
        $params['fragmented_tables'] = Helpers::fragmented_tables($my_stat);

        return Helpers::getView(__FUNCTION__,get_class($this),$params);




    }










}
