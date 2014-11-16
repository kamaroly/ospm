<?php

if ($this->session->userdata('user_id'))
{
    $current_uri = $this->uri->segment(1);

    echo "\n\n".'<div class="container" id="navigation">';
    echo '<ul>';
    $active_section = $this->uri->segment(1);
    $navigation_list= "";
    $navigation_list[].= $this->lang->line('navigation_dashboard');
    if ($this->session->userdata('project_id') > 0)
    {
        $navigation_list[].= $this->lang->line('navigation_tasks');
        $navigation_list[].=$this->lang->line('navigation_invoices');
        $navigation_list[].=$this->lang->line('navigation_timetrack');
    }
    else
    {
        $navigation_list[] .=$this->lang->line('navigation_projects');
    }

    foreach ($navigation_list as $navigation_item):
            if (preg_match("#<a href=\"".base_url().$current_uri."\"#", $navigation_item))
            {
            	
                echo "\n".preg_replace("#<a href=\"".base_url().$current_uri."\"#",
                     "<li><a class=\"active_section\" href=\"".base_url().$current_uri."\"", $navigation_item);
            	echo '</li>';
			}
            else 
			{
				echo "\n".'<li>'.$navigation_item.'</li>';
			}       
    endforeach;
    echo '</ul>';
    echo '</div>';
}
/* End of file */