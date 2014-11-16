<?php
// Prepare Project Names In Array
foreach ($projects as $project):
    $project_name[$project['unq']] = $project['name'];
endforeach;
echo '<h3>'.$this->lang->line('invoice_main').'</h3>';
if (count($invoices) > 0)
{
    $duetypes = $this->config->item('invoice_due_status_types');
    echo '<table class="view_table">';
    echo '<tr>';
    echo '<th>'.$this->lang->line('invoice_name').'</th>';
    echo '<th>'.$this->lang->line('project_name').'</th>';
    echo '<th>'.$this->lang->line('invoice_status').'</th>';
    echo '<th>'.$this->lang->line('invoice_amount').'</th>';
    echo '<th>'.$this->lang->line('invoice_action_date').'</lth>';
    echo '<th>'.$this->lang->line('invoice_due_date').'</th>';
    echo '<th>'.$this->lang->line('invoice_paid_date').'</th>';
    echo '</tr>';
    $altrow = 0;
    foreach ($invoices as $invoice):
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
        echo '<td>'.$invoice['name'].'</td>';
        echo '<td><a href="'.base_url().'projects/activate/'.$invoice['project'].'">'.$project_name[$invoice['project']].'</a></td>';
        echo '<td>'.$invoice['status'].'</td>';
        echo '<td>'.number_format($time[$invoice['unq']], 2).'</td>';
        //echo '&pound; '.$overall_due;
        echo '<td>';
        if (in_array($invoice['status'], $duetypes))
        {
            echo convert_date($invoice['actiondate'], '', $this->session->userdata('dateformat'));
        }
        echo '</td>';
        echo '<td>'.convert_date($invoice['due_date'], '', $this->session->userdata('dateformat')).'</td>';
        echo '<td>'.convert_date($invoice['paid_date'], '', $this->session->userdata('dateformat')).'</td>';
        echo '</tr>';
    endforeach;
    echo '</table>';
}
else
{
    echo '<p class="notice">'.$this->lang->line('invoice_no_results').'</p>';
}
/* End of File */
