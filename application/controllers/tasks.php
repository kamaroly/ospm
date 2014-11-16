<?php
class tasks extends Controller
{
    function tasks()
    {
        parent::Controller();
        $this->load->model('tasks_model');
        $this->load->model('users_model');
        $this->load->model('invoices_model');
    }
    function index()
    {
        if (preg_match('/test.php/', $_SERVER['PHP_SELF']))
        {
            return;
        }
        // Get All Project Tasks and Load Categories and Users
        $data = $this->get_all_tasks();
        // Set HTML Variables and Load View
        $data['page_heading'] = $this->lang->line('task_view');
        $this->load->view('tasks/view.php', $data);
    }
    function get_all_tasks()
    {
        // Count All Open Tasks for Company
        $data['overall_company_tasks'] = $this->tasks_model->count_tasks($this->session->userdata('company'), '*');
        // Set Filter to Pass to Model
        $filters = array('status' => $this->session->userdata('status_filter'), 'assignedto' => $this->session->userdata('assignedto_filter'), 'priority' => $this->session->userdata('priority_filter'),
            'category' => $this->session->userdata('category_filter'));
        // Get All Open Tasks
        $data['tasks'] = $this->tasks_model->get_all_tasks($this->session->userdata('company'), $this->session->userdata('project_id'), $filters);
        // Get Task Categories
        $data['categories'] = $this->tasks_model->get_categories($this->session->userdata('company'), $this->session->userdata('project_id'));
        // Get List of Available Users for Current Active Company
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Return All Data
        return $data;
    }
    /*
    |   Function for Adding a New Task
    */
    function add()
    {
        /*
        |   Set Local Variables
        */
        $company = $this->session->userdata('company');
        $project = $this->session->userdata('project_id');
        $due_statuses = $this->config->item('invoice_due_status_types');
        /*
        |   Get Task Categories
        */
        $data['categories'] = $this->tasks_model->get_categories($company, $project);
        /*
        |   Get Invoice Details
        */
        $data['invoices'] = $this->invoices_model->get_active_billables($company, $project, $due_statuses);
        /*
        |   Get List of Available Users for Current Active Company
        */
        $data['people'] = $this->users_model->get_people($company);
        /*
        |   Set Additional CSS and Javascript Pages
        */
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js', base_url().'js/toggle_advanced_tasks.js');
        /*
        |   Set Page Heading
        */
        $data['page_heading'] = $this->lang->line('task_add');
        /*
        | Load HTML
        */
        $this->load->view('tasks/form.php', $data);
    }
    function edit($id) // Edit Task Form

    {
        // Get Existing Task Details
        $task_id = (int)$id;
        $data['task_details'] = $this->tasks_model->get_task($this->session->userdata('company'), $this->session->userdata('project_id'), $task_id);
        // Get Task Categories
        $data['categories'] = $this->tasks_model->get_categories($this->session->userdata('company'), $this->session->userdata('project_id'));
        // Get Invoice Details
        $data['invoices'] = $this->invoices_model->get_active_billables($this->session->userdata('company'), $this->session->userdata('project_id'), $this->config->item('invoice_due_status_types'));
        // Get List of Available Users for Current Active Company
        $data['people'] = $this->users_model->get_people($this->session->userdata('company'));
        // Set HTML Variables and Load View
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js', base_url().'js/toggle_advanced_tasks.js');
        $data['page_heading'] = $this->lang->line('task_edit');
        $this->load->view('tasks/form.php', $data);
    }
    function create() // Insert Task into Database

    {
        // saves an item to the database using the data from New
        if ($this->form_validation->run('tasks') == true)
        {
            // Identify Category - Input Field over-rules dropdown
            if ($this->input->post('task_category') <> "") $category = $this->input->post('task_category');
            else  $category = $this->input->post('task_categorylist');
            // Estimated Time
            $estTime = 0;
            if ($this->input->post('task_estimate') != '')
            {
                $estTimeArray = explode(':', $this->input->post('task_estimate'));
                $estTime = $estTimeArray[0] * 60 + $estTimeArray[1];
            }
            // Create Array of Field Inputs for Database
            $taskInfo['unq'] = NULL;
            $taskInfo['name'] = $this->input->post('task_name');
            $taskInfo['description'] = $this->input->post('task_description');
            $taskInfo['status'] = $this->config->item('initial_status');
            $taskInfo['type'] = $this->input->post('task_type');
            $taskInfo['category'] = str_replace('_', ' ', $category);
            if ($this->input->post('task_invoice'))
            {
                $taskInfo['invoice'] = $this->input->post('task_invoice');
            }
            $taskInfo['assignedto'] = $this->input->post('assigned_to');
            $taskInfo['priority'] = $this->input->post('priority');
            $taskInfo['project'] = $this->session->userdata('project_id');
            $taskInfo['company'] = $this->session->userdata('company');
            $taskInfo['target'] = date_conversion($this->input->post('task_target'), 'import');
            $taskInfo['estimate'] = $estTime;
            $taskInfo['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            $taskInfo['createdby'] = $this->session->userdata('user_id');
            if ($this->tasks_model->insert_task($taskInfo))
            {
                $this->session->set_flashdata('success', $this->lang->line('task_msg_created'));
            }
            redirect('tasks');
        }
        else
        {
            $this->add();
        }
    }
    function update()
    {
        // updates an item in the database using the data from Edit
        if ($this->form_validation->run('tasks') == true)
        {
            $taskStatus = $this->compareStatus($this->input->post('task_old_status'), $this->input->post('task_status'));
            if ($this->input->post('task_category') <> "") $category = $this->input->post('task_category');
            else  $category = $this->input->post('task_categorylist');
            // Estimated Time
            $estTime = 0;
            if ($this->input->post('task_estimate') != '')
            {
                $estTimeArray = explode(':', $this->input->post('task_estimate'));
                $estTime = $estTimeArray[0] * 60 + $estTimeArray[1];
            }
            // Actual Time
            $actTime = 0;
            if ($this->input->post('task_actual') != '')
            {
                $actTimeArray = explode(':', $this->input->post('task_actual'));
                $actTime = $actTimeArray[0] * 60 + $actTimeArray[1];
            }
            $taskInfo['name'] = $this->input->post('task_name');
            $taskInfo['description'] = $this->input->post('task_description');
            $taskInfo['comments'] = $this->input->post('task_comments');
            $taskInfo['status'] = $this->input->post('task_status');
            $taskInfo['type'] = $this->input->post('task_type');
            if ($this->input->post('task_invoice'))
            {
                $taskInfo['invoice'] = $this->input->post('task_invoice');
            }
            $taskInfo['estimate'] = $estTime;
            $taskInfo['actual'] = $actTime;
            $taskInfo['category'] = str_replace('_', ' ', $category);
            $taskInfo['assignedto'] = $this->input->post('assigned_to');
            $taskInfo['target'] = date_conversion($this->input->post('task_target'), 'import');
            $taskInfo['priority'] = $this->input->post('priority');
            if ($taskStatus == "Closed")
            {
                $taskInfo['closed'] = mdate("%Y-%m-%d %H:%i:%s", time());
            }
            $task_id = (int)$this->input->post('taskUnq');
            $this->tasks_model->update_task($this->session->userdata('company'), $task_id, $taskInfo);
            $this->session->set_flashdata('success', $this->lang->line('task_msg_updated'));
            redirect('tasks');
        }
        else
        {
            $this->edit($this->input->post('taskUnq'));
        }
    }
    function compareStatus($oldStatus, $newStatus)
    {
        // Check if Status Has Changed
        $oldStatusCheck = "Open"; // Initialise Status to Open
        // Check if Old Status was Closed
        if (in_array($oldStatus, $this->config->item('closed_status')))
        {
            $oldStatusCheck = "Closed";
        }
        $newStatusCheck = "Open"; // Initialise Status to Open
        // Check if New Status is Closed
        if (in_array($newStatus, $this->config->item('closed_status')))
        {
            $newStatusCheck = "Closed";
        }
        if ($oldStatus != $newStatus)
        {
            return $newStatusCheck;
        }
        else
        {
            // No Change Required
            return false;
        }
    }
    function destroy($id)
    {
        // removes an item from the database
        $task_id = (int)$id;
        if ($this->tasks_model->delete_task($this->session->userdata('company'), $task_id))
        {
            $this->session->set_flashdata('success', $this->lang->line('task_msg_deleted'));
        }
        redirect('tasks');
    }
    function filter($filter)
    {
        $filtertypes = array('category', 'priority', 'assignedto', 'status');
        $filtersplit = explode("_", $filter, 2);
        if (array_search($filtersplit[0], $filtertypes) >= 0)
        {
            $filtername = $filtersplit[0].'_filter'; // Setup filter name
            $this->session->set_userdata($filtername, str_replace('_', ' ', $filtersplit[1]));
        }
        redirect('tasks');
    }
}
/* End of file */