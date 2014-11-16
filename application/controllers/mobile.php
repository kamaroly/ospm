<?php
class Mobile extends Controller
{
    function Mobile()
    {
        parent::Controller();
    }
    function index()
    {
        $data['page_heading'] = $this->lang->line('mobile_menu');
        $this->load->view('mobile/menu',$data);
    }
}
/* End of File */