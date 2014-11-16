<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 if ( ! function_exists('arrange_dropdown'))
{
    function arrange_dropdown($my_array)
    {
        $new_array='';
        if(count($my_array)>0)
        {
            //$my_array_split=explode(",",$my_array);
            $my_array_split=$my_array;
            if(count($my_array_split)>0)
            {
                foreach ($my_array_split as $row)
                {
                    $key_id=str_replace(" ","_",$row);
                    $new_array[$key_id] = $row;
                }
            }
        }
        // return result set as an associative array
        return $new_array;
    }
}
/* End of file */