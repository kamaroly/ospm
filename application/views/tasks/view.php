<?php
$this->load->view('header');
/*
|   People / Assigned To Filter
*/
$people['viewall'] = $this->lang->line('task_show_all');
$js_assignedto = 'onchange="document.location = \''.site_url().'/tasks/filter/assignedto_\'+this.value;"';
$filter_assignedto = form_dropdown('assigned_to', $people, $this->session->userdata('assignedto_filter'), $js_assignedto);
/*
|   Category Filter
*/
$categories['viewall'] = $this->lang->line('task_show_all');
$js_category = 'onchange="document.location = \''.site_url().'/tasks/filter/category_\'+this.value;"';
$filter_category = form_dropdown('task_categorylist', $categories, $this->session->userdata('category_filter'), $js_category);
/*
|   Priority Filter
*/
$priorities = $this->config->item('task_priority_options');
$priorities['viewall'] = $this->lang->line('task_show_all');
$js_priority = 'onchange="document.location = \''.site_url().'/tasks/filter/priority_\'+this.value;"';
$filter_priority = form_dropdown('priority', $priorities, $this->session->userdata('priority_filter'), $js_priority);
/*
|   Status Filters
*/ 
$status_filter = arrange_dropdown($this->config->item('task_status_options'));
$status_filter['viewactive'] = $this->lang->line('task_show_active');
$status_filter['viewall'] = $this->lang->line('task_show_all');
$js_status = 'onchange="document.location = \''.site_url().'/tasks/filter/status_\'+this.value;"';
$filter_status = form_dropdown('status', $status_filter, $this->session->userdata('status_filter'), $js_status);
/*
|   Display Filters
*/ 
echo '<fieldset class="filter-section">';
echo '<p>';
echo form_label($this->lang->line('task_priority').': ', '');
echo $filter_priority;
echo form_label($this->lang->line('task_assigned_to').': ', '');
echo $filter_assignedto;
echo form_label($this->lang->line('task_category').': ', '');
echo $filter_category;
echo form_label($this->lang->line('task_status').': ', '');
echo $filter_status;
echo '</p>';
echo '</fieldset>';
/*
|   Display Task List Headers
*/

/*
|   Display Task List
*/
if (count($tasks) > 0)
{
$total_time_left = 0;
echo '<table class="view_table">';
echo '<tr>';
$alt_text = $this->lang->line('common_filter');
$filter = '<a class="toggle-filter" href="#"><img src="'.base_url().'img/icons/lens_16x16.png" title="'.$alt_text.'" alt="'.$alt_text.'" /></a>';
echo '<th>'.$filter.'</th>';
echo '<th></th>';
//echo '<li class="li_30">'.$this->lang->line('task_id').'</li>';
//echo '<li class="li_50">'.$this->lang->line('task_type').'</li>';
echo '<th></th>';
echo '<th>'.$this->lang->line('task_assigned_to').'</th>';
echo '<th>'.$this->lang->line('task_status').'</th>';
echo '<th>'.$this->lang->line('task_category').'</th>';
echo '<th>'.$this->lang->line('task_estimated_time').'</th>';
echo '<th>'.$this->lang->line('task_actual_time').'</th>';
echo '</tr>';
$altrow=0;
    foreach ($tasks as $task):
        if($altrow==1)
        {
            $altrow=0;
        }
        else 
        {
            $altrow=1;   
        }
        echo '<tr';
        if($altrow==1) 
        {
            echo ' class="altrow"';
        }
        echo '>';
        // Show Priority Indicator
        $priority = $task['priority'];
        $task_cols = $this->config->item('task_priority_cols');
        // show Category Name
        $category = $task['category'];
        if (!in_array($task['status'], $this->config->item('closed_status')))
        {
            //echo remainingTime($task['target']);
        }
        // START List Available Actions - Edit, Delete, etc.
        $actions = "";
        $url = 'tasks/edit/'.$task['unq'];
        $alt_text = $this->lang->line('common_edit');
        $actions .= "\n".edit_action($url, $alt_text);
        // Set Actions for Delete
        $url = 'tasks/destroy/'.$task['unq'];
        $alt_text = $this->lang->line('common_delete');
        $popup_text = $this->lang->line('common_delete_confirmation');
        $actions .= "\n".delete_action($url, $alt_text, $popup_text);
        // END List Available Actions - Edit, Delete, etc.
        echo '<td>'.$actions.'</td>';
        echo '<td class="priority" style="background-color:'.$task_cols[$priority].';">&nbsp;</td>';
        echo '<td>'.$task['name'].' ('.$task['unq'].')</td>';
        //echo '<li class="li_30">'.$task['unq'].'</li>';
        // Task Type
        $t_types = $this->config->item('task_type_options');
  //      echo '<li class="li_50">'.$t_types[$task['type']].'</li>';
        // Assigned To
        if (isset($people[$task['assignedto']]))
        {
            echo '<td>'.$people[$task['assignedto']].'</td>';
        }
        else
        {
            echo '<td>N/A</td>';
        }
        echo '<td>'.$task['status'].'</td>';
        echo '<td>'.$category.'</td>';
        // Remaining Time Left on Task Estimated
        $total_time_left = $total_time_left + $task['estimate'];
        echo '<td>';
        if ($task['estimate'] > 0)
        {
            convertTime($task['estimate']);
        }
        echo '</td>';
        echo '<td>';
        if ($task['actual'] > 0)
        {
            echo convertTime($task['actual']);
        }
        echo '</td>';
        $data['info'] = $task;
        echo '</tr>';
    endforeach;
echo '</table>';
// END OF LOOP
if ($total_time_left > 0)
{
    echo '<p class="notice">';
    echo convertTime($total_time_left).' '.$this->lang->line('task_total_time_left');
    echo '</p>';
}
}
else
{
    echo '<p class="notice">'.$this->lang->line('task_no_results').'</p>';
}

// Display Footer
$this->load->view('footer');
/* End of file */
