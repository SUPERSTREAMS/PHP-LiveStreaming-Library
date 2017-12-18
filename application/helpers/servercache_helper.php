<?php

use Symfony\Component\Process\Process;

    function loadCache() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        return $CI;
    }
    function getCache($key) {
        $CI = loadCache();
        return $CI->cache->get($key);
    }

    function saveInCache($key, $value) {

        $CI = loadCache();
        $CI->cache->save($key, $value, 90000);
    }

    function saveProcess($PID) {
        $CI = loadCache();
        if ( ! $processes = $CI->cache->get('processes'))
        {
            $processes = [];
        }
        $processes[$PID] = time();
        $CI->cache->save('processes', $processes, 90000);
    }

    function clean() {
        $CI = loadCache();
        $processes = $CI->cache->get('processes');
        if($processes && is_array($processes)) {
            foreach ($processes as $pid => $time) {
                $yesterday = strtotime('-1 days', time());
                echo " ".$pid."<br/>";
               if($yesterday > $time) { //if application been more than 24 hours
                   unset($processes[$pid]);
                   $CI->cache->save('processes', $processes, 90000);    //save after updating
               }


            }
        }
    }

