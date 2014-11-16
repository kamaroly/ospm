<?php
$this->load->view('header');
echo '<fieldset>';
if (isset($user_details[0]['unq']))
{
    // Editing Existing Item
    $hidden = array('UserUnq' => $user_details[0]['unq']);
    echo form_open('users/update', '', $hidden);
    // User Full Name
    $user_fullname = set_value('user_fullname', isset($post['user_fullname']) ?
        htmlentities($post['user_fullname']) : $user_details[0]['fullname']);
    // User Email
    $user_email = set_value('user_email', isset($post['user_email']) ?
        htmlentities($post['user_email']) : $user_details[0]['email']);
    // User Type
    $user_type = set_value('user_type', isset($post['user_type']) ?
        htmlentities($post['user_type']) : $user_details[0]['type']);
    // User Status
    $user_status = set_value('user_status', isset($post['user_status']) ?
        htmlentities($post['user_status']) : $user_details[0]['status']);
    $submit_text = $this->lang->line('user_submit_edit');
}
else
{
    // Add a New Item
    echo form_open('users/create');
    $user_fullname = set_value('user_fullname');
    $user_email = set_value('user_email');
    $user_type = set_value('user_type');
    $user_status = set_value('user_status');
    $submit_text = $this->lang->line('user_submit_add');
}
echo '<p>'.form_label($this->lang->line('user_fullname'),
    'user_fullname');
echo form_input(array('name' => 'user_fullname', 'id' => 'user_fullname',
    'maxlength' => '100', 'size' => '30', 'value' => $user_fullname)).'</p>';
echo '<p>'.form_label($this->lang->line('user_email'), 'user_email');
echo form_input(array('name' => 'user_email', 'id' => 'user_email', 'maxlength' =>
    '100', 'size' => '30', 'value' => $user_email)).'</p>';
echo '<p>'.form_label($this->lang->line('user_type'), 'user_type');
echo form_dropdown('user_type', arrange_dropdown($this->config->item('user_types')),
    $user_type).'</p>';
echo '<p>'.form_label($this->lang->line('user_status'), 'user_status');
echo form_dropdown('user_status', arrange_dropdown($this->config->item('user_statuses')),
    $user_status).'</p>';
echo '<p>'.form_submit('submit_form', $submit_text).'</p>';
echo '</fieldset>';
echo '</form>';
$this->load->view('footer');
/* End of file */