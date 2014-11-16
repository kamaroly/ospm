<?php
// for convenient removal of this page from the online demo without needing to
// constantly remove and add this file.
// show_404();
// This install controller is only for quick insertion of an admin user into the system.
// I strongly recommend you delete this file after you've installed Cyberience Projects.
// This controller is not needed to run the application.
class Install extends Controller
{
    function __construct()
    {
        parent::Controller();
        $this->load->library('encrypt');
        $this->load->dbutil();
    }
    // --------------------------------------------------------------------
    function index()
    {
        /*
        |   If Table People Exists then Run Upgrade
        */
        if ($this->db->table_exists('people'))
        {
            redirect('install/update_projects', 'refresh');
        }
        /*
        |   Prepare Validation Rules for Installer
        */
        $this->load->helper('form', 'url');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_fullname', $this->lang->line('user_fullname'), 'required');
        $this->form_validation->set_rules('user_email', $this->lang->line('user_email'), 'required|valid_email');
        $this->form_validation->set_rules('user_password', $this->lang->line('user_password'), 'required|matches[user_repeat_password]');
        $this->form_validation->set_rules('user_repeat_password', $this->lang->line('user_repeat_password'), 'required');
        /*
        |   Run Validation on Installer Inputs
        */
        if ($this->form_validation->run() == FALSE)
        {
            /*
            |   Validation Failed - Display Form Again
            */
            $this->load->view('authentication/install_form');
        }
        else
        {
            /*
            |   Passed Validation - Run Installation
            */
            $email = $this->input->post('user_email');
            $password = $this->input->post('user_password');
            $full_name = $this->input->post('user_fullname');
            $this->do_install($email, $password, $full_name);
        }
    }
    // --------------------------------------------------------------------
    function do_install($admin_email = '', $admin_password = '', $primary_contact = '')
    {
        /*
        |   If Email or Password is Not Set You Should Not Be Here
        */
        if (!isset($admin_password) || !isset($admin_email))
        {
            redirect('install', 'refresh');
        }
        /*
        |   If Any of the Variables are Blank You Should Not Be Here Either
        */
        if ($admin_email == '' OR $admin_password == '' OR $primary_contact == '')
        {
            redirect('install', 'refresh');
        }
        $this->load->dbforge();
        /*
        |   Create Sessions Table
        */
        $sessions_definition['session_id'] = array('type' => 'VARCHAR', 'constraint' => 40, 'default' => 0);
        $sessions_definition['ip_address'] = array('type' => 'VARCHAR', 'constraint' => 16, 'default' => 0);
        $sessions_definition['user_agent'] = array('type' => 'VARCHAR', 'constraint' => 50, 'default' => '');
        $sessions_definition['last_activity'] = array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'default' => 0);
        $sessions_definition['user_data'] = array('type' => 'TEXT', 'constraint' => 200);
        $this->dbforge->add_field($sessions_definition);
        $this->dbforge->add_key('session_id', TRUE);
        $this->dbforge->add_key('ip_address', TRUE);
        $this->dbforge->create_table('sessions', TRUE);
        /*
        |   Create Billables Table
        */
        $billables_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $billables_definition['project'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE);
        $billables_definition['invoice_id'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $billables_definition['quantity'] = array('type' => 'INT', 'constraint' => 3, 'unsigned' => TRUE);
        $billables_definition['description'] = array('type' => 'VARCHAR', 'constraint' => 500);
        $billables_definition['created'] = array('type' => 'DATETIME');
        $billables_definition['flatfee'] = array('type' => 'DECIMAL', 'constraint' => '11,2');
        $billables_definition['hourlyrate'] = array('type' => 'DECIMAL', 'constraint' => '5,2');
        $billables_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('project', 'company'));
        $this->dbforge->add_field($billables_definition);
        $this->dbforge->create_table('billables', TRUE);
        /*
        |   Create Clients Table
        */
        $clients_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $clients_definition['organisationname'] = array('type' => 'VARCHAR', 'constraint' => 200);
        $clients_definition['address'] = array('type' => 'VARCHAR', 'constraint' => 400);
        $clients_definition['orgtelephone'] = array('type' => 'VARCHAR', 'constraint' => 30);
        $clients_definition['contactname'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $clients_definition['email'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $clients_definition['website'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $clients_definition['other'] = array('type' => 'VARCHAR', 'constraint' => 500);
        $clients_definition['created'] = array('type' => 'DATETIME');
        $clients_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('company'));
        $this->dbforge->add_field($clients_definition);
        $this->dbforge->create_table('clients', TRUE);
        /*
        |   Create Invoices Table
        */
        $invoices_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $invoices_definition['project'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE);
        $invoices_definition['name'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $invoices_definition['status'] = array('type' => 'VARCHAR', 'constraint' => 30);
        $invoices_definition['created'] = array('type' => 'DATETIME');
        $invoices_definition['actiondate'] = array('type' => 'DATE');
        $invoices_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $invoices_definition['due_date'] = array('type' => 'DATE');
        $invoices_definition['paid_date'] = array('type' => 'DATE');
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('company', 'project', 'status', 'actiondate'));
        $this->dbforge->add_field($invoices_definition);
        $this->dbforge->create_table('invoices', TRUE);
        /*
        |   Create People Table
        */
        $people_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $people_definition['fullname'] = array('type' => 'VARCHAR', 'constraint' => 200);
        $people_definition['email'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $people_definition['password'] = array('type' => 'VARCHAR', 'constraint' => 200);
        $people_definition['created'] = array('type' => 'DATETIME');
        $people_definition['status'] = array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active');
        $people_definition['type'] = array('type' => 'VARCHAR', 'constraint' => 20);
        $people_definition['enddate'] = array('type' => 'DATETIME');
        $people_definition['lastproject'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE);
        $people_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key('company');
        $this->dbforge->add_field($people_definition);
        $this->dbforge->create_table('people', TRUE);
        /*
        |   Create Projects Table
        */
        $projects_definition['unq'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $projects_definition['name'] = array('type' => 'VARCHAR', 'constraint' => 200);
        $projects_definition['description'] = array('type' => 'VARCHAR', 'constraint' => 500);
        $projects_definition['status'] = array('type' => 'VARCHAR', 'constraint' => 20);
        $projects_definition['created'] = array('type' => 'DATETIME');
        $projects_definition['client'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $projects_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('company', 'status'));
        $this->dbforge->add_field($projects_definition);
        $this->dbforge->create_table('projects', TRUE);
        /*
        |   Create Tasks Table
        */
        $tasks_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $tasks_definition['company'] = array('type' => 'INT', 'unsigned' => TRUE, 'constraint' => 3);
        $tasks_definition['project'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE);
        $tasks_definition['name'] = array('type' => 'VARCHAR', 'constraint' => 200);
        $tasks_definition['type'] = array('type' => 'INT', 'constraint' => 2, 'unsigned' => TRUE);
        $tasks_definition['status'] = array('type' => 'VARCHAR', 'constraint' => 20);
        $tasks_definition['priority'] = array('type' => 'INT', 'constraint' => 2, 'unsigned' => TRUE);
        $tasks_definition['description'] = array('type' => 'VARCHAR', 'constraint' => 1000);
        $tasks_definition['comments'] = array('type' => 'VARCHAR', 'constraint' => 2000);
        $tasks_definition['category'] = array('type' => 'VARCHAR', 'constraint' => 50);
        $tasks_definition['createdby'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $tasks_definition['created'] = array('type' => 'DATETIME');
        $tasks_definition['assignedto'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $tasks_definition['closed'] = array('type' => 'DATETIME');
        $tasks_definition['target'] = array('type' => 'DATE');
        $tasks_definition['estimate'] = array('type' => 'INT', 'constraint' => 4, 'unsigned' => TRUE);
        $tasks_definition['actual'] = array('type' => 'INT', 'constraint' => 4, 'unsigned' => TRUE);
        $tasks_definition['invoice'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('company', 'assignedto', 'project', 'status'));
        $this->dbforge->add_field($tasks_definition);
        $this->dbforge->create_table('tasks', TRUE);
        /*
        |   Create Timetrack Table
        */
        $timetrack_definition['unq'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE);
        $timetrack_definition['startdate'] = array('type' => 'DATE');
        $timetrack_definition['time'] = array('type' => 'INT', 'constraint' => 4, 'unsigned' => TRUE);
        $timetrack_definition['project'] = array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE);
        $timetrack_definition['user'] = array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE);
        $timetrack_definition['item'] = array('type' => 'VARCHAR', 'constraint' => 100);
        $timetrack_definition['company'] = array('type' => 'INT', 'constraint' => 3, 'unsigned' => TRUE);
        $timetrack_definition['status'] = array('type' => 'VARCHAR', 'constraint' => 20);
        $this->dbforge->add_key('unq', TRUE);
        $this->dbforge->add_key(array('company', 'project', 'user'));
        $this->dbforge->add_field($timetrack_definition);
        $this->dbforge->create_table('timetrack', TRUE);
        /*
        |   Create Default User
        */
        $this->db->set('unq', 1);
        $this->db->set('fullname', $primary_contact);
        $this->db->set('email', $admin_email);
        $this->load->helper('encryption');
        $this->db->set('password', encryptPass($admin_password, $admin_password));
        $this->db->set('created', mdate("%Y-%m-%d %H:%i:%s", time()));
        $this->db->set('type', 'Administrator');
        $this->db->set('company', 1);
        $this->db->insert('people');
        /*
        |   Clear Any Session Variables and Run the Update Script
        */
        $this->session->sess_destroy();
        redirect('install/update_projects', 'refresh');
    }
    function update_projects()
    {
        // This code will be expanded for future releases to allow auto-upgrades of any data tables
        $this->load->dbforge();
       
        // Add Due Date and Paid Date To Invoices     
        
        //$invoices_definition['due_date'] = array('type' => 'DATE');
        //$invoices_definition['paid_date'] = array('type' => 'DATE');
        //$this->dbforge->add_column('invoices',$invoices_definition);
                
        $this->load->view('authentication/install_complete');
    }
}
?>