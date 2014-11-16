<?php
echo '<fieldset class="filter-section">';
echo '<ul id="sub_navigation">';
echo '<li>';
if ($this->session->userdata('view_inactive_projects') == 0)
{
    echo $this->lang->line('navigation_projects_show_inactive');
}
else
{
    echo $this->lang->line('navigation_projects_show_active');
}
echo '</li>';
echo '</ul>';
echo '</fieldset>';
echo '<table>';
echo '<tr>';
$alt_text = $this->lang->line('common_filter');
$filter = '<a class="toggle-filter" href="#"><img src="'.base_url().'img/icons/lens_16x16.png'.
    '" title="'.$alt_text.'" alt="'.$alt_text.'" /></a>';
echo '<th>'.$filter.'</th>';
echo '<th>&nbsp;</th>';
echo '<th>'.$this->lang->line('project_status').'</th>';
echo '<th>'.$this->lang->line('task_outstanding').'</th>';
echo '<th>'.$this->lang->line('invoice_outstanding').'</th>';
echo '<th>'.$this->lang->line('timetrack_total').'</th>';
echo '</tr>';
/* End of file */