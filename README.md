# DB-Form-Bootstrap
PHP class that generate form fields and render with bootstrap tags (optionally).

Basic use
<pre>
/* Include and Instance form (required)*/ 
include "Db_form2.php";
$form = new Db_form;
// array/object "field name" => "Label text"
$form->create( array('name'=>'Name', 'email'=>'Email') ); 
$form->render();
</pre>

Configuring field render (optional)
<pre>
//create form
$form->create( array('name'=>'Name', 'email'=>'Email') ); 

// Change field parameters | Change/Add Label text 
$form->field('name')->label('Username'); 

// Change field parameters | Change type 
// (text(default)|hidden|password|upload|dropdown|checkbox|radio|button|textarea|empty) 
$form->field('name')->type('dropdown'); 

// Change field parameters | add options to field (for dropdown, radio, checkbox, etc) (Array/Object)
$form->field('name')->type('dropdown')->options( array( 1=>'Robert', 2=>'susan', ...) ); 

// Change field parameters | add html attributes 
$>form->field('name')->attr( array('id'=>'names', ...) ); 

// Change field parameters | add value or array values for checkbox
$form->field('name')->value( 1 ); 

// Change field parameters | set new values for class parameter to unique field 
/* IMPORTANT: see config vars in end of this Doc */ 
$form->field('name')->config( array('parent_class'=>'col-md-12') ); 

// Change field parameters | add before and after input content 
$form->field('name')->content( array('before'=>'i will appear above input', 'after'=>'i will appear below input') ); 
</pre>

Configuring global bootstrap render output vars
/* IMPORTANT: see config vars in end of this Doc */ 
<pre>
// Dynamic Change class parameters 
$form->setConfig( array('fieldset'=>false) ); 

// Stand alone change 
$form->fieldset = false; 
</pre>

Fill form with result (optional)
<pre>
// Get query array/object (Codeigniter example)
$qr = $this->db->query( 'SQL' )->result_array(); 
// or Set array 
$qr = array( 'name'=>'bob', 'email'=>'...' ); 

// Fill all fields
$form->fill( $qr ); 
</pre>

Other render forms
<pre>
// For default print a array with block input | optional parameter print(true|false) 
$rtn = $form->render( <auto print(true|false)> ); 

echo $rtn['<field name>']; 
</pre>

Public config vars
<pre>
 public $fieldset = true;  
	public $fieldset_name = 'Inserir/Editar';
 	public $out_type = 'bootstrap';
	public $form_class = 'form-horizontal';
	public $form_action = '';
	public $parent_group = true;
	public $parent_group_tag = 'div';
	public $parent_group_class = 'form-group';
	public $label = true;
	public $label_tag = 'label';
	public $label_class = 'col-md-3 control-label';
	public $input_parent = true;
	public $input_parent_tag = 'div';
	public $input_parent_class = 'col-md-6';
	public $input_class = 'form-control';
	public $input_check = array('checkbox'=>'');
	public $btn_submit = true;
	public $btn_submit_config = array('type'=>'submit', 'name'=>'btn_submit', 'class'=>'btn btn-success', 'value'=>'Save');
	public $btn_cancel = true;
	public $btn_cancel_config = array('type'=>'button', 'name'=>'btn_cancel', 'class'=>'btn btn-danger', 'value'=>'Cancel', 'onclick'=>'javascript:history.back(-1);');
	public $submit_config = array('label_tag'=>'div');

</pre>
