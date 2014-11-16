<?php
$config = array('login'=>array(
                                    array(  'field' => 'Email',
                                            'label' => 'lang:Email',
                                            'rules' => 'required|valid_email|max_length[50]|xss_clean'),
                                            
                                    array(  'field' => 'Password',
                                            'label' => 'lang:Password',
                                            'rules' => 'required|min_length[6]|max_length[15]|xss_clean'),				
										),
                 'tasks' => array(
                                    array(  'field' => 'task_name',
                                            'label' => 'lang:task_name',
                                            'rules' => 'required|max_length[100]|xss_clean'),
                                            
                                    array(  'field' => 'category',
                                            'label' => 'lang:task_new_categories',
                                            'rules' => 'max_length[50]|xss_clean'),
                                            
                                    array(  'field' => 'task_categorylist',
                                            'label' => 'lang:task_existing_categories',
                                            'rules' => 'max_length[50]|xss_clean'),
                                            
                                    array(  'field' => 'privacy',
                                            'label' => 'lang:privacy',
                                            'rules' => 'xss_clean'),
                                            
                                    array(  'field' => 'task_invoice',
                                            'label' => 'lang:task_invoice',
                                            'rules' => 'xss_clean'),                                            
                                    array(  'field' => 'assigned_to',
                                            'label' => 'lang:assignedto',
                                            'rules' => 'integer|xss_clean'),
                                            
                                    array(  'field' => 'priority',
                                            'label' => 'lang:priority',
                                            'rules' => 'required|integer|xss_clean'),
                                            
                                    array(  'field' => 'task_target',
                                            'label' => 'lang:task_target',
                                            'rules' => 'xss_clean'),
                                    array(
                                            'field' => 'task_estimate',
                                            'label' => 'lang:task_estimate',
                                            'rules' => 'required|max_length[5]|xss_clean|format[time]'
                                         ),  
                                    array(
                                            'field' => 'task_actual',
                                            'label' => 'lang:task_actual',
                                            'rules' => 'max_length[5]|xss_clean|format[time]'
                                         ),                                           
                                    array(
                                            'field' => 'task_description',
                                            'label' => 'lang:task_description',
                                            'rules' => 'max_length[2000]|xss_clean'),
                                    array(
                                            'field' => 'task_type',
                                            'label' => 'lang:task_type',
                                            'rules' => 'max_length[50]|xss_clean'),
                                    array(
                                            'field' => 'task_created',
                                            'label' => 'lang:task_created',
                                            'rules' => 'max_length[50]|xss_clean'),
                                    array(
                                            'field' => 'task_comments',
                                            'label' => 'lang:task_comments',
                                            'rules' => 'max_length[500]|xss_clean'),
                                    array(
                                            'field' => 'task_status',
                                            'label' => 'lang:task_status',
                                            'rules' => 'max_length[50]|xss_clean'),
                                            ),
                 'invoices' => array(
                                    array(
                                            'field' => 'invoice_name',
                                            'label' => 'lang:invoice_name',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'invoice_status',
                                            'label' => 'lang:invoice_status',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'invoice_action_date',
                                            'label' => 'lang:invoice_action_date',
                                            'rules' => 'xss_clean'
                                         ),
                                ),
                'work_item' => array(
                                    array(
                                            'field' => 'invoice_work_item_quantity',
                                            'label' => 'lang:invoice_work_item_quantity',
                                            'rules' => 'required|is_natural_no_zero|xss_clean'
                                         ),
                                    array(
                                            'field' => 'invoice_work_item_description',
                                            'label' => 'lang:invoice_work_item_description',
                                            'rules' => 'required|max_length[500]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'invoice_work_item_flat_fee',
                                            'label' => 'lang:invoice_work_item_flat_fee',
                                            'rules' => 'callback_decimal_check|max_length[5]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'invoice_work_item_rate',
                                            'label' => 'lang:invoice_work_item_rate',
                                            'rules' => 'callback_decimal_check|xss_clean'
                                         ),

                                ),
                 'document' => array(
                                    array(
                                            'field' => 'document_name',
                                            'label' => 'lang:document_name',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'document_description',
                                            'label' => 'lang:document_description',
                                            'rules' => 'max_length[300]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'document_category',
                                            'label' => 'lang:document_category',
                                            'rules' => 'max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'document_version',
                                            'label' => 'lang:document_version',
                                            'rules' => 'max_length[5]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'document_upload',
                                            'label' => 'lang:document_upload',
                                            'rules' => 'max_length[500]|xss_clean'
                                         ),
                                ),
                 'timetrack' => array(
                                    array(
                                            'field' => 'timetrack_name',
                                            'label' => 'lang:timetrack_name',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_category',
                                            'label' => 'lang:timetrack_category',
                                            'rules' => 'max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_categorylist',
                                            'label' => 'lang:timetrack_existing_categories',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_user',
                                            'label' => 'lang:timetrack_user',
                                            'rules' => 'is_natural_no_zero|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_project',
                                            'label' => 'lang:timetrack_project',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_invoicetrack',
                                            'label' => 'lang:timetrack_invoice_track',
                                            'rules' => 'xss_clean'
                                         ),
                                ),
                 'timetrack_existing' => array(
                                    array(
                                            'field' => 'timetrack_name',
                                            'label' => 'lang:timetrack_name',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_category',
                                            'label' => 'lang:timetrack_category',
                                            'rules' => 'max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_categorylist',
                                            'label' => 'lang:timetrack_existing_categories',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_start_date',
                                            'label' => 'lang:timetrack_start_date',
                                            'rules' => 'required|max_length[11]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_time',
                                            'label' => 'lang:timetrack_hours',
                                            'rules' => 'required|max_length[5]|xss_clean|format[time]'
                                         ),
                                    array(
                                            'field' => 'timetrack_user',
                                            'label' => 'lang:timetrack_user',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_project',
                                            'label' => 'lang:timetrack_project',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'timetrack_invoicetrack',
                                            'label' => 'lang:timetrack_invoice_track',
                                            'rules' => 'xss_clean'
                                         ),
                                ),
                 'users' => array(
                                    array(
                                            'field' => 'user_fullname',
                                            'label' => 'lang:user_fullname',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'user_email',
                                            'label' => 'lang:user_email',
                                            'rules' => 'required|max_length[100]|valid_email|xss_clean|unique[people.email]'
                                         ),
                                    array(
                                            'field' => 'user_type',
                                            'label' => 'lang:user_type',
                                            'rules' => 'required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'user_status',
                                            'label' => 'lang:user_status',
                                            'rules' => 'required|xss_clean'
                                         ),
                                ),
                 'user_profile' => array(
                                    array(
                                            'field' => 'user_fullname',
                                            'label' => 'lang:user_fullname',
                                            'rules' => 'required|max_length[50]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'user_email',
                                            'label' => 'lang:user_email',
                                            'rules' => 'required|max_length[100]|valid_email|xss_clean|unique[people.email]'
                                         ),
                                ),
                
				
				
				 'clients' => array(
                                    array(
                                            'field' => 'client_organisation',
                                            'label' => 'lang:client_organisation',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'client_name',
                                            'label' => 'lang:client_name',
                                            'rules' => 'required|max_length[100]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'client_email',
                                            'label' => 'lang:client_email',
                                            'rules' => 'required|max_length[100]|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'client_telephone',
                                            'label' => 'lang:client_telephone',
                                            'rules' => 'xss_clean|max_length[15]'
                                         ),
                                    array(
                                            'field' => 'client_website',
                                            'label' => 'lang:client_website',
                                            'rules' => 'xss_clean|max_length[120]'
                                         ),
                                    array(
                                            'field' => 'client_address',
                                            'label' => 'lang:client_address',
                                            'rules' => 'xss_clean'
                                         ),
                                    array(
                                            'field' => 'client_other',
                                            'label' => 'lang:client_other',
                                            'rules' => 'xss_clean'
                                         ),
                                ),
                                
                                
                                
                 'projects' => array(
                                        array(
                                            'field' => 'project_name',
                                            'label' => 'lang:project_name',
                                            'rules' => 'required|max_length[100]|xss_clean',
                                            ),
                                        array(
                                            'field' => 'project_description',
                                            'label' => 'lang:form_label_project_description',
                                            'rules' => 'max_length[500]|xss_clean',
                                            ),
                                        array(
                                            'field' => 'project_clientlist',
                                            'label' => 'lang:project_client',
                                            'rules' => 'xss_clean',
                                            ),
                                        array(
                                            'field' => 'project_status',
                                            'label' => 'lang:project_status',
                                            'rules' => 'required|xss_clean',
                                            )
                                    )
               );
/* End of file */