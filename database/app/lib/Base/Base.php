<?php


namespace Lib;

use Models\Database;
use Models\Invoice;

define('TIME_TRAVEL_DB',BASEROOT.'database/storage/time_travel.json');

class Base
{

    private static function persist($data)
    {
        file_put_contents(TIME_TRAVEL_DB, json_encode($data));
    }
    private static function retrieve()
    {    	  
        $data = json_decode(file_get_contents(TIME_TRAVEL_DB), true);		
        $data['real_time'] = \DateTime::__set_state($data['real_time']);
        $data['simulated_time'] = \DateTime::__set_state($data['simulated_time']);
        return $data;
    }
    
    public static function clear()
    {
        $data = array(
            'real_time' => new \DateTime(),
            'simulated_time' => new \DateTime(),
            'speed' => 1.0
        );
        self::persist($data);
    }
	public static function setDateTime($datetime)
    {
        $data = self::retrieve();
        $data['real_time'] = new \DateTime();
        $data['simulated_time'] = $datetime;
        self::persist($data);
    }
	public static function setSpeed($speed)
    {
        self::getDateTime();
        $data = self::retrieve();		
        $data['speed'] = $speed;
        self::persist($data);
    }
	public static function getDateTime()
	{
        $data = self::retrieve();
        $real_time_now = new \DateTime();		
			
        $delta_seconds = round(($real_time_now->getTimestamp() - $data['real_time']->getTimestamp()) * $data['speed']);		
        $simulated_time_now = $data['simulated_time']->add(new \DateInterval("PT${delta_seconds}S"));
        $data['real_time'] = $real_time_now;    
        $data['simulated_time'] = $simulated_time_now;
        self::persist($data);
        return $data['simulated_time'];
	}
	public static function receive($invoice, $type, $notification_blob)
    {
       
    }
	public static function enqueue($invoice, $type, $notification_blob)
    {
       
    }
	public static function dispatch()
    {
       
    }
    
}
