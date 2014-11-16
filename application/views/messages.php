<?php
if ($this->session->flashdata('error') <> "") echo '<p class="error">'.$this->session->flashdata('error').'</p>';
if ($this->session->flashdata('success') <> "") echo '<p class="success">'.$this->session->flashdata('success').'</p>';
$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
echo validation_errors();
/* End of File */