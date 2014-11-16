<?php
$this->load->view('header');
echo '<fieldset>';
echo form_open('projects/project_destroy');
echo '<p class="notice">'.$this->lang->line('project_delete_msg').'</p>';
echo '<p>'.form_label($this->lang->line('user_repeat_password'), 'Password');
echo form_password(array('name'=> 'Password','id'=> 'Password','maxlength'=> '15','size'=> '15')).'</p>';

echo '<p>';
echo form_submit('submit', $this->lang->line('project_delete'));
echo '</p>';
echo '</fieldset>';
$this->load->view('footer');
?>