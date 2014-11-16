<?php
$this->load->view('header');
if (count($users) > 0)
{
    echo '<table>';
    echo '<tr>';
    echo '<th></th>';
    echo '<th>'.$this->lang->line('user_fullname').'</th>';
    echo '<th>'.$this->lang->line('user_email').'</th>';
    echo '<th>'.$this->lang->line('user_created').'</th>';
    echo '<th>'.$this->lang->line('user_type').'</th>';
    echo '<th>'.$this->lang->line('user_status').'</th>';
    echo '</tr>';
    foreach ($users as $user):
        // Create Row with Data Available
        echo '<tr>';

        /* START List Available Actions - Edit, Delete, etc. */
        $actions = "";
        $url = 'users/edit/'.$user['unq'];
        $alt_text = $this->lang->line('common_edit');
        $actions .= "\n".edit_action($url, $alt_text);
        // Set Actions for Delete
        if ($user['status'] == 'Inactive')
        {
            $url = 'users/delete/'.$user['unq'];
            $alt_text = $this->lang->line('common_delete');
            $popup_text = $this->lang->line('common_delete_confirmation');
            $actions .= "\n".delete_action($url, $alt_text, $popup_text);
        }
        /* END List Available Actions - Edit, Delete, etc. */
        echo '<td>'.$actions.'</td>';
        echo '<td>'.$user['fullname'].'</td>';
        echo '<td>'.safe_mailto($user['email'], $user['email']).'</td>';
        echo '<td>'.convert_date($user['created'], '', $this->session->userdata('dateformat')).
            '</td>';
        echo '<td>'.$user['type'].'</td>';
        echo '<td>'.$user['status'].'</td>';
        echo '</tr>';
    endforeach;
    echo '</table>';
}
$this->load->view('footer');
/* End of file */