<?php
$this->load->view('header');
echo '<fieldset>';
if (isset($timetrack_details[0]['unq']))
{
    // Editing Existing Item
    $hidden = array('timetrackUnq' => $timetrack_details[0]['unq']);
    // Timetrack Name
    $timetrack_name = set_value('timetrack_name', isset($post['timetrack_name']) ?
        htmlentities($post['timetrack_name']) : $timetrack_details[0]['item']);
    // Timetrack User
    $timetrack_user = set_value('timetrack_user', isset($post['timetrack_user']) ?
        htmlentities($post['timetrack_user']) : $timetrack_details[0]['user']);
    $update_urls = array('edit', 'update_existing');
    if (in_array($this->uri->segment(2), $update_urls))
    {
        echo form_open('timetrack/update_existing', '', $hidden);
        // Timetrack Start Date
        $timetrack_startdate = set_value('timetrack_start_date', isset($post['timetrack_start_date']) ?
            htmlentities($post['timetrack_start_date']) : date_conversion($timetrack_details[0]['startdate'],
            'export'));
        // Timetrack Time Spent
        $timetrack_time = set_value('timetrack_time', isset($post['timetrack_time']) ?
            htmlentities($post['timetrack_time']) : convertTimeForm($timetrack_details[0]['time']));
    }
    else
    {
        echo form_open('timetrack/update', '', $hidden);
    }
    $submit_text = $this->lang->line('timetrack_update');
}
else
{
    // Creating a New Timetrack Item
    echo form_open('timetrack/record_create');
    $timetrack_name = set_value('timetrack_name');
    $timetrack_user = set_value('timetrack_user', isset($post['timetrack_user']) ?
        htmlentities($post['timetrack_user']) : $this->session->userdata('user_id'));
        
        
    $timetrack_startdate = date("d M Y");
    $timetrack_time = "0:00";
  $submit_text = $this->lang->line('timetrack_create');
}
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('timetrack_name'), 'timetrack_name');
echo form_input(array('name' => 'timetrack_name', 'id' => 'timetrack_name',
    'maxlength' => '100', 'size' => '30', 'value' => $timetrack_name));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('common_required_input').$this->lang->line('timetrack_user'), 'timetrack_user');
echo form_dropdown('timetrack_user', $people, $timetrack_user);
if ($this->uri->segment(2) != 'stop_timer')
{
    echo '<p>';
    echo form_label($this->lang->line('common_required_input').$this->lang->line('timetrack_start_date'),
        'timetrack_start');
    echo form_input(array('name' => 'timetrack_start_date', 'id' =>
        'timetrack_start_date', 'maxlength' => '10', 'size' => '10', 'value' => $timetrack_startdate,
        'class' => 'date_input'));
    echo '</p>';
    echo '<p>';
    echo form_label($this->lang->line('common_required_input').$this->lang->line('timetrack_hours'),
        'timetrack_time');
    echo form_input(array('name' => 'timetrack_time', 'id' => 'timetrack_time',
        'maxlength' => '5', 'size' => '5', 'value' => $timetrack_time));
}
echo '</p>';
echo '<p>';
echo form_submit('submit_form', $submit_text);
echo '</p>';
echo '</fieldset>';
echo '</form>';
$this->load->view('footer');
/* End of file */