<?php

/*
* LOOP THROUGH INVOICE ITEMS
*/
if (isset($timetracks)&&(count($timetracks) > 0))
{
    echo '<h2>' . $this->lang->line('invoice_timetrack_list') . '</h2>';
    $totaltime = 0;
	$countid=0;
    foreach ($timetracks as $timeitem):
        if($countid==0)
        {
		echo '<p>';
        }
        else
        {
		echo '<p class="alt_row">';        	
        }
		echo $timeitem['item'];        
        echo '</p>';
        if($countid==0)
        {
        echo '<p class="lowlevel">';
        $countid=1;
		}
        else
        {
		echo '<p class="alt_row lowlevel">';  
		$countid=0;      	
        } 
        $calctime = $timeitem['time'];
        $totaltime = $totaltime + $calctime;
        $displaytotaltime = $calctime . ' min'; 
 		echo $this->lang->line('timetrack_category').': '.$timeitem['category'].', ';
		echo $this->lang->line('timetrack_user').': '.$timeitem['fullname'];
		echo $this->lang->line('timetrack_date').': '.convert_date($timeitem['startdate'], '', $this->session->userdata('dateformat')).', ';
		echo $this->lang->line('timetrack_total').': '.$displaytotaltime;

    endforeach;  
}
/*
* END OF LOOP
*/


?>