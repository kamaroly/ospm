<?php
$this->load->view('header');
echo '<fieldset>';
if (isset($user_details[0]['unq']))
{
    // Editing Existing Item
    echo form_open('users/profile_update');
    // Full Name
    $user_fullname = set_value('user_fullname', isset($post['user_fullname']) ?
        htmlentities($post['user_fullname']) : $user_details[0]['fullname']);
    // Email
    $user_email = set_value('user_email', isset($post['user_email']) ? htmlentities
        ($post['user_email']) : $user_details[0]['email']);
    $submit_text = $this->lang->line('user_submit_myprofile');
}
echo '<p>'.form_label($this->lang->line('user_fullname'),
    'user_fullname');
echo form_input(array('name' => 'user_fullname', 'id' => 'user_fullname',
    'maxlength' => '100', 'size' => '30', 'value' => $user_fullname)).'</p>';
echo '<p>'.form_label($this->lang->line('user_email'), 'user_email');
echo form_input(array('name' => 'user_email', 'id' => 'user_email', 'maxlength' =>
    '100', 'size' => '30', 'value' => $user_email)).'</p>';
echo '<p>'.form_submit('submit_form', $submit_text).'</p>';
echo '</fieldset>';
echo '</form>';
echo form_open('users/update_password');
echo '<fieldset>';
echo '<h3>'.$this->lang->line('user_change_password').'</h3>';
echo '<p>'.form_label($this->lang->line('user_current_password'), 'user_current_password');
echo form_password(array('name' => 'user_current_password', 'id' => 'user_current_password', 'maxlength' =>
    '50', 'size' => '20', 'value' => '')).'</p>';
echo '<p>'.form_label($this->lang->line('user_new_password'), 'user_new_password');
echo form_password(array('name' => 'user_new_password', 'id' => 'user_new_password', 'maxlength' =>
    '50', 'size' => '20', 'value' => '')).'</p>';
echo '<p>'.form_label($this->lang->line('user_repeat_new_password'), 'user_repeat_new_password');
echo form_password(array('name' => 'user_repeat_new_password', 'id' => 'user_repeat_new_password', 'maxlength' =>
    '50', 'size' => '20', 'value' => '')).'</p>';
echo '<p>'.form_submit('submit_form', $this->lang->line('user_password_update')).'</p>';
echo '</fieldset>';
echo '</form>';

$this->load->view('footer');
/* End of file */