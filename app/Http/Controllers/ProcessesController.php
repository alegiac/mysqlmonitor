<?php

namespace App\Http\Controllers;
use App\Classes\Helpers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class ProcessesController extends Controller
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

    public function processes(){

        $params['proccesses']    = $this->db->select("SHOW FULL PROCESSLIST");
        return Helpers::getView(__FUNCTION__,get_class($this),$params);

    }

    public function getProcesses(){

        $result['result'] = false;

        if(Request::ajax()) {
            $result['result'] = true;


            $result['partial'] = View::make('processes.partial_processes', array(
                'proccesses' => $this->db->select("SHOW FULL PROCESSLIST")

            ))->render();


            return response()->json($result);
        }

    }
}
