<?php
$this->load->view('header');
if (isset($work_item_details[0]['unq']))
{
    // Editing Existing Item
    $invoice_id = $work_item_details[0]['invoice_id'];
    $hidden = array('work_item_id' => $work_item_details[0]['unq'], 'invoice_id' => $work_item_details[0]['invoice_id']);
    echo form_open('invoices/update_work', '', $hidden);
    $invoice_id_pad = str_pad($invoice_id, 7, "0", STR_PAD_LEFT);
    // Work Item Quantity
    $invoice_work_item_quantity = set_value('invoice_work_item_quantity', isset($post['invoice_work_item_quantity']) ?
        htmlentities($post['invoice_work_item_quantity']) : $work_item_details[0]['quantity']);
    // Work Item Description
    $invoice_work_item_description = set_value('invoice_work_item_description', isset($post['invoice_work_item_description']) ?
        htmlentities($post['invoice_work_item_description']) : $work_item_details[0]['description']);
    // Work Item Flat Fee
    $invoice_work_item_flat_fee = set_value('invoice_work_item_flat_fee', isset($post['invoice_work_item_flat_fee']) ?
        htmlentities($post['invoice_work_item_flat_fee']) : $work_item_details[0]['flatfee']);
    // Work Item Hourly Rate
    $invoice_work_item_rate = set_value('invoice_work_item_rate', isset($post['invoice_work_item_rate']) ? htmlentities($post['invoice_work_item_rate']) :
        $work_item_details[0]['hourlyrate']);
    $submit_text = $this->lang->line('invoice_work_item_update');
}
else
{
    // Add a New Item
    $hidden = array('invoiceUnq' => $invoice_id);
    echo form_open('invoices/create_work', '', $hidden);
    $invoice_id_pad = str_pad($invoice_id, 7, "0", STR_PAD_LEFT);
    $invoice_work_item_description = set_value('invoice_work_item_description');
    $invoice_work_item_rate = set_value('invoice_work_item_rate');
    $invoice_work_item_flat_fee = set_value('invoice_work_item_flat_fee');
    $invoice_work_item_quantity = set_value('invoice_work_item_quantity');
    if ($invoice_work_item_quantity == "") $invoice_work_item_quantity = 1;
    $submit_text = $this->lang->line('invoice_work_item_add');
}
echo '<fieldset>';
// Display Invoice ID
echo '<p>';
echo form_label($this->lang->line('invoice_id'), 'invoice_id');
echo form_input(array('name' => 'invoice_id', 'id' => 'invoice_id', 'maxlength' => '10', 'size' => '10', 'value' => $invoice_id_pad,
    'disabled' => 'disabled'));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_name'), 'invoice_name');
echo form_input(array('name' => 'invoice_name', 'id' => 'invoice_name', 'maxlength' => '100', 'size' => '30', 'value' =>
    $invoice_details[0]['name'], 'disabled' => 'disabled'));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('invoice_work_item_quantity'),
    'invoice_work_item_quantity');
echo form_input(array('name' => 'invoice_work_item_quantity', 'id' => 'invoice_work_item_quantity', 'maxlength' => '3',
    'size' => '3', 'value' => $invoice_work_item_quantity));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('invoice_work_item_description'),
    'invoice_work_item_description', array('class' => 'tarea_label'));
echo form_textarea(array('name' => 'invoice_work_item_description', 'id' => 'invoice_work_item_description', 'rows' =>
    '2', 'cols' => '60', 'value' => $invoice_work_item_description));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_work_item_flat_fee'), 'invoice_work_item_flat_fee');
echo form_input(array('name' => 'invoice_work_item_flat_fee', 'id' => 'invoice_work_item_flat_fee', 'maxlength' => '10',
    'size' => '6', 'value' => $invoice_work_item_flat_fee));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('invoice_work_item_rate'), 'invoice_work_item_rate');
echo form_input(array('name' => 'invoice_work_item_rate', 'id' => 'invoice_work_item_rate', 'maxlength' => '6', 'size' =>
    '6', 'value' => $invoice_work_item_rate));
echo '</p>';
echo '<p>';
echo form_submit('submit_form', $submit_text);
echo '</p>';
echo '</fieldset>';
echo '</form>';
if (isset($work_item_details[0]['unq']))
{
    if (count($tracked_times) > 0)
    {
        echo '<fieldset class="view_table">';
        echo '<h2>'.$this->lang->line('invoice_tracked_items').'</h2>';
        echo '<ul class="view_header">';
        echo '<li class="li_50"></li>';
        echo '<li class="li_200">'.$this->lang->line('invoice_status').'</li>';
        echo '<li class="li_200">'.$this->lang->line('task_estimate').'</li>';
        echo '<li class="li_150">'.$this->lang->line('task_actual').'</li>';
        echo '</ul>';
        foreach ($tracked_times as $task):
            echo '<h3>'.$task['name'].'</h3>';
            echo '<ul class="view_items">';
            $actions = "";
            $url = 'tasks/edit/'.$task['unq'];
            $alt_text = $this->lang->line('common_edit');
            $actions .= "\n".edit_action($url, $alt_text);
            echo '<li class="li_50">'.$actions.'</li>';
            echo '<li class="li_200">'.$task['status'].'</li>';
            echo '<li class="li_200">'.convertTimeForm($task['estimate']).'</li>';
            echo '<li class="li_200">'.convertTimeForm($task['actual']).'</li>';
            echo '</ul>';
        endforeach;
        echo '</fieldset>';
    }
}
$this->load->view('invoices/timetracked');
$this->load->view('footer');
/* End of file */
