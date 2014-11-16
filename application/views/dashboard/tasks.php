<?php
// Prepare Project Names In Array
foreach($projects as $project):
$project_name[$project['unq']]=$project['name'];
endforeach;
echo '<h3>'.$this->lang->line('task_view').'</h3>';
$total_time_left = 0;
echo '<table class="view_table">';
echo '<tr>';
$alt_text = $this->lang->line('common_filter');
//echo '<li class="li_50"></li>';
echo '<th>&nbsp;</th>';
echo '<th>'.$this->lang->line('task_name').'</th>';
echo '<th>'.$this->lang->line('project_name').'</th>';
echo '<th>'.$this->lang->line('task_assigned_to').'</th>';
echo '<th>'.$this->lang->line('task_status').'</th>';
echo '<th>'.$this->lang->line('task_estimated_time').'</th>';
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
        // Show Priority Indicator
        $priority = $task['priority'];
        $task_cols = $this->config->item('task_priority_cols');
        
        echo '<tr';
        if($altrow==1) 
        {
            echo ' class="altrow"';
        }
        echo '>';
        // show Category Name
        $category = $task['category'];
        echo '<td class="priority" style="background-color:'.$task_cols[$priority].';">&nbsp;</td>';
        echo '<td>'.$task['name'].' ('.$task['unq'].')</td>';            
	        echo '<td><a href="'.base_url().'projects/activate/'.$task['project'].'">'.$project_name[$task['project']].'</a></td>';
        // Task Type
        $t_types = $this->config->item('task_type_options');
  //      echo '<li class="li_50">'.$t_types[$task  ['type']].'</li>';
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
        //echo '<li class="li_150">'.$category.'</li>';
        // Remaining Time Left on Task Estimated
        $total_time_left = $total_time_left + $task['estimate'];
        echo '<td>';
        if ($task['estimate'] > 0)
        {
            convertTime($task['estimate']);
        }
        echo '</td>';
        $data['info'] = $task;
        echo '</tr>';
    endforeach;
echo '</table>';
/* End of File */