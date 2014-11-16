<?php
class Timetrack extends Controller
{
    function Timetrack()
    {
        parent::Controller();
        // load 'Timetrack' model
        $this->load->model('timetrack_model');
    }
    // Display Timetrack List
    function index()
    {
        // Set Filter to Pass to Model
        $filters = array('status' => $this->session->userdata('view_archived_times'));
        // Get Timetrack Items from Database
        $data['timetracks'] = $this->timetrack_model->get_all_times($this->session->userdata('company'), $this->session->userdata('project_id'), $filters);
        // Set Page Heading
        $data['page_heading'] = $this->lang->line('timetrack_main');
        if ($this->session->userdata('view_archived_times') == 0)
        {
            $data['page_heading'] = $this->lang->line('timetrack_main');
        }
        else
        {
            $data['page_heading'] = $this->lang->line('timetrack_archives');
        }
        // Get List of Available Users for Current Active Company
        $this->load->model('users_model');
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Display Timetrack Output
        $this->load->view('timetrack/view', $data);
    }
    // Set Timetrack Filters
    function filter($filter)
    {
        // Setup Array of possible filters
        $filtertypes = array('ttpeople');
        // Split Input Array - Format is [FILTERNAME]_[FILTERID]
        $filtersplit = explode("_", $filter, 2);
        // Identify if Filter is a match / valid filter
        if (array_search($filtersplit[0], $filtertypes) >= 0)
        {
            // Setup New Filter and Update Session Variable
            $filtername = $filtersplit[0].'_filter'; // Setup filter name
            $this->session->set_userdata($filtername, str_replace('_', ' ', $filtersplit[1]));
        }
        // Redirect to Main Timetrack Page
        redirect('timetrack');
    }
    // Initialise Timetracking
    function start_timer()
    {
        // Check That Timetracker is Not Running Already
        if (!$this->session->userdata('timestart'))
        {
            // Session Variable in format for Javascript Timer
            $this->session->set_userdata('timestart', date("Y,m,d,H,i,s", (time())));
            // Session Variable with timestamp
            $this->session->set_userdata('timestart2', time());
            // Session Variable for Database
            $timestartDB = date("Y-m-d H:i:s");
            // Set Array for Updating Database with Initial Timetrack Entry
            $timerInfo = array('unq' => null, 'startdate' => $timestartDB, 'project' => $this->session->userdata('project_id'), 'company' => $this->session->userdata('company'), 'user' => $this->session->
                userdata('user_id'));
            // Update Database
            if ($this->timetrack_model->insert_timetrack($timerInfo))
            {
                // Set TimerID Database as Session Variable for later use
                $this->session->set_userdata('timerid', mysql_insert_id()); // Save TimerID Session
                // Set Confirmation Message
                $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_started'));
            }
            // Redirect to Tasks
            redirect('dashboard');
        }
        else
        {
            // Timetrack Already Started Message
            $this->session->set_flashdata('error', $this->lang->line('timetrack_msg_alreadystarted'));
            // Redirect to Tasks
            redirect('dashboard');
        }
    }
    // Function for Displaying Form Allowing Timetrack to be Closed
    function stop_timer()
    {
        // Get List of Available Users for Current Active Company
        $this->load->model('users_model');
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Get Existing Timetrack Details
        $timer_id = (int)$this->session->userdata('timerid');
        $data['timetrack_details'] = $this->timetrack_model->get_timetrack($this->session->userdata('company'), $timer_id);
        // Get List of Active Project for User
        $this->load->model('projects_model'); // load 'Projects' model
        $data['project_list'] = $this->projects_model->get_selected_projects($this->session->userdata('company'), 'NOT IN', $this->config->item('project_closed_status'));
        // Set HTML Variables and Load View
        $data['page_heading'] = $this->lang->line('timetrack_stop');
        $this->load->view('timetrack/form.php', $data);
    }
    function edit($id)
    {
        // Get List of Available Users for Current Active Company
        $this->load->model('users_model');
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Get Existing Timetrack Details
        $timer_id = (int)$id;
        $data['timetrack_details'] = $this->timetrack_model->get_timetrack($this->session->userdata('company'), $timer_id);
        // Get List of Active Project for User
        $this->load->model('projects_model'); // load 'Projects' model
        $data['project_list'] = $this->projects_model->get_selected_projects($this->session->userdata('company'), 'NOT IN', $this->config->item('project_closed_status'));
        // Set HTML Variables and Load View
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js');
        // Set Page Heading
        $data['page_heading'] = $this->lang->line('timetrack_edit');
        $this->load->view('timetrack/form.php', $data);
    }
    function update()
    {
        // updates an item in the database using the data from Edit
        if ($this->form_validation->run('timetrack') == true)
        {
            $elapsedtime = round((time() - $this->session->userdata('timestart2')) / 60, 0);
            $timetrackInfo['item'] = $this->input->post('timetrack_name');
            $timetrackInfo['user'] = $this->input->post('timetrack_user');
            $timetrackInfo['time'] = $elapsedtime;
            $trackID = (int)$this->input->post('timetrackUnq');
            $this->timetrack_model->update_timetrack($this->session->userdata('company'), $trackID, $timetrackInfo);
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_updated'));
            // Unset Timer Variables
            $this->session->unset_userdata('timestart');
            $this->session->unset_userdata('timestart2');
            $this->session->unset_userdata('timerid');
            redirect('timetrack');
        }
        else
        {
            $this->stop_timer();
        }
    }
    function update_existing()
    {
        // updates an item in the database using the data from Edit
        if ($this->form_validation->run('timetrack_existing') == true)
        {
            // Parse Start Time and Finish Time
            $startdate = date_conversion($this->input->post('timetrack_start_date'), 'import');
            $spenttime_array = explode(':', $this->input->post('timetrack_time'));
            $spenttime = $spenttime_array[0] * 60 + $spenttime_array[1];
            $timetrackInfo['item'] = $this->input->post('timetrack_name');
            $timetrackInfo['user'] = $this->input->post('timetrack_user');
            $timetrackInfo['startdate'] = $startdate;
            $timetrackInfo['time'] = $spenttime;
            $trackID = (int)$this->input->post('timetrackUnq');
            $this->timetrack_model->update_timetrack($this->session->userdata('company'), $trackID, $timetrackInfo);
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_updated'));
            redirect('timetrack');
        }
        else
        {
            $this->edit($this->input->post('timetrackUnq'));
        }
    }
    function destroy($id)
    {
        // removes an item from the database
        $timetrackID = (int)$id;
        if ($this->timetrack_model->delete_timetrack($this->session->userdata('company'), $timetrackID))
        {
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_deleted'));
        }
        redirect('timetrack');
    }
    function archive($id)
    {
        // removes an item from the database
        $timetrackID = (int)$id;
        $timetrack_data['status'] = 'Archived';
        if ($this->timetrack_model->update_timetrack($this->session->userdata('company'), $timetrackID, $timetrack_data))
        {
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_archived'));
        }
        redirect('timetrack');
    }
    function unarchive($id)
    {
        // removes an item from the database
        $timetrackID = (int)$id;
        $timetrack_data['status'] = '';
        if ($this->timetrack_model->update_timetrack($this->session->userdata('company'), $timetrackID, $timetrack_data))
        {
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_unarchived'));
        }
        redirect('timetrack');
    }
    function toggle_archives()
    {
        if ($this->session->userdata('view_archived_times') == 'Archived')
        {
            $this->session->set_userdata('view_archived_times', '');
        }
        else
        {
            $this->session->set_userdata('view_archived_times', 'Archived');
        }
        $prevurl = str_replace(site_url(), '', $_SERVER['HTTP_REFERER']);
        redirect($prevurl);
    }
    function record_time()
    {
        // Get List of Available Users for Current Active Company
        $this->load->model('users_model');
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Get List of Active Project for User
        $this->load->model('projects_model'); // load 'Projects' model
        $data['project_list'] = $this->projects_model->get_selected_projects($this->session->userdata('company'), 'NOT IN', $this->config->item('project_closed_status'));
        // Set HTML Variables and Load View
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js');
        $data['page_heading'] = $this->lang->line('timetrack_record');
        $this->load->view('timetrack/form.php', $data);
    }
    function record_create()
    {
        // updates an item in the database using the data from Edit
        if ($this->form_validation->run('timetrack_existing') == true)
        {
            // Parse Start Time and Finish Time
            $startdate = date_conversion($this->input->post('timetrack_start_date'), 'import');
            $spenttime_array = explode(':', $this->input->post('timetrack_time'));
            $spenttime = $spenttime_array[0] * 60 + $spenttime_array[1];
            $timetrackInfo = array('unq' => null, 'company' => $this->session->userdata('company'), 'item' => $this->input->post('timetrack_name'), 'user' => $this->input->post('timetrack_user'), 'project' => $this->
                session->userdata('project_id'), 'startdate' => $startdate, 'time' => $spenttime);
            $this->timetrack_model->insert_timetrack($timetrackInfo);
            $this->session->set_flashdata('success', $this->lang->line('timetrack_msg_created'));
            redirect('timetrack');
        }
        else
        {
            $this->record_time();
        }
    }
}
/* End of file */