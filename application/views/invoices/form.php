<?php
$this->load->view('header');
if (isset($invoice_details[0]['unq']))
{
    // Editing Existing Ite,
    $hidden = array('invoiceUnq' => $invoice_details[0]['unq']);
    echo form_open('invoices/update', '', $hidden);
    $invoice_id = str_pad($invoice_details[0]['unq'], 7, "0", STR_PAD_LEFT);
    // Invoice Name
    $invoice_name = set_value('invoice_name', isset($post['invoice_name']) ? htmlentities($post['invoice_name']) : $invoice_details[0]['name']);
    // Invoice Status
    $invoice_status = set_value('invoice_status', isset($post['invoice_status']) ? htmlentities($post['invoice_status']) : str_replace(" ", "_", $invoice_details[0]['status']));
    // Invoice Action Date
    $invoice_action_date = set_value('invoice_action_date', isset($post['invoice_action_date']) ? htmlentities($post['invoice_action_date']) : date_conversion($invoice_details[0]['actiondate'], 'export'));
    // Invoice Due Date
    $invoice_due_date = set_value('invoice_due_date', isset($post['invoice_due_date']) ? htmlentities($post['invoice_due_date']) : date_conversion($invoice_details[0]['due_date'], 'export'));
    // Invoice Paid Date
    $invoice_paid_date = set_value('invoice_paid_date', isset($post['invoice_paid_date']) ? htmlentities($post['invoice_paid_date']) : date_conversion($invoice_details[0]['paid_date'], 'export'));
    $submit_text = $this->lang->line('invoice_update');
}
else
{
    // Add a New Item
    echo form_open('invoices/create');
    $invoice_name = set_value('invoice_name');
    $invoice_action_date = set_value('invoice_action_date');
    $invoice_due_date = set_value('invoice_due_date');
    $invoice_paid_date = set_value('invoice_paid_date');
    $invoice_description = set_value('invoice_description');
    $invoice_rate = set_value('invoice_rate');
    $invoice_flatfee = set_value('invoice_flatfee');
    $invoice_status = set_value('invoice_status');
    $submit_text = $this->lang->line('invoice_add');
}
echo '<fieldset>';
if (isset($invoice_details[0]['unq']))
{
    // Display Invoice ID
    echo '<p>';
    echo form_label($this->lang->line('invoice_id'), 'invoice_id');
    echo form_input(array('name' => 'invoice_id', 'id' => 'invoice_id', 'maxlength' => '10', 'size' => '10', 'value' => $invoice_id, 'disabled' => 'disabled'));
    echo '</p>';
}
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('invoice_name'), 'invoice_name');
echo form_input(array('name' => 'invoice_name', 'id' => 'invoice_name', 'maxlength' => '100', 'size' => '30', 'value' => $invoice_name));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('invoice_status'), 'invoice_status');
echo form_dropdown('invoice_status', arrange_dropdown($this->config->item('invoice_status_types')), $invoice_status);
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_action_date'), 'invoice_action_date');
echo form_input(array('name' => 'invoice_action_date', 'id' => 'invoice_action_date', 'maxlength' => '10', 'size' => '10', 'value' => $invoice_action_date, 'class' => 'date_input'));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_due_date'), 'invoice_due_date');
echo form_input(array('name' => 'invoice_due_date', 'id' => 'invoice_due_date', 'maxlength' => '10', 'size' => '10', 'value' => $invoice_due_date, 'class' => 'date_input'));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_paid_date'), 'invoice_paid_date');
echo form_input(array('name' => 'invoice_paid_date', 'id' => 'invoice_paid_date', 'maxlength' => '10', 'size' => '10', 'value' => $invoice_paid_date, 'class' => 'date_input'));
echo '</p>';
echo '<p>';
echo form_submit('submit_form', $submit_text);
echo '</p>';
echo '</fieldset>';
/* START List Available Actions - Edit, Delete, etc. */
//echo $total_invoice_amount;
$duetypes = $this->config->item('invoice_due_status_types');
if (isset($invoice_details[0]['unq']))
{
    if (in_array($invoice_details[0]['status'], $duetypes))
    {
        // Create Invoice
        $pdf_url = '<li><a href="'.site_url().'/pdf/invoice/'.$invoice_details[0]['unq'].'">'.$this->lang->line('invoice_generate_invoice').'</a></li>';
    }
    else
    {
        // Create Receipt
        $pdf_url = '<li><a href="'.site_url().'/pdf/receipt/'.$invoice_details[0]['unq'].'">'.$this->lang->line('invoice_generate_receipt').'</a></li>';
    }
    // Display Billables
    echo '<fieldset class="view_table">';
    echo '<h2>'.$this->lang->line('invoice_work_items').'</h2>';
    echo '<ul id="sub_navigation">';
    echo '<li><a href="'.site_url().'/invoices/add_work/'.$invoice_details[0]['unq'].'">'.$this->lang->line('invoice_create_work_item').'</a></li>';
    if (count($billable_details) > 0)
    {
        echo $pdf_url;
    }
    echo '</ul>';
    $invoice_total_due = 0;
    if (count($billable_details) > 0)
    {
        echo '<ul class="view_header">';
        echo '<li class="li_50"></li>';
        echo '<li class="li_100">'.$this->lang->line('invoice_work_item_quantity').'</li>';
        echo '<li class="li_100">'.$this->lang->line('invoice_work_item_flat_fee').'</li>';
        echo '<li class="li_100">'.$this->lang->line('invoice_work_item_rate').'</li>';
        echo '<li class="li_100">'.$this->lang->line('invoice_time_total').'</li>';
        echo '<li class="li_100">'.$this->lang->line('invoice_work_item_total').'</li>';
        echo '</ul>';
        foreach ($billable_details as $billables):
            echo '<h3>'.$billables['description'].'</h3>';
            echo '<ul class="view_items">';
            /* START List Available Actions - Edit, Delete, etc. */
            $actions = "";
            // Edit
            $url = 'invoices/edit_work/'.$billables['unq'];
            $alt_text = $this->lang->line('common_edit');
            $actions .= "\n".edit_action($url, $alt_text);
            // Set Actions for Delete
            $url = 'invoices/delete_work/'.$billables['unq'];
            $alt_text = $this->lang->line('common_delete');
            $popup_text = $this->lang->line('common_delete_confirmation');
            $actions .= "\n".delete_action($url, $alt_text, $popup_text);
            /* END List Available Actions - Edit, Delete, etc. */
            echo '<li class="li_50">'.$actions.'</li>';
            echo '<li class="li_100">'.$billables['quantity'].'</li>';
            echo '<li class="li_100">'.$billables['flatfee'].'</li>';
            echo '<li class="li_100">'.$billables['hourlyrate'].'</li>';
            echo '<li class="li_100">'.convertTimeForm($work_item_time[$billables['unq']]).'</li>';
            $rate_total = ($billables['hourlyrate'] * $work_item_time[$billables['unq']] / 60);
            $due_amount = $billables['quantity'] * ($billables['flatfee'] + $rate_total);
            echo '<li class="li_100">'.number_format($due_amount, 2).'</li>';
            $invoice_total_due = $invoice_total_due + $due_amount;
            echo '<li class="li_100"></li>';
            //echo ' x ';
            //echo $tracktime.' =  ';
            //echo '&pound; '.$overall_due;
            echo '</ul>';
        endforeach;
    }
    echo '<p class="table_summary">';
    echo $this->lang->line('invoice_amount').': '.number_format($invoice_total_due, 2);
    echo '</p>';
}
// End of Billables
echo '</fieldset>';
echo '</form>';
$this->load->view('invoices/timetracked');
$this->load->view('footer');
/* End of file */
