<?php
$this->load->view('header');
echo '<fieldset>';
echo form_open('general/export_data');
echo '<p>';
echo form_label($this->lang->line('export_type'), 'export_type');
$id = 'id="export_type"';
echo form_dropdown('export_type', array('tasks'=>'Tasks','timetracking'=>'Timetracking','invoices'=>'Invoices'), '',
    $id);
echo '</p>';
// Display List of Projects For Export Facility
$project_list=array();
foreach($projects as $project):
$project_list[$project['unq']]=$project['name'];
endforeach;
echo '<p>';
echo form_label($this->lang->line('export_project'), 'export_project');
$id = 'id="export_project"';
echo form_dropdown('export_project', $project_list, '',
    $id);
echo '</p>';
echo form_submit('submit', $this->lang->line('export_data'));
echo '</p>';
echo '</fieldset>';
$this->load->view('footer');
?>