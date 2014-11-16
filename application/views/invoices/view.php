<?php

$this->load->view('header');

echo '<table class="view_table">';

if (count($invoices) > 0)
{
    $duetypes = $this->config->item('invoice_due_status_types');

    echo '<tr>';
	    echo '<th></th>';
        echo '<th>'.$this->lang->line('invoice_name').'</th>';
	    echo '<th>'.$this->lang->line('invoice_status').'</th>';
	    echo '<th>'.$this->lang->line('invoice_total_due').'</th>';
	    echo '<th>'.$this->lang->line('invoice_action_date').'</th>';
	    echo '<th>'.$this->lang->line('invoice_due_date').'</th>';
	    echo '<th>'.$this->lang->line('invoice_paid_date').'</th>';
    echo '</tr>';
$altrow=0;
    foreach ($invoices as $invoice):
        if($altrow==1)
        {
            $altrow=0;
        }
        else 
        {
            $altrow=1;   
        }
        echo '<tr';
        if($altrow==1) 
        {
            echo ' class="altrow"';
        }
        echo '>';
        $actions = "";
        if (in_array($invoice['status'], $duetypes))
        {
            // Create Invoice
            $url = 'pdf/invoice/'.$invoice['unq'];
            $alt_text = $this->lang->line('invoice_generate_invoice');
            $actions .= "\n".pdf_action($url, $alt_text);
        }
        else
        {
            // Create Receipt
            $url = 'pdf/receipt/'.$invoice['unq'];
            $alt_text = $this->lang->line('invoice_generate_receipt');
            $actions .= "\n".pdf_action($url, $alt_text);
        }
        // Edit
        $url = 'invoices/edit/'.$invoice['unq'];
        $alt_text = $this->lang->line('common_edit');
        $actions .= "\n".edit_action($url, $alt_text);
        // Set Actions for Delete
        $url = 'invoices/destroy/'.$invoice['unq'];
        $alt_text = $this->lang->line('common_delete');
        $popup_text = $this->lang->line('common_delete_confirmation');
        $actions .= "\n".delete_action($url, $alt_text, $popup_text);
        /* END List Available Actions - Edit, Delete, etc. */
            echo '<td>'.$actions.'</td>';
	        echo '<td>'.$invoice['name'].'</td>';
	        echo '<td>'.$invoice['status'].'</td>';
	        echo '<td>'.number_format($time[$invoice['unq']],2).'</td>';
	        //echo '&pound; '.$overall_due;
            echo '<td>';
	        if (in_array($invoice['status'], $duetypes))
	        {
	            echo convert_date($invoice['actiondate'], '', $this->
	                session->userdata('dateformat'));
	        }
            echo '</td>';
	        echo '<td>'.convert_date($invoice['due_date'], '', $this->
	                session->userdata('dateformat')).'</td>';            
	        echo '<td>'.convert_date($invoice['paid_date'], '', $this->
	                session->userdata('dateformat')).'</td>';  
        echo '</tr>';
    
	endforeach;
}
else
{
    echo '<p class="notice">'.$this->lang->line('invoice_no_results').
        '</p>';
}

echo '</table>';
$this->load->view('footer');
/* End of file */