<?php

namespace App\Http\Controllers;
use App\Classes\Helpers;
use Illuminate\Support\Facades\Request;


class RecommendationsController extends Controller
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



        //Gloabl Stats
        $my_stat = Helpers::global_stats();
        $my_var = Helpers::global_vars();
        if($my_stat['Uptime'] < 86400){
            $params['warning_uptime'] = 'MySQL started within last 24 hours - recommendations may be inaccurate';
        }

        //Recomendations
        $params['recomend'] = Helpers::recommendations($my_stat,$my_var);

        return Helpers::getView(__FUNCTION__,get_class($this),$params);

    }

}
