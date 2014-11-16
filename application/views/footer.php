<?php
echo '</div>';
echo '</div>'; // End of Main Content
echo '</div>'; // End of Wrapper
echo '</div>';
echo '<div class="container" id="footer">';
echo "\n".'<div class="span-8 projects_branding">';
echo '<img alt="" src="'.base_url().'img/layout/bottom_logo.png'.'" />';
    
    echo $this->lang->line('@projects_version');
echo '</div>';
echo "\n".'<div class="span-8">';

echo '&nbsp;';
echo '</div>';

echo "\n".'<div class="last span-8">';
echo '</div>';
echo '</div>'; // End of Footer
echo '</body>';
echo '</html>';
// debug information
//echo print_r($this->session);
//echo print_r($this->post);
/* End of file */
