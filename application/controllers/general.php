<?php
class general extends Controller
{
    function general()
    {
        parent::Controller();
    }
    function credits()
    {
        // Display Credits Page
        $data['page_heading'] = $this->lang->line('common_credits');
        $this->load->view('general/credits.php', $data);
        ;
    }
    function database_backup()
    {
        // Backup Database
        $this->load->dbutil();
        $this->load->helper(array('file', 'download'));
        $filename = 'cyberience_projects';
        $prefs = array('format' => 'zip', 'filename' => $filename.'_'.date("Ymd").'.zip', );
        // Backup your entire database and assign it to a variable
        $backup = &$this->dbutil->backup($prefs);
        write_file('backup/'.$prefs['filename'], $backup);
        force_download($prefs['filename'], $backup);
    }
    function export()
    {
        // Get List of Projects
        $this->load->model('projects_model');
        $data['projects'] = $this->projects_model->get_all_projects($this->session->userdata('company'));
        // Set Page Title
        $data['page_heading'] = $this->lang->line('export_title');
        // Load Page View
        $this->load->view('general/export.php', $data);
    }
    function export_data()
    {
        $export_type = $this->input->post('export_type');
        $export_project = (int)$this->input->post('export_project');
        if ($export_type == "tasks")
        {
            $this->export_tasks($export_project);
        } elseif ($export_type == "timetracking")
        {
            echo "Exporting Timetracking";
        } elseif ($export_type == "invoices")
        {
            echo "Exporting Invoices";
        }
    }
    function export_tasks($project_id)
    {
        // Export Data as CSV File
        $this->load->dbutil();
        $this->load->helper(array('file', 'download'));
        $this->load->model('tasks_model');
        $filters = array('0' => 'viewall');
        $query = 'select unq,(select name from cyberience_projects where project=unq) as project,name,type,status,priority,description,comments,category,(select fullname from cyberience_people where createdby=unq) as createdby,created,(select fullname from cyberience_people where assignedto=unq) as assignedto,closed,target,estimate,actual,invoice from cyberience_tasks;';
        $query = $this->tasks_model->get_all_tasks($this->session->userdata('company'), $project_id, $filters);
        $delimiter = ",";
        $newline = "\r\n";
        $last_query = $this->db->last_query();
        $query = $this->db->query($last_query);
        $export_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        $filename = 'cyberience_tasks';
        $prefs = array('format' => 'csv', 'filename' => $filename.'_'.date("Ymd").'.csv', );
        write_file('export/'.$prefs['filename'], $export_data);
        force_download($prefs['filename'], $export_data);
    }
}
/* End o


f file */
