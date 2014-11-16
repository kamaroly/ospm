<?php
$this->load->view('header');
echo '<fieldset class="filter-section">';
echo '<ul id="sub_navigation">';
echo '<li>';
if ($this->session->userdata('view_archived_times') == '')
{
    echo $this->lang->line('navigation_timetrack_show_archived');
}
else
{
    echo $inactive = $this->lang->line('navigation_timetrack_hide_archived');
}
echo '</li>';
echo '</ul>';
echo '</fieldset>';
// LOOP THROUGH TRACKED TIME ITEMS
$total_time = 0;
if (count($timetracks) > 0)
{
    echo '<table class="view_table">';
    $alt_text = $this->lang->line('common_filter');
    $filter = '<a class="toggle-filter" href="#"><img src="'.base_url().'img/icons/lens_16x16.png" title="'.$alt_text.'" alt="'.$alt_text.'" /></a>';
    echo '<th>'.$filter.'</th>';
    echo '<th></th>';
    echo '<th>'.$this->lang->line('timetrack_user').'</th>';
    echo '<th>'.$this->lang->line('timetrack_date').'</th>';
    echo '<th>'.$this->lang->line('timetrack_hours').'</th>';
    echo '</tr>';
    $altrow = 0;
    foreach ($timetracks as $timeitem):
        if ($altrow == 1)
        {
            $altrow = 0;
        }
        else
        {
            $altrow = 1;
        }
        echo '<tr';
        if ($altrow == 1)
        {
            echo ' class="altrow"';
        }
        echo '>';
        /* START List Available Actions - Edit, Delete, etc. */
        $actions = "";
        $url = 'timetrack/edit/'.$timeitem['unq'];
        $alt_text = $this->lang->line('common_edit');
        $actions .= "\n".edit_action($url, $alt_text);
        // Set Actions for Delete
        $url = 'timetrack/destroy/'.$timeitem['unq'];
        $alt_text = $this->lang->line('common_delete');
        $popup_text = $this->lang->line('common_delete_confirmation');
        $actions .= "\n".delete_action($url, $alt_text, $popup_text);
        if ($this->session->userdata('view_archived_times') == '')
        {
            $archive_action = "\n".'<a href="'.site_url().'/timetrack/archive/'.$timeitem['unq'].'"><img src="'.base_url().'img/icons/round_down_arrow_16x16.png'.'" alt="'.$this->lang->line('timetrack_archive').
                '" title="'.$this->lang->line('timetrack_archive').'" /></a>';
        }
        else
        {
            $archive_action = "\n".'<a href="'.site_url().'/timetrack/unarchive/'.$timeitem['unq'].'"><img src="'.base_url().'img/icons/round_up_arrow_16x16.png'.'" alt="'.$this->lang->line('timetrack_unarchive').
                '" title="'.$this->lang->line('timetrack_unarchive').'" /></a>';
        }
        $actions .= $archive_action;
        /* END List Available Actions - Edit, Delete, etc. */
        echo "\n".'<td>'.$actions.'</td>';
        // Create Row with Data Available
        echo "\n".'<td>';
        if ($timeitem['item'] <> "")
        {
            echo $timeitem['item'];
        }
        else
        {
            echo $this->lang->line('timetrack_empty');
        }
        echo '</td>';
        if (isset($people[$timeitem['user']]))
        {
            echo "\n".'<td>'.$people[$timeitem['user']].'</td>';
        }
        else
        {
            echo "\n".'<td></td>';
        }
        echo "\n".'<td>'.convert_date($timeitem['startdate'], 'no', $this->session->userdata('dateformat')).'</td>';
        $total_time = $total_time + $timeitem['time'];
        echo '<td>'.convertTimeForm($timeitem['time']).'</td>';
        echo "\n".'</tr>';
    endforeach;
    // END OF LOOP
    echo '</table>';
}
else
{
    echo '<p class="notice">'.$this->lang->line('timetrack_no_results').'</p>';
}
if ($total_time > 0)
{
    echo '<p class="notice">';
    echo $this->lang->line('timetrack_total').': '.convertTime($total_time).'</p>';
    echo '<p>';
}
$this->load->view('footer');
?>