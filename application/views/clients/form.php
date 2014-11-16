<?php
$this->load->view('header');
echo '<fieldset>';
if (isset($client_details[0]['unq']))
{
    // Editing Existing Client
    $hidden = array('clientUnq' => $client_details[0]['unq']);
    echo form_open('clients/update', '', $hidden);
    /*
	$client_organisation = $client_details[0]['organisationname'];
    $client_name = $client_details[0]['contactname'];
    $client_telephone = $client_details[0]['orgtelephone'];
    $client_email = $client_details[0]['email'];
    $client_website = $client_details[0]['website'];
    $client_other = $client_details[0]['other'];
    $client_address = $client_details[0]['address'];*/

    
    // Organisation Name
    $client_organisation = set_value('client_organisation', isset($post['client_organisation']) ? htmlentities($post['client_organisation']) :
        $client_details[0]['organisationname']);
    // Client Name
    $client_name = set_value('client_name', isset($post['client_name']) ? htmlentities($post['client_name']) :
        $client_details[0]['contactname']);    
    // Client Telephone
    $client_telephone = set_value('client_telephone', isset($post['client_telephone']) ? htmlentities($post['client_telephone']) :
        $client_details[0]['orgtelephone']);    
    // Client Email
    $client_email = set_value('client_email', isset($post['client_email']) ? htmlentities($post['client_email']) :
        $client_details[0]['email']);    
    // Client Website
     $client_website = set_value('client_website', isset($post['client_website']) ? htmlentities($post['client_website']) :
        $client_details[0]['website']);   
    // Client Other
     $client_other = set_value('client_other', isset($post['client_other']) ? htmlentities($post['client_other']) :
        $client_details[0]['other']);   
    // Client Address
     $client_address = set_value('client_address', isset($post['client_address']) ? htmlentities($post['client_address']) :
        $client_details[0]['address']);    
	// Form Submit Text
	    $submit_text = $this->lang->line('client_update');   
}
else
{
    // Add a New Item
    echo form_open('clients/create');
    $client_organisation = set_value('client_organisation');
    $client_name = set_value('client_name');
    $client_telephone = set_value('client_telephone');
    $client_email = set_value('client_email');
    $client_website = set_value('client_website');
    $client_other = set_value('client_other');
    $client_address = set_value('client_address');
    $submit_text = $this->lang->line('client_add_new');
}
echo '<p>'.form_label($this->lang->line('common_required_input').$this->lang->line('client_organisation'),
    'client_organisation');
echo form_input(array('name' => 'client_organisation', 'id' => 'client_organisation',
    'maxlength' => '100', 'size' => '30', 'value' => $client_organisation)).'</p>';
echo '<p>'.form_label($this->lang->line('common_required_input').$this->lang->line('client_name'), 'client_name');
echo form_input(array('name' => 'client_name', 'id' => 'client_name',
    'maxlength' => '100', 'size' => '30', 'value' => $client_name)).'</p>';
echo '<p>'.form_label($this->lang->line('client_telephone'),
    'client_telephone');
echo form_input(array('name' => 'client_telephone', 'id' => 'client_telephone',
    'maxlength' => '15', 'size' => '15', 'value' => $client_telephone)).'</p>';
echo '<p>'.form_label($this->lang->line('common_required_input').$this->lang->line('client_email'), 'client_email');
echo form_input(array('name' => 'client_email', 'id' => 'client_email',
    'maxlength' => '100', 'size' => '30', 'value' => $client_email)).'</p>';
echo '<p>'.form_label($this->lang->line('client_website'), 'client_website');
echo form_input(array('name' => 'client_website', 'id' => 'client_website',
    'maxlength' => '100', 'size' => '30', 'value' => $client_website)).'</p>';
echo '<p>'.form_label($this->lang->line('client_address'), 'client_address',
    array('class' => 'tarea_label'));
echo form_textarea(array('name' => 'client_address', 'id' => 'client_address',
    'rows' => '5', 'cols' => '30', 'value' => $client_address)).'</p>';
echo '<p>'.form_label($this->lang->line('client_other'), 'client_other',
    array('class' => 'tarea_label'));
echo form_textarea(array('name' => 'client_other', 'id' => 'client_other',
    'rows' => '5', 'cols' => '30', 'value' => $client_other)).'</p>';
echo '<p>'.form_submit('submit_form', $submit_text).'</p>';
echo '</fieldset>';
echo '</form>';
$this->load->view('footer');
/* End of file */