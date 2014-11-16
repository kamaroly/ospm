<?php
$this->load->view('header');
if (isset($task_details[0]['unq']))
{
    // Editing Existing Ite,
    $hidden = array('taskUnq' => $task_details[0]['unq'], 'task_old_status' => $task_details[0]['status']);
    echo form_open('tasks/update', '', $hidden);
    // Task Name
    $task_name = set_value('task_name', isset($post['task_name']) ? htmlentities($post['task_name']) :
        $task_details[0]['name']);
    // Task Description
    $task_description = set_value('task_description', isset($post['task_description']) ?
        htmlentities($post['task_description']) : $task_details[0]['description']);
    // Task Comments
    $task_comments = set_value('task_comments', isset($post['task_comments']) ?
        htmlentities($post['task_comments']) : $task_details[0]['comments']);
    // Task Category
    $task_category = set_value('task_category', isset($post['task_category']) ?
        htmlentities($post['task_category']) : $task_details[0]['category']);
    // Task Assigned To
    $assigned_to = set_value('assigned_to', isset($post['assigned_to']) ?
        htmlentities($post['assigned_to']) : $task_details[0]['assignedto']);
    // Task Priority
    $priority = set_value('priority', isset($post['priority']) ? htmlentities($post['priority']) :
        $task_details[0]['priority']);
    // Task Type
    $task_type = set_value('task_type', isset($post['task_type']) ? htmlentities($post['task_type']) :
        $task_details[0]['type']);
    // Task Invoice
    $task_invoice = set_value('task_invoice', isset($post['task_invoice']) ?
        htmlentities($post['task_invoice']) : $task_details[0]['invoice']);
    // Task Estimate
    $task_estimate = set_value('task_estimate', isset($post['task_estimate']) ?
        htmlentities($post['task_estimate']) : convertTimeForm($task_details[0]['estimate']));
    // Task Actual
    $task_actual = set_value('task_actual', isset($post['task_actual']) ?
        htmlentities($post['task_actual']) : convertTimeForm($task_details[0]['actual']));
    // Task Target
    $task_target = set_value('task_target', isset($post['task_target']) ?
        htmlentities($post['task_target']) : date_conversion($task_details[0]['target'],
        'export'));
    // Task Status
    $task_status = set_value('task_status', isset($post['task_status']) ?
        htmlentities($post['task_status']) : $task_details[0]['status']);
    $submit_text = $this->lang->line('task_update');
    $task_created = $task_details[0]['created'];
    $task_closed = $task_details[0]['closed'];
}
else
{
    // Add a New Item
    echo form_open('tasks/create');
    $task_name = set_value('task_name');
    $task_description = set_value('task_description');
    $task_category = set_value('task_category');
    $assigned_to = set_value('assigned_to');
    $priority = set_value('priority');
    $task_type = set_value('task_type');
    $task_invoice = set_value('task_invoice');
    $task_target = set_value('task_target');
    $task_estimate = set_value('task_estimate');
    $submit_text = $this->lang->line('task_create_button');
}
echo '<fieldset class="span-24">';
echo '<div class="span-12">';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('task_type'), 'task_type');
$id = 'id="task_type"';
echo form_dropdown('task_type', $this->config->item('task_type_options'), $task_type,
    $id);
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('task_name'), 'task_name');
echo form_input(array('name' => 'task_name', 'id' => 'task_name', 'maxlength' =>
    '100', 'size' => '30', 'value' => $task_name));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('task_priority'), 'priority');
$id = 'id="priority"';
echo form_dropdown('priority', $this->config->item('task_priority_options'), $priority,
    $id);
echo '</p>';
// Additional Fields Displayed for Edit Task
if (isset($task_details[0]['unq']))
{
    echo '<p>';
    echo form_label($this->lang->line('common_required_input').$this->lang->line('task_status'), 'task_status');
    echo form_dropdown('task_status', arrange_dropdown($this->config->item('task_status_options')),
        $task_status);
    if (($task_status == "Closed") || ($task_status == "Rejected")) echo ', '.
            convert_date($task_closed, 'yes', $this->session->userdata('dateformat'));
	echo '</p>';
}

echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('task_estimate'), 'task_estimate');
echo form_input(array('name' => 'task_estimate', 'id' => 'task_estimate',
    'maxlength' => '5', 'size' => '5', 'value' => $task_estimate));
echo '</p>';

// Additional Fields Displayed for Edit Task
if (isset($task_details[0]['unq']))
{
    echo '<p>';
    echo form_label($this->lang->line('task_actual'), 'task_actual');
    echo form_input(array('name' => 'task_actual', 'id' => 'task_actual',
        'maxlength' => '5', 'size' => '5', 'value' => $task_actual));
    echo '</p>';
}
echo '<p>';
echo form_label($this->lang->line('task_assigned_to'), 'assigned_to');
$id = 'id="assigned_to"';
echo form_dropdown('assigned_to', $people, $assigned_to, $id);
echo '</p>';


echo '<p>';
echo form_submit('submit_form', $submit_text);
echo '</p>';
echo '</div>';

echo '<div class="span-11">';
echo '<span class="indent"><a class="tasks-filter">'.$this->lang->line('task_advanced').'</a></span>';    
echo '<div id="advanced_task_items">';

echo '<p>';
echo form_label($this->lang->line('task_target'), 'task_target');
echo form_input(array('name' => 'task_target', 'id' => 'task_target',
    'maxlength' => '11', 'size' => '11', 'value' => $task_target, 'class' =>
    'date_input'));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('task_new_category'), 'task_category');
echo form_input(array('name' => 'task_category', 'id' => 'task_category',
    'maxlength' => '20', 'size' => '20', 'value' => ''));
echo '</p>';
if (count($categories) > 0)
{
    echo '<p>';
    echo form_label($this->lang->line('task_existing_category'), 'task_categorylist');
    $id = 'id="task_categorylist"';
    echo form_dropdown('task_categorylist', $categories, $task_category, $id);
    echo '</p>';
}
if (count($invoices) > 0)
{
    $invoice_list[''] = '';
    foreach ($invoices as $invoice):
        $invoice_list[$invoice['unq']] = $invoice['name'];
    endforeach;
    echo '<p>';
    echo form_label($this->lang->line('task_invoice'), 'task_invoice');
    $id = 'id="task_invoice"';
    echo form_dropdown('task_invoice', $invoice_list, $task_invoice, $id);
    echo '</p>';
}
echo '<p>';
echo form_label($this->lang->line('task_description'), 'task_description', array
    ('class' => 'tarea_label'));
echo form_textarea(array('name' => 'task_description', 'id' =>
    'task_description', 'rows' => '5', 'cols' => '25', 'value' => $task_description));
echo '</p>';
// Additional Fields Displayed for Edit Task
if (isset($task_details[0]['unq']))
{
    echo '<p>';
    echo form_label($this->lang->line('task_comments'), 'task_comments', array('class' =>
        'tarea_label'));
    echo form_textarea(array('name' => 'task_comments', 'id' => 'task_comments',
        'rows' => '5', 'cols' => '25', 'value' => $task_comments));
    echo '<p>';
}
echo '</div>';
echo '</div>';
echo '</fieldset>';
echo '</form>';
$this->load->view('footer');
/* End of file */