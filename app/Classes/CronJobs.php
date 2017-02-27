<?php
namespace App\Classes;

use App\Models\SpeedxTablesHistories;


class CronJobs
{



    public  static function cron_tables_history(){


        $db = app('db');
        $tables = $db->select("SHOW TABLE STATUS");

        foreach($tables as $t){

            $history = new SpeedxTablesHistories();
            $history->db_table     = $t->Name;
            $history->date      = date('Ymd');
            $history->records   = $t->Rows;
            $history->size      = $t->Data_length;
            $history->save();

        }

    }

}