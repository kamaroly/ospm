<?php
$this->load->view('header');
echo '<fieldset>';
if (isset($project_details->unq))
{
    // Editing Existing Ite,
    $hidden = array('projectUnq' => $project_details->unq);
    echo form_open('projects/update', '', $hidden);
    // Invoice Name
    $project_name = set_value('project_name', isset($post['project_name']) ?
        htmlentities($post['project_name']) : $project_details->name);
    // Invoice Name
    $project_description = set_value('project_description', isset($post['project_description']) ?
        htmlentities($post['project_description']) : $project_details->description);
    // Invoice Name
    $project_status = set_value('project_status', isset($post['project_status']) ?
        htmlentities($post['project_status']) : $project_details->status);
    // Invoice Name
    $project_client = set_value('project_client', isset($post['project_client']) ?
        htmlentities($post['project_client']) : $project_details->client);
    //$project_name = $project_details->name;
    //$project_description = $project_details->description;
    //$project_status = $project_details->status;
    //$project_client = $project_details->client;
    $submit_text = $this->lang->line('project_update');
}
else
{
    // Add a New Item
    echo form_open('projects/create_project');
    $project_name = set_value('project_name');
    $project_description = set_value('project_description');
    $project_status = set_value('project_status');
    $project_client = set_value('project_client');
    $submit_text = $this->lang->line('project_create');
}
// Project Name
echo '<p>'.form_label($this->lang->line('common_required_input').$this->lang->line('project_name'),
    'project_name');
echo form_input(array('name' => 'project_name', 'id' => 'project_name',
    'maxlength' => '100', 'size' => '30', 'value' => $project_name)).'</p>';
// View Client List
echo '<p>'.form_label($this->lang->line('project_client'),
    'project_clientlist');
if (count($clients) > 1)
{
    echo form_dropdown('project_clientlist', $clients, $project_client);
}
else
{
	echo '<input type="text" disabled="disabled" size="40" value="'.$this->lang->line('client_no_results').'" />';
}
echo '<span class="discreet">'.$this->lang->line('add_client').'</span>'.'</p>';
// Project Status
echo '<p>'.form_label($this->lang->line('project_status'),
    'project_status');
echo form_dropdown('project_status', $this->config->item('project_statuses'), $project_status).'</p>';
// Project Description
echo '<p>'.form_label($this->lang->line('project_description'),
    'project_description', array('class' => 'tarea_label'));
echo form_textarea(array('name' => 'project_description', 'id' =>
    'project_description', 'rows' => '5', 'cols' => '30', 'value' => $project_description)).'</p>';

echo '<p>'.form_submit('submit_form', $submit_text);
// Delete Project - Administrators Only
if (($this->session->userdata('usertype') == "Administrator") && (isset($project_details->
    unq)))
{
    // Create Javascript for Delete Confirmation
    $attributes = array('onclick' => 'return confirm(\''.$this->lang->line('common_delete_confirmation').
        '\')');
    // Set Actions for Delete
   echo "\n".anchor('projects/project_delete', '<input class="delete_button" type="button" value="Delete Project" />', $attributes);

}
echo '</p>';
echo '</fieldset>';
echo '</form>';
$this->load->view('footer');
/* End of file */