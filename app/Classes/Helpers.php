<?php
namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;


class Helpers
{



    public  static function queryOutput($query){
        DB::connection()->enableQueryLog();
        eval($query);
        $queries = DB::getQueryLog();
        return $queries;

    }

    public static function getView($function,$class,$params){

        $class =  str_replace('App\Http\Controllers\\','',$class);
        $class =  str_replace('Controller','',$class);
        $class =  strtolower($class);
        $view = $class.'.'.$function;

        return view($view)->with(
            $params
        );

    }

    public static function  format_size($rawSize) {
        if($rawSize / 1073741824 > 1)
            return number_format($rawSize/1048576, 0) . ' Gb';
        else if ($rawSize / 1048576 > 1)
            return number_format($rawSize/1048576, 0) . ' Mb';
        else if ($rawSize / 1024 > 1)
            return number_format($rawSize/1024, 0) . ' Kb';
        else
            return number_format($rawSize, 0) . ' bytes';
    }


    public static function getUptime($uptime){

        $up['seconds'] = $uptime % 60;
        $up['minutes'] = intval( ( $uptime % 3600 ) / 60 );
        $up['hours']   = intval( ( $uptime % 86400 ) / (3600) );
        $up['days']    = intval( $uptime / (86400) );

        return $up;
    }


    public static function global_stats(){
        //Gloabl Stats
        $globals = app('db')->select("SHOW GLOBAL STATUS");
        $my_stat = [];

        foreach($globals as $g => $k){
            $var_obj = (array)$k;
            $my_stat[$var_obj['Variable_name']] = $var_obj['Value'];
        }
        return $my_stat;
    }



    public static function global_vars(){

        $global_vars = app('db')->select("SHOW GLOBAL VARIABLES");
        $my_var = [];

        foreach($global_vars as $g => $k){
            $var_obj = (array)$k;
            $my_var[$var_obj['Variable_name']] = $var_obj['Value'];
        }
        return $my_var;
    }

    public static function engines(){

        $engines = app('db')->select("SELECT * FROM information_schema.ENGINES ORDER BY ENGINE ASC");
        return $engines;
    }

    public static function engine_sizes(){

        $engine_size = app('db')->select("SELECT ENGINE,SUM(DATA_LENGTH+INDEX_LENGTH) as dli,COUNT(ENGINE) as ce,SUM(DATA_LENGTH) as dl,SUM(INDEX_LENGTH) as il FROM information_schema.TABLES WHERE TABLE_SCHEMA NOT IN ('information_schema', 'performance_schema', 'mysql') AND ENGINE IS NOT NULL GROUP BY ENGINE ORDER BY ENGINE ASC;");
        return $engine_size;

    }

    public static function supported_engines()
    {

        $suported_engines = [];
        foreach (self::engine_sizes() as $eng) {
            $suported_engines[$eng->ENGINE] = true;

        }
        return $suported_engines;
    }


    //Fragmented Tables
    public static function fragmented_tables($my_stat){

        $not_innodb = '';
        if(isset($my_stat['innodb_file_per_table']) && $my_stat['innodb_file_per_table'] == 'OFF'){
            $not_innodb = "AND NOT ENGINE='InnoDB'";
        }

        $query = "SELECT CONCAT(CONCAT(TABLE_SCHEMA, '.'), TABLE_NAME) as table_name,DATA_FREE FROM information_schema.TABLES WHERE TABLE_SCHEMA NOT IN ('information_schema','performance_schema', 'mysql') AND DATA_LENGTH/1024/1024>0 AND DATA_FREE*100/(DATA_LENGTH+INDEX_LENGTH+DATA_FREE) > 0 AND NOT ENGINE='MEMORY' $not_innodb ORDER BY DATA_FREE DESC";
        $fragmented_tables = app('db')->select($query);
        return $fragmented_tables;



    }

    public static function get_max_integer(){


        $query_max = "SELECT ~0 as max_int";
        $get_max_integer = app('db')->select($query_max);
        $max_integer = $get_max_integer[0]->max_int;
        return $max_integer;
    }

    public static function show_table_status(){

        $query_table_status = "SHOW TABLE STATUS";
        $table_status = app('db')->select($query_table_status);
        return $table_status;

    }

    public static function recommendations($my_stat,$my_var){

        $reccomend = [];
        $suported_engines = self::supported_engines();
        $fragmented_tables = self::fragmented_tables($my_stat);

        //Check Inno Db
        if(!isset($suported_engines['InnoDB'])){
            if(isset($my_stat['have_innodb']) && $my_stat['have_innodb'] == 'YES'){
                $reccomend['InnoDB'][] = "InnoDB is enabled but isn't being used.Add skip-innodb to MySQL configuration to disable InnoDB";
            }
        }

       //Check BDB
        if(!isset($suported_engines['BerkeleyDB'])){
            if(isset($my_stat['have_bdb']) && $my_stat['have_bdb'] == 'YES'){
                $reccomend['BDB'][] = "BDB is enabled but isn't being used. Add skip-bdb to MySQL configuration to disable BDB";
            }
        }

        //Check ISAM
        if(!isset($suported_engines['ISAM'])){
            if(isset($my_stat['have_isam']) && $my_stat['have_isam'] == 'YES'){
                $reccomend['Myisam'][] = "MYISAM is enabled but isn't being used. Add skip-isam to MySQL configuration to disable ISAM (MySQL > 4.1.0)";
            }
        }

        //Fragmented Tables
        if(count($fragmented_tables) > 0){
            $reccomend['Fragmentation'][] = "Run OPTIMIZE TABLE to defragment tables for better performance";
        }



        //Max Integer
        $max_integer = self::get_max_integer();

        //Table Status
        $table_status = self::show_table_status();
        foreach($table_status as $ts){

            $percent = intval($ts->Auto_increment*100/$max_integer);
            if($percent > 70){
                $reccomend['AutoIncrement'] = "Table {$ts->Name} has an autoincrement value near max capacity ($percent%)";
            }
        }

        if($my_stat['Questions'] < 1){
            $reccomend['General'][] = "Your server has not answered any queries - cannot continue...";

        }

        $read_buffer_size       = isset($my_var['read_buffer_size']) ?  $my_var['read_buffer_size'] :0;
        $read_rnd_buffer_size   = isset($my_var['read_rnd_buffer_size']) ?  $my_var['read_rnd_buffer_size'] :0;
        $sort_buffer_size       = isset($my_var['sort_buffer_size']) ?  $my_var['sort_buffer_size'] :0;
        $thread_stack           = isset($my_var['thread_stack']) ?  $my_var['thread_stack'] :0;
        $join_buffer_size       = isset($my_var['join_buffer_size']) ?  $my_var['join_buffer_size'] :0;
        $record_buffer          = isset($my_var['record_buffer']) ?  $my_var['record_buffer'] :0;
        $record_rnd_buffer      = isset($my_var['record_rnd_buffer']) ?  $my_var['record_rnd_buffer'] :0;
        $sort_buffer            = isset($my_var['sort_buffer']) ?  $my_var['sort_buffer'] :0;



        $calc_per_thread_buffers = $read_buffer_size+$read_rnd_buffer_size+$sort_buffer_size+$thread_stack+$join_buffer_size+$record_buffer+$record_rnd_buffer+$sort_buffer;
        $calc_total_per_thread_buffers = $calc_per_thread_buffers*$my_var['max_connections'];
        $calc_max_total_per_thread_buffers = $calc_per_thread_buffers * $my_stat['Max_used_connections'];


        $calc_max_tmp_table_size = $my_var['tmp_table_size'] > $my_var['max_heap_table_size'] ? $my_var['max_heap_table_size'] : $my_var['tmp_table_size'];
        $calc_server_buffers = $my_var['key_buffer_size'] + $calc_max_tmp_table_size;
        $calc_server_buffers += isset($my_var['innodb_buffer_pool_size']) ? $my_var['innodb_buffer_pool_size'] : 0;
        $calc_server_buffers += isset($my_var['innodb_additional_mem_pool_size']) ? $my_var['innodb_additional_mem_pool_size'] : 0;
        $calc_server_buffers += isset($my_var['innodb_log_buffer_size']) ? $my_var['innodb_log_buffer_size'] : 0;
        $calc_server_buffers += isset($my_var['query_cache_size']) ? $my_var['query_cache_size'] : 0;
        $calc_server_buffers += isset($my_var['aria_pagecache_buffer_size']) ? $my_var['aria_pagecache_buffer_size'] : 0;

        //Max used memory
        $calc_max_used_memory = $calc_server_buffers + $calc_max_total_per_thread_buffers;
        //Total possible memory
        $calc_max_peak_memory = $calc_server_buffers+$calc_total_per_thread_buffers;

        $physical_memory = self::get_physical_memory();

        $pct_max_used_memory = intval($calc_max_used_memory * 100/ $physical_memory ); // Max used memory is memory used by MySQL based on Max_used_connections
        $pct_max_physical_memory = intval($calc_max_peak_memory * 100/ $physical_memory ); // Total possible memory is memory needed by MySQL based on max_connections




        //Slow Queries
        $pct_slow_queries = intval($my_stat['Slow_queries']/$my_stat['Questions']*100);

        //Connections
        $pct_connections_used = intval($my_stat['Max_used_connections'] / $my_var['max_connections'] *100);
        $pct_connections_used = $pct_connections_used > 100 ? 100 : $pct_connections_used;

        //Aborted Connections
        $pct_connections_aborted = $my_stat['Aborted_connects'] * 100 / $my_stat['Connections'];

        $params['connections'] = $my_stat['Connections'];

        $params['aborted_connections'] = $my_stat['Aborted_connects'];
        $params['aborted_connections_percent'] = $pct_connections_aborted;


        if(PHP_INT_SIZE == 4 && $calc_max_used_memory > 2 * 1024 * 1024 * 1024){
            $reccomend['SYSTEM'][] = "Allocating > 2GB RAM on 32-bit systems can cause system instability";
            $reccomend['SYSTEM'][] = "Maximum reached memory usage: ".Helpers::format_size($calc_max_used_memory)." ($pct_max_used_memory % of installed RAM)";
        }elseif($pct_max_used_memory > 85){
            $reccomend['SYSTEM'][] = "Maximum reached memory usage: ".Helpers::format_size($calc_max_used_memory)." ($pct_max_used_memory % of installed RAM)";
        }
        if ( $pct_max_physical_memory > 85 ) {
            $reccomend['SYSTEM'][] = "Maximum reached memory usage: ".Helpers::format_size($calc_max_peak_memory )." ($pct_max_physical_memory % of installed RAM). Reduce your overall MySQL memory footprint for system stability";
        }

        /*
         *
                 *     if ( $physical_memory <
                ( $mycalc{'max_peak_memory'} + get_other_process_memory() ) )
            {
                badprint
                  "Overall possible memory usage with other process exceeded memory";
                push( @generalrec,
                    "Dedicate this server to your database for highest performance." );
            }
         */


        if ( $pct_slow_queries > 85 ) {
            $reccomend['Slow_Queries'][] = "Slow queries: $pct_slow_queries% ({$my_stat['Slow_queries']} / {$my_stat['Questions']}";
        }

        if ( $my_var['long_query_time'] > 10 ) {
            $vars[] = "long_query_time (<= 10)";
        }

        if(isset($my_var['log_slow_queries']) && $my_var['log_slow_queries'] == 'OFF'){
            $reccomend['Slow_Queries'][] = "Enable the slow query log to troubleshoot bad queries";
        }

        if ( $pct_connections_used > 85 ) {

            $reccomend['Connections'][] = "Highest connection usage: $pct_connections_used%  (".$my_stat['Max_used_connections']."/".$my_var['max_connections']."). Reduce or eliminate persistent connections to reduce connection usage";
            $reccomend['Connections'][] = "Reduce or eliminate persistent connections to reduce connection usage";
            $vars[] = "max_connections (> " . $my_var['max_connections'] . ")";
            $vars[] = "wait_timeout (< " . $my_var['wait_timeout'] . ")";
            $vars[] = "interactive_timeout (< " . $my_var['interactive_timeout'] . ")";
        }


        if ( $pct_connections_aborted > 3 ) {
            $reccomend['Connections'][] = "Highest connection usage: $pct_connections_used%  (".$my_stat['Max_used_connections']."/".$my_var['max_connections']."). Reduce or eliminate persistent connections to reduce connection usage";
            $reccomend['Connections'][] = "Reduce or eliminate unclosed connections and network issues";

        }

        if(!isset($my_var['skip_name_resolve'])){
            $reccomend['General'][] ="Skipped name resolution test due to missing skip_name_resolve in system variables.";
        }
        if(isset($my_var['skip_name_resolve']) &&  $my_var['skip_name_resolve'] == 'OFF'){
            $reccomend['General'][] ="name resolution is active : a reverse name resolution is made for each new connection and can reduce performance.Configure your accounts with ip or subnets only, then update your configuration with skip-name-resolve=1";
        }

        if($my_var['query_cache_type'] == 'OFF'){
            $reccomend['Query_Cache'][] = "Query cache may be disabled by default due to mutex contention.";
            $vars[] = "query_cache_type (=0)";
        }

        if($my_var['query_cache_size'] < 1){
            $reccomend['Query_Cache'][] = "Query cache is disabled)";
            $vars[] = "query_cache_size (>= 8M)";
        }


        if($my_stat['Com_select']  == 0){
            $reccomend['Query_Cache'][] = "Query cache cannot be analyzed - no SELECT statements executed";
        }


        $query_cache_efficiency = sprintf("%.1f",($my_stat['Qcache_hits'] / ( $my_stat['Com_select'] + $my_stat['Qcache_hits'] )) * 100);

        if($query_cache_efficiency < 20){
            $reccomend['Query_Cache'][] = "Bad Query cache efficiency.";
            $vars[] = "query_cache_limit (> ". Helpers::format_size( $my_var['query_cache_limit'] ). ", or use smaller result sets)";
        }

        $query_cache_prunes_per_day = 0;
        if ( $my_stat['Qcache_lowmem_prunes'] == 0 ) {
            $query_cache_prunes_per_day = 0;
        }
        else {
            $query_cache_prunes_per_day = intval($my_stat['Qcache_lowmem_prunes'] / ( $my_stat['Uptime'] / 86400 ));
        }

        if($query_cache_prunes_per_day > 98){
            $reccomend['Query_Cache'][] = "Query cache prunes per day: $query_cache_prunes_per_day.";
            $vars[] = "query_cache_limit (> ". Helpers::format_size( $my_var['query_cache_limit'] ). ", or use smaller result sets)";


            if ( $my_var['query_cache_size'] >= 128 * 1024 * 1024 ) {
                $reccomend['General'][] = "Increasing the query_cache size over 128M may reduce performance";
                $vars[] = "query_cache_size (> ". Helpers::format_size( $my_var['query_cache_size'] ). ")";
            }else{
                $vars[] = "query_cache_size (> " .  Helpers::format_size( $my_var['query_cache_size'] ). ")";
            }

        }


        $total_sorts = $my_stat{'Sort_scan'} + $my_stat{'Sort_range'};
        $pct_temp_sort_table = 0;
        if ( $total_sorts > 0 ) {
            $pct_temp_sort_table = intval(( $my_stat{'Sort_merge_passes'} / $total_sorts ) * 100 );
        }

        if($pct_temp_sort_table > 10){
            $reccomend['Sort'][] = "Sorts requiring temporary tables.";
            $vars[] = "sort_buffer_size (> ". Helpers::format_size( $my_var['sort_buffer_size'] ). ")";
            $vars[] = "read_rnd_buffer_size (> ". Helpers::format_size( $my_var['read_rnd_buffer_size'] ). ")";
        }


        $joins_without_indexes = $my_stat['Select_range_check'] + $my_stat['Select_full_join'];
        $joins_without_indexes_per_day = intval( $joins_without_indexes / ( $my_stat['Uptime'] / 86400 ) );
        if($joins_without_indexes_per_day > 250){

            $reccomend['Join'][] = "Joins performed without indexes: $joins_without_indexes";
            $reccomend['General'][] = "Adjust your join queries to always utilize indexes";
            $vars[] = "join_buffer_size (> ". Helpers::format_size( $my_var['join_buffer_size'] ). ", or always use indexes with joins)";
        }


        if ( $my_stat{'Created_tmp_tables'} > 0 ) {
            if ( $my_stat{'Created_tmp_disk_tables'} > 0 ) {
                $pct_temp_disk = intval(($my_stat['Created_tmp_disk_tables'] / $my_stat{'Created_tmp_tables'}) * 100);
            }
            else {
                $pct_temp_disk = 0;
            }
        }


        if ( $my_stat['Created_tmp_tables'] > 0 ) {
            if (   $pct_temp_disk > 25 && $calc_max_tmp_table_size < 256 * 1024 * 1024 ) {

                $reccomend['Temporary_Tables'][] = "Temporary tables created on disk: $pct_temp_disk %";
                $reccomend['General'][] = "When making adjustments, make tmp_table_size/max_heap_table_size equal";
                $reccomend['General'][] = "Reduce your SELECT DISTINCT queries which have no LIMIT clause";

                $vars[] = "tmp_table_size (> ". Helpers::format_size( $my_var['tmp_table_size'] ). ")";
                $vars[] = "max_heap_table_size (> ". Helpers::format_size( $my_var['max_heap_table_size'] ). ")";

            }elseif ($pct_temp_disk > 25 && $calc_max_tmp_table_size >= 256 * 1024 * 1024 ){
                $reccomend['Temporary_Tables'][] = "Temporary tables created on disk: $pct_temp_disk %";
                $reccomend['General'][] = "Temporary table size is already large - reduce result set size";
                $reccomend['General'][] = "Reduce your SELECT DISTINCT queries without LIMIT clauses";
            }
        }



        if($my_var['thread_cache_size'] == 0){
            $reccomend['Thread_cache'][] = "Thread cache is disabled";
            $reccomend['General'][] = "Set thread_cache_size to 4 as a starting value";
            $vars[] = "thread_cache_size (start at 4)";
        }

        $thread_cache_hit_rate =intval( 100 -( ( $my_stat['Threads_created'] / $my_stat['Connections'] ) * 100 ) );

        if ( $thread_cache_hit_rate <= 50 ) {
            $reccomend['Thread_cache'][] = "Thread cache hit rate: $thread_cache_hit_rate%";
            $vars[] = "thread_cache_size (> ".$my_var['thread_cache_size'].")";
        }

        $table_cache_hit_rate = 0;
        if ( $my_stat['Opened_tables'] > 0 ) {
            $table_cache_hit_rate = intval( $my_stat['Open_tables'] * 100 / $my_stat{'Opened_tables'} );
        }
        else {
            $table_cache_hit_rate = 100;
        }


        if($my_stat['Open_tables'] > 0){
            if ( $table_cache_hit_rate < 20 ) {
                $reccomend['Table_cache'][] = "Table cache hit rate: $table_cache_hit_rate %";
                $reccomend['Table_cache'][] = "Increase table_open_cachegradually to avoid file descriptor limits%";
                $reccomend['Table_cache'][] = "Beware that open_files_limit (". $my_var['open_files_limit']. ") variable ";
                $reccomend['Table_cache'][] = "should be greater than table_open_cache (". $my_var['table_open_cache']. ")";
                $vars[] = "table_open_cache (> " . $my_var['table_open_cache'] . ")";
            }
        }


        $pct_files_open = 0;
        if ( $my_var['open_files_limit'] > 0 ) {
            $pct_files_open = intval( $my_stat['Open_files'] * 100 / $my_var['open_files_limit'] );
        }
        if ( $pct_files_open > 85 ) {
            $reccomend['Open_files'][] = "Open file limit used: $pct_files_open %";
            $vars[] = "open_files_limit (> " . $my_var['open_files_limit'] . ")";
        }

        $pct_table_locks_immediate = 0;
        if ( $my_stat['Table_locks_immediate'] > 0 ) {
            if ( $my_stat['Table_locks_waited'] == 0 ) {
                $pct_table_locks_immediate = 100;
            }
            else {
                $pct_table_locks_immediate = intval( $my_stat['Table_locks_immediate'] * 100 / ( $my_stat['Table_locks_waited'] + $my_stat{'Table_locks_immediate'} ));
            }
        }

        if ( $pct_table_locks_immediate < 95 ) {
            $reccomend['Table_lock'][] = "Table locks acquired immediately: $pct_table_locks_immediate %";
            $reccomend['General'][] = "Optimize queries and/or use InnoDB to reduce lock wait";
        }

        $pct_binlog_cache = 0;
        if ( $my_var['log_bin']  ==  'OFF' ) {

            $total_binlog = $my_stat['Binlog_cache_use'] - $my_stat['Binlog_cache_disk_use'];
            $pct_binlog_cache = $total_binlog > 0 ? $total_binlog * 100 /  $my_stat['Binlog_cache_use']  : 0;
        }

        if (   $pct_binlog_cache < 90 && $my_stat['Binlog_cache_use'] > 0 ){
            $reccomend['Binlog_cache'][] = "Binlog cache memory access : $pct_binlog_cache%";
            $reccomend['General'][] = "Increase binlog_cache_size (Actual value: ". $my_var['binlog_cache_size']. ")";
            $vars[] = "binlog_cache_size (". Helpers::format_size( $my_var['binlog_cache_size'] + 16 * 1024 * 1024 ). ")";
        }


        if ( $my_var['key_buffer_size'] > 0 ) {
            $pct_key_buffer_used = sprintf("%.1f",(1 - (($my_stat['Key_blocks_unused'] * $my_var['key_cache_block_size']) / $my_var['key_buffer_size'])) * 100);
        }
        else {
            $pct_key_buffer_used = 0;
        }

        if ( $pct_key_buffer_used < 90 ) {
            $reccomend['Key_buffer'][] = "Key buffer used: $pct_key_buffer_used %";
        }
        $total_myisam_indexes = 0;
        $isam_query = "SELECT IFNULL(SUM(INDEX_LENGTH),0) as index_length FROM information_schema.TABLES WHERE TABLE_SCHEMA NOT IN ('information_schema') AND ENGINE = 'MyISAM'";
        //$total_myisam_indexes
        $isam_fetch = app('db')->select($isam_query);
        $total_myisam_indexes = $isam_fetch[0]->index_length;
        if($total_myisam_indexes == 0){
            $reccomend['Myisam'][] = "None of your MyISAM tables are indexed - add indexes immediately";

        }



        if ( $my_stat['Key_read_requests'] > 0 ) {
            $pct_keys_from_mem = sprintf("%.1f",(100 - (( $my_stat['Key_reads'] / $my_stat['Key_read_requests'] ) *100)));
        }
        else {
            $pct_keys_from_mem = 0;
        }
        if ( $my_var['key_buffer_size'] < $total_myisam_indexes && $pct_keys_from_mem < 95 ){
            $reccomend['Myisam'][] = "Key buffer size / total MyISAM indexes : ". number_format(($my_var['key_buffer_size'] / $total_myisam_indexes),2);
            $vars[] = "key_buffer_size (> ". Helpers::format_size( $total_myisam_indexes ). ")";

        }

        if ( $my_stat['Key_read_requests'] > 0 ) {
            if ( $pct_keys_from_mem < 95 ) {
                $reccomend['Key_buffer'][] = "Read Key buffer hit rate: $pct_keys_from_mem %";
            }
        }

        if ( $my_stat['Key_write_requests'] > 0 ) {
            $pct_wkeys_from_mem = sprintf("%.1f",(100 - (( $my_stat['Key_writes'] / $my_stat['Key_write_requests'] ) * 100 ) ));
        }
        else {
            $pct_wkeys_from_mem = 0;
        }

        if ( $my_stat['Key_write_requests'] > 0 ) {
            if ( $pct_wkeys_from_mem < 95 ) {
                $reccomend['Key_buffer'][] = "Write Key buffer hit rate: $pct_wkeys_from_mem %";
            }
        }

        if(isset($my_var['have_innodb']) && $my_var['have_innodb'] =='YES'){

            if($my_var['thread_pool_size'] or $my_var['thread_pool_size'] > 36 ){
                $reccomend['ThreadPool'][] = "thread_pool_size between 16 and 36 when using InnoDB storage engine.";
                $reccomend['thread_pool_size'][] = "Thread pool size for InnoDB usage (". $my_var['thread_pool_size']. ")";
                $vars[] = "thread_pool_size between 16 and 36 for InnoDB usage";
            }
        }

        if ( isset($my_var['have_isam']) && $my_var['have_isam'] == 'YES' ) {
            if ( $my_var['thread_pool_size'] < 4 or $my_var['thread_pool_size'] > 8 ) {
                $reccomend['ThreadPool'][] = "thread_pool_size between 4 and 8 when using MyIsam storage engine.";
                $reccomend['General'][] = "Thread pool size for MyIsam usage (". $my_var['thread_pool_size']. ")";
                $vars[] = "thread_pool_size between 4 and 8 for MyIsam usage";
            }
        }



            return array(
            'rec' => $reccomend,
            'vars' => $vars,
            'simple_stats' => array(
                'pct_max_used_memory' => $pct_max_used_memory > 100 ? 100 : intval($pct_max_used_memory),
                'pct_slow_queries' => $pct_slow_queries > 100 ? 100 : intval($pct_slow_queries),
                'pct_connections_used' => $pct_connections_used > 100 ? 100 : intval($pct_connections_used),
                'pct_connections_aborted' => $pct_connections_aborted > 100 ? 100 : intval($pct_connections_aborted),
            )
        );


    }


    public static function get_physical_memory(){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return self::get_physical_memory_windows();
        }else{
            $memory = self::get_physical_memory_linux();
            return str_replace(' kB','',$memory['MemTotal']);
        }
    }
    public static function get_physical_memory_linux(){

        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();

        if($data){

            foreach ($data as $line) {


                list($key,$val) = (strstr($line, ':') ? explode(':', $line) : array($line, ''));

                $meminfo[$key] = trim($val);
            }

        }

        return $meminfo;
    }

    public static function get_physical_memory_windows(){

        exec('wmic memorychip get capacity', $totalMemory);
        return array_sum($totalMemory);
    }

    public static function get_db_size(){

        $query = "SELECT table_schema ,sum( data_length + index_length ) as total,sum( data_free ) as free FROM information_schema.TABLES WHERE table_schema = '".env('DB_DATABASE')."' GROUP BY table_schema ; ";
        return app('db')->select($query);

    }

    public static function count_tables(){

        $query = "SELECT COUNT(*) as count_tables FROM information_schema.tables WHERE table_schema = '".env('DB_DATABASE')."';";
        return app('db')->select($query);

    }



}