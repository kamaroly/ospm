<?php
### Display Timetrack
//echo '<div id="timedisplay">';
echo "\n".'<span class="timetracking">';
if ($this->session->userdata('timestart') == TRUE)
{
echo "\n" . '<script type="text/javascript">';
echo "\n" . 'var dateLocal = new Date();';
echo "\n" . 'var dateServer = new Date("' . date("F d, Y H:i:s") . '");' . "\n";
echo 'var dateOffset = dateServer - dateLocal;';
echo "\n" . '</script>';
echo "\n" . '<script type="text/javascript" src="' .base_url() .
    'js/timetrack.js"></script>';
echo "\n" . '<script type="text/javascript">';
echo "\n" . 'setcountup(' . $this->session->userdata('timestart') . ')';
echo "\n" . '</script>';
echo '<a href="' . site_url() . '/timetrack/stop_timer"><img alt="' . $this->lang->line('text_timestop') . '" title="' . $this->lang->line('text_timestop') .'" src="' . base_url() . 'img/icons/control_stop.png" /></a>';
}
else
{
    echo '00:00:00';
echo '<a href="' . site_url() . '/timetrack/start_timer"><img alt="' . $this->lang->line('text_timestart') . '" title="' . $this->lang->line('text_timestart') .'" src="' . base_url() . 'img/icons/control_play.png" /></a>';
}
echo '</span>';
//echo "</div>";

/* End of file */
