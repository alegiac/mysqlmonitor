<?php

namespace App\Http\Controllers;
use App\Classes\Helpers;
use Illuminate\Support\Facades\Request;
use App\Models\SpeedxTablesHistories;

class TablesController extends Controller
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

        $params['db_tables']    = $this->db->select("SHOW TABLE STATUS");
        return Helpers::getView(__FUNCTION__,get_class($this),$params);

    }

    public function optimizeTable(){
        $result['result'] = false;
        if(Request::ajax()) {

            $table = $this->request['table'];
            $result['table'] = $table;

            $local_query = 'OPTIMIZE TABLE `'.$table.'`';
            $optimize = $this->db->select($local_query);
            if($optimize){
                $result['result'] = true;
            }
            return response()->json($result);
        }

    }

    public function history(){



        $date_min = date('Ymd',strtotime('-10 days'));

        $table_histories = SpeedxTablesHistories::orderBy('db_table','asc')->orderBy('date','asc');

        if(isset($this->request['date_range'])){
            $date_reqexp = explode('-',$this->request['date_range']);
            $start_date = $date_reqexp[0];
            $end_date   = $date_reqexp[1];
            $table_histories->where('date','>=',$start_date);
            $table_histories->where('date','<=',$end_date);
        }else{
            $table_histories->where('date','>=',$date_min);
        }
        $exec = $table_histories->get();

        $tb = [];
        $tables = [];
        $stats = [];
        foreach ($exec as $th){
            $tb[$th->db_table]['records'][] = $th->records;
            $tb[$th->db_table]['size'][] = $th->size;
            $tables[$th->db_table] = true;
            $stats[$th->date]['records'][] = $th->records;
            $stats[$th->date]['size'][] = $th->size;
        }


        $params['stats']    = $stats;
        $params['tables']    = $tables;
        $params['tb_h']    = $tb;


        return Helpers::getView(__FUNCTION__,get_class($this),$params);
    }

    public function getHistoryStats(){



    }
}
