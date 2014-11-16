<?php
$this->load->view('mobile/header');
echo '<div id="mobile_nav">';
echo '<ul class="mobile_nav">';
    $active_section = $this->uri->segment(1);
    $navigation_list= "";
    $navigation_list[].= $this->lang->line('navigation_dashboard');
    if ($this->session->userdata('project_id') > 0)
    {
        $navigation_list[].= str_replace('</a>',' ('.$this->session->userdata('os_tasks').')</a>',$this->lang->line('navigation_tasks'));
        $navigation_list[].= str_replace('</a>',' ('.$this->session->userdata('os_invoices').')</a>',$this->lang->line('navigation_invoices'));

        $navigation_list[].=$this->lang->line('navigation_timetrack');
    }
    else
    {
        $navigation_list[] .=$this->lang->line('navigation_projects');
    }

    foreach ($navigation_list as $navigation_item):


				echo "\n".'<li>'.$navigation_item.'</li>';
    
    endforeach;
echo '</ul>';
echo '<ul>';
echo '<li>'.$this->lang->line('navigation_projects').'</li>'; 
if ($this->session->userdata('usertype') == "Administrator")
{

    echo '<li>'.$this->lang->line('navigation_clients').'</li>';
    echo '<li>'.$this->lang->line('navigation_users').'</li>';
}      
      
echo '<li>'.$this->lang->line('navigation_users_profile').'</li>';;
echo '<li>'.$this->lang->line('navigation_users_logout').'</li>';
echo '</ul>';

echo '<ul>';
echo '<li>'.$this->lang->line('navigation_report_bug').'</li>';
echo '<li>'.$this->lang->line('navigation_donate').'</li>';
echo '<li>'.$this->lang->line('navigation_credits').'</li>';
echo '</ul>';
echo '</div>';
/* End of File */