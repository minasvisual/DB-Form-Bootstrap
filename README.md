# DB-Form-Bootstrap
PHP class that generate form fields and render with bootstrap tags (optionally).

<?php 

$this->load->library('db_form', array(), 'form'); 

/* Instance form (required)*/ 
// 1 mode 
$this->form->setForm('<table name>'); 
// 2 mode 
$this->form->setForm('<table name>', array('<field name>'=>'<label name>', ...) ); 

/* configuring render (optional)*/ 
// Filter fields 
$this->form->setForm('<table name>')->filter( array('<field name>', ... ) ); 

// Change field parameters | Change/Add Label text 
$this->form->field('<field name>')->label('<label text>'); 

// Change field parameters | Change type (form library codeigniter) 
$this->form->field('<field name>')->type('dropdown'); 

// Change field parameters | add options to field (for dropdown, radio, checkbox, etc) 
$this->form->field('<field name>')->options( array('< value >'=>'< label >', ...) ); 

// Change field parameters | add html attributes 
$this->form->field('<field name>')->attr( array('< attribute >'=>'< value >', ...) ); 

// Change field parameters | add value or array values for checkbox 
$this->form->field('<field name>')->value( '<field value>' ); 

// Change field parameters | set new values for class parameter to unique field 
$this->form->field('<field name>')->config( array('<class variable>'=>'<new value>') ); 

// Change field parameters | add pre and after input content 
$this->form->field('<field name>')->content( array('before'=>'< value >', 'after'=>'< value >') ); 

/* Configuring general bootstrap render output */ 
// Dynamic Change class parameters 
$this->form->setConfig( array('<config var>'=>'<new value>') ); 

// Stand alone change 
$this->form-><config var> = '<new value>'; 

/* IMPORTANT: see config vars in end of this Doc */ 

/* Fill form with result (optional)*/ 
// Get query 
$qr = $this->db->query( '<row return>' )->result_array(); 
// or Set array 
$qr = array( '<field name1>'=>'<value>', ... ); 

$this->form->fill( $qr ); 

/* Render form (required )*/ 
// For default print a array with block input | optional parameter print(true|false) 
$rtn = $this->form->render( <auto print(true|false)> ); 

echo $rtn['<field name>']; 


/* Public config vars */ 
/* 
 $fieldset = true; 
 $fieldset_name = ''; 
 $out_type = 'bootstrap'; 
 $form_class = 'form-horizontal'; 
 $form_action = ''; 
 $parentt = true; 
 $parent_tag = 'div'; 
 $parent_class = 'form-group'; 
 $label = true; 
 $label_tag = 'label'; 
 $label_class = 'col-md-3 control-label'; 
 $input_parent = true; 
 $input_parent_tag = 'div'; 
 $input_parent_class = 'col-md-6'; 
 $input_class = 'form-control'; 
 $input_check = array('checkbox'=>''); 
 $btn_submit = true; 
 $btn_submit_config = array('name'=>'btn_submit', 'class'=>'btn btn-success', 'value'=>'Gravar'); 
 $btn_apply = true; 
 $btn_apply_config = array('name'=>'btn_apply', 'class'=>'btn btn-info', 'value'=>'Aplicar'); 
 $submit_config = array('label_tag'=>'div'); 
*/ 
?> 
