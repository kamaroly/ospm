<?php

$this->load->view('header');
/*
* LOOP THROUGH CLIENTS
*/
echo '<table>';
echo '<tr>';

echo '<th></th>';
echo '<th>'.$this->lang->line('client_name').'</th>';
echo '<th>'.$this->lang->line('client_organisation').'</th>';
echo '<th>'.$this->lang->line('client_email').'</th>';
echo '<th>'.$this->lang->line('client_telephone').'</th>';
echo '</tr>';
if (count($clients) > 0)
{
    foreach ($clients as $client):

       // Create Row with Data Available
        echo '<tr>';
        /* START List Available Actions - Edit, Delete, etc. */
        $actions = "";
        $url='clients/edit/' . $client['unq'];	
       	$alt_text=$this->lang->line('common_edit');
        $actions.= "\n".edit_action($url,$alt_text);
        // Set Actions for Delete
        $url='clients/delete/' . $client['unq'];	
       	$alt_text=$this->lang->line('common_delete');
       	$popup_text=$this->lang->line('common_delete_confirmation');
        $actions.= "\n".delete_action($url,$alt_text,$popup_text); 
        echo '<td>'.$actions.'</td>';  
       
	   echo '<td>'. $client['contactname'].'</td>';
       echo '<td>'.$client['organisationname'].'</td>';
	   echo '<td>'.safe_mailto($client['email'],$client['email']).'</td>';
       echo '<td>'.$client['orgtelephone'].'</td>';
		echo '</tr>';

    endforeach;
    echo '</table>';
}
else
{
    echo '<p class="notice">'.$this->lang->line('client_no_results').'</p>';
}
$this->load->view('footer');

?>