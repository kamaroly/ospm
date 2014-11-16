<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('date_conversion'))
{
    function date_conversion($date, $conversion_type)
    {
        // Convert Date Into SQL Date
        if ($conversion_type == "import")
        {
        	if($date==0) return FALSE;
        	$this_date = strtotime($date);
			$new_date = date('Y-m-d', $this_date);
			return $new_date;
        }
        // Convert Date From SQL
        if ($conversion_type == "export")
        {
        	if($date==0) return FALSE;
        	$this_date = strtotime($date);
			$new_date = date('d M Y', $this_date);
            return $new_date;
        }
    }
    // Informal Date Conversion
    if (!function_exists('convert_date'))
    {
        function convert_date($date, $time, $format)
        {
            $yesterday = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
            $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $tomorrow = mktime(0, 0, 0, date("m"), date("d") + 1, date("y"));
            $date1 = substr($date, 8, 2); // Day
            $date2 = substr($date, 5, 2); // Month
            $year = substr($date, 2, 2); // Year
            $displaydate = mktime(0, 0, 0, $date2, $date1, $year);
            if (substr($date, 0, 10) == "0000-00-00") $formatted_datetime = '';
            elseif ($displaydate == $tomorrow) $formatted_datetime = "Tomorrow";
            elseif ($displaydate == $today) $formatted_datetime = "Today";
            elseif ($displaydate == $yesterday) $formatted_datetime = "Yesterday";
            else  $formatted_datetime = date("D, j M y", $displaydate);
            if ($time == "yes") $formatted_datetime = $formatted_datetime . ', ' . substr($date,
                    11, 5);
            return $formatted_datetime;
        }
    }
    // Function to Convert Total Minutes into Format hh hrs, mm mins
    if (!function_exists('convertTime'))
    {
        function convertTime($minutes)
        {
            $hrsView = floor($minutes / 60);
            $minsView = $minutes % 60;
            $timeDisplay = '';
            if ($hrsView > 0) $timeDisplay .= $hrsView . ' hour';
            if ($hrsView > 1) $timeDisplay .= 's';
            if (($hrsView > 0) & ($minsView > 0)) $timeDisplay .= ', ';
            if ($minsView > 0) $timeDisplay .= $minsView . ' min';
            if ($minsView > 1) $timeDisplay .= 's';
            return $timeDisplay;
        }
    }
    // Function to Convert Total Minutes into Format hh:mm
    if (!function_exists('convertTimeForm'))
    {
        function convertTimeForm($minutes)
        {
            $hrsView = floor($minutes / 60);
            $minsView = $minutes % 60;
            if($minsView<10) $minsView='0'.$minsView;
            $timeDisplay = $hrsView.':'.$minsView;
            return $timeDisplay;
        }
    }
    // Function to Compare A Date To Today And Return How Long Left
    if (!function_exists('remainingTime'))
    {
        function remainingTime($dueDate)
        {
            if ($dueDate == 0) return '';
            $day = substr($dueDate, 8, 2);
            $month = substr($dueDate, 5, 2);
            $year = substr($dueDate, 0, 4);
            $date1 = time();
            $date2 = mktime(0, 0, 0, $month, $day, $year);
            $dateDiff = $date2 - $date1;
            $fullDays = floor($dateDiff / (60 * 60 * 24)) + 1;
            if ($fullDays == 0)
            {
                $remainingTime = '<span class="target_box duenow">Due today</span>';
            }
            if ($fullDays < 0)
            {
                $remainingDays = abs($fullDays) . " day";
                if ($remainingDays > 1) $remainingDays .= "s";
                $remainingTime = '<span class="target_box overdue">' . $remainingDays .
                    ' overdue</span>';
            }
            if ($fullDays > 0)
            {
                $remainingDays = $fullDays . " day";
                if ($remainingDays > 1) $remainingDays .= "s";
                $remainingTime = '<span class="target_box duesoon">' . $remainingDays .
                    ' left</span>';
            }
            return $remainingTime;
        }
    }
}
/* End of file */