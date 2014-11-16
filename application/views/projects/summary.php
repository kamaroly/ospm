<?php


		//echo '<h3>'.$projectname.'</h3>';

        $alt_text=$this->lang->line('project_open');
        $url=base_url().'projects/activate/'.$projectid;
		$actions=anchor($url, img(array('src' =>'img/icons/house_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)));
        $url = base_url().'projects/edit/'.$projectid;
        $alt_text = $this->lang->line('common_edit');
        $actions .= "\n".edit_action($url, $alt_text);
		echo '<tr>';
		echo "\n".'<td>'.$actions.'</td>';
        echo '<td>'.$projectname.'</td>';
        echo '<td>'.$projectstatus.'</td>';
        echo '<td>'.$total_tasks.'</td>';
        echo '<td>'.$totalinvoices.'</td>';
        
        

        if($totaltime>0)
	    {
	    $hrs_track=floor($totaltime/60);
	    $mins_track=$totaltime%60;
	    $tracktime=$hrs_track . " hrs " . $mins_track . " mins";
	    }
	    else
	    {
		$tracktime="None";
	    }
	    echo '<td>'.$tracktime.'</td>';
//$projects['status'],convert_date($projects['created'],'',$this->session->userdata('dateformat'))));
	    
		echo '</tr>';

/* End of file */        