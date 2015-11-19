<?php
/**
 * Db_LIbrary form html generator by database ou array
 *
 * Crie forms a partir dos campos da tabela do banco ou por array associativo
 *
 * @category   Libraries
 * @package    Db_form
 * @subpackage Libraries
 * @author     Ulisses Mantovani <contato@minasvisual.com>
 * @license    http://minasvisual.com
 * @link       https://github.com/minasvisual/db_form
 */
class Db_form 
{

	public    $fields = array();  // NAME OF FIELDS IS A KEY THE VALUE IS PARAMETERS
	protected $out = array();     // OUTPUT VALUES IN ARRAY
	protected $table = array();
	protected $fill = '';         // TABLE OF DB GET NAMES
	protected $qr = array(), $CI; // QUERY AND CI INSTANCE
	protected $field_edit = '';   // FIELD INSTANCE TO EDIT VALUES OUTSIDE CLASS
	// RENDER VARS
	public $fieldset = true;  
	public $fieldset_name = 'Inserir/Editar';
 	public $out_type = 'bootstrap';
	public $form_attr = array('class'=>'form-horizontal');
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

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	public function __construct()
	{
		$this->form_action = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/*
	 * PUBLIC METHODS
	*/

	// SET FORM PARAMETERS AND FIELDS ( table query array | personal fields | query result array )
	public function create($fields, $fill=false, $table=null)
	{
		$this->table = $table;
		$this->fill = &$fill;
		$this->getFields($fields);
		
		return $this;
	}

	//SET CONFIG VARS IN BATCH
	public function setConfig($config=array())
	{
		foreach($config as $ky => $pr){
			$this->$ky = $pr; 
		}
	}

	//
	// MODIFING VARIABLE VALUES AND PARAMETERS
	//

	// SET FIELD TO EDIT
	public function field($var)
	{
		$this->field_edit = $var;
		
		return $this;
	}

	//SETTING TYPE OF FIELD
	public function type($type)
	{
		$this->fields[$this->field_edit]['type'] = $type; 
		
		return $this;
	}

	// SETTING OPTIONS TO ATRIBUTES
	public function options($options)
	{
		$this->fields[$this->field_edit]['options'] = $options;
		
		return $this;
	}

	//SET ATRIBUTES INPUT
	public function attr($attr)
	{
		$this->fields[$this->field_edit]['attr'] = $attr;
		
		return $this;
	}

	// SETTING LABEL VALUE
	public function label($lbl)
	{
		if( !is_array($lbl) ){
			$this->fields[$this->field_edit]['label'] = $lbl;
		}else{
			foreach($lbl as $k=>$v){
				$this->fields[$k]['label'] = $v;
			}
		}
		return $this;
	}

    // SET VALUE FOR UNIQUE FIELD
	public function value($vlr)
	{
		$this->fields[$this->field_edit]['value'] = $vlr;
		
		return $this;
	}

	// INSERT CONTENT BEFORE AND AFTER INPUT
	public function content( $arrContent )
	{
		if( !is_array($arrContent) ){
			$this->fields[$this->field_edit]['content']['after'] = $arrContent;
		}else{
			foreach($arrContent as $k=>$v){
				$this->fields[$this->field_edit]['content'][$k] = $v;
			}
		}
		return $this;
	}	

	// FILTER FIELDS TO SHOW
	public function filter($filter)
	{
		if( count($filter) > 0 ){
			foreach($filter as $item){
				unset($this->fields[$item]);
			}
		}
		return $this; 
	}

	// SET SORT
	public function sort($vlr)
	{
		$this->fields[$this->field_edit]['sort'] = $vlr;
		
		return $this;
	}

	//SET PERSONAL CONFIG VARS TO UNIQUE FIELDs
	public function config( $vls )
	{
		if( is_array($vls) )
		{
			foreach($vls as $k=>$v){
				$this->fields[$this->field_edit]['config'][$k] = $v;
			}
		}
		return $this;
	}

	// FILL INPUT WITH DB VALUES
	public function fill($arrQr=null)
	{
		if( !is_null($arrQr) ) $this->qr = $arrQr;
		
		if( isset($this->qr) )
		{
			foreach( $this->qr as $kfl=>$vfl ){
				if( isset($this->fields[$kfl]) )
				{
					$this->fields[$kfl]['value'] = $vfl;
				}
			}
		}
		return $this;
	}
    
	//GET FILD render
	public function getRender($print=true)
	{
		return $this->renderFields($this->field_edit, $print);
	}

    // RENDERING FORM 
	public function renderFields($field, $print=true)
	{
		//SET INPUT TYPES IN OUTPUT VAR
		if( !is_array($field) ) $field = array($field);

		$fields = $this->elements($field, $this->fields);

		//sorting fields
		$this->sortFields($fields, 'sort');

		foreach($fields as $name=>$param)
		{
			$this->out[$name] = '';
			// CONFIG RENDERING PARAMETERS
		    if( !isset($param['config']) )  
		    	$param['config'] = array();
			$config = $this->getConfig($param['config']);
            
			// SET ATRIBUTE PARAMETERS TO INPUT
			$data = array( 'name'=>$name );
			// INIT OPTIONS VAR
			if( !isset($param['options']) ) 
				$param['options'] = array();
			// INIT ATRIBUTES VAR
			if( !isset($param['attr']) ) 
				$param['attr'] = array('id'=>$name);

			// READY TO RENDER TAGS
			//IF PARENT TAG IS TRUE
			if( @$param['type'] == 'hidden')
			 	$config['parent_group_class'] .= ' hide';
			if( @$param['type'] == 'button')
			{
			 	$param['value'] = $param['label'];
			 	$param['label'] = ' ';
			}
			if( $config['parent_group'] ) 
				@$this->out[$name] .= '<'.$config['parent_group_tag'].' id="'.$name.'" class="'.$config['parent_group_class'].'">'."\r\n";
			//SET LABEL IF NOT DEFAULT SETTED
			if( !isset($param['label']) ) 
				$param['label'] = $name;
			//IF LABEL  TRUE
			if( $config['label'] ) 
				$this->out[$name] .= '<'.$config['label_tag'].' class="'.$config['label_class'].'" for="'.$name.'">'
                                        . ucfirst(@$param['label']) .'</'.$config['label_tag'].'>'."\r\n";
			//IF INPUT PARENT TRUE
			if( $config['input_parent'] ) 
				$this->out[$name] .= '<'.$config['input_parent_tag'].' class="'.$config['input_parent_class'].'">'."\r\n";			
			//IF CONTENT BEFORE EXSITS
			if( isset($param['content']['before']) ) 
				$this->out[$name] .= $param['content']['before']."\r\n";
				
			if( !isset($param['value']) ) 
				$param['value'] = '';
			
			if( !isset($param['attr']['class']) ) 
				$param['attr']['class'] = $config['input_class'];
			
			
			switch(@$param['type'])
			{
				// IF NOT DEFINED FIELD IS BE INPUT TEXT
				default :
					if( isset($param['attr']) )
                    { 
                        $data += $param['attr']; $data['value'] = $param['value']; 
                    }
                    if( !isset($data['type']) ) $data['type'] = 'text';
                
					$this->out[$name] .= $this->addField('input', $data, null );
				break;
				case 'dropdown':
                    $param['attr']['name'] = $data['name'];
                
					$this->out[$name] .= $this->addField( 'select', $param['attr'], $this->addOptions($param['options'], $param['value']) );
				break;
				case 'hidden':

                    if( isset($param['attr']) )
                    {
                         $data += $param['attr']; $data['value'] = $param['value']; 
                    }
                    if( !isset($data['type']) ) $data['type'] = 'hidden';
                
					$this->out[$name] .= $this->addField('input', $data, null );
				break;
				case 'password':
					if( isset($param['attr']) )
                    {
                        $data += $param['attr']; $data['value'] = $param['value']; 
                    }
                    if( !isset($data['type']) ) $data['type'] = 'password';
                
					$this->out[$name] .= $this->addField('input', $data, null );
				break;
				case 'checkbox':
					foreach($param['options'] as $kch=>$vch)
                    {
                        unset( $param['attr']['checked'] );
						$this->out[$name] .= '<label class="'.$param['type'].'">'."\r\n";
                        //
                            $param['attr']['name'] = $data['name'];
                            $param['attr']['value'] = $kch;
                            if( $param['value'] == $kch || @in_array($kch, $param['value']) ) $param['attr']['checked'] = 'true';
                            if( !isset($param['attr']['type']) ) $param['attr']['type'] = 'checkbox';
                        //
                        	$this->out[$name] .= $this->addField('input', $param['attr'], '')."\r\n".$vch;
						$this->out[$name] .= '</label>'."\r\n";
					}
				break;
				case 'textarea':
					if( isset($param['attr']) ) $data += $param['attr'];
					$this->out[$name] .= $this->addField( 'textarea', $data, $param['value'] );
				break;
				case 'upload':
					if( isset($param['attr']) )
                    {
                         $data += $param['attr']; $data['value'] = $param['value']; 
                    }
                    if( !isset($data['type']) ) $data['type'] = 'file';
                
					$this->out[$name] .= $this->addField('input', $data, null );
				break;
				case 'radio':
					foreach($param['options'] as $kch=>$vch)
                    {
                        unset( $param['attr']['checked'] );
						$this->out[$name] .= '<label class="'.$param['type'].'">'."\r\n";
                        //
                            $param['attr']['name'] = $data['name'];
                            $param['attr']['value'] = $kch;
                            if( $param['value'] == $kch ) $param['attr']['checked'] = 'true';
                            if( !isset($param['attr']['type']) ) $param['attr']['type'] = 'radio';
                        //
                        	$this->out[$name] .= $this->addField('input', $param['attr'], ''). $vch;
						$this->out[$name] .= '</label>'."\r\n";
					}
				break;
				case 'button':
					//SET BUTTONS
                    if( $param['attr']['class'] == $config['input_class'] )
                    	$param['attr']['class'] = 'btn btn-info';
					if( isset($param['attr']) )
                        $data += $param['attr'];
                    if( !isset($data['value']) )
                        $data['value'] = $param['value']; 
                    if( !isset($data['type']) ) 
                    	$data['type'] = 'button';
                
					$this->out[$name] .= $this->addField('input', $data, null );
				break;
				case 'submit':
					//SET BUTTONS
					if( $config['btn_submit'] ) 
                        $this->out[$name] .=  $this->addField('button', $config['btn_submit_config'], $config['btn_submit_config']['value']);
					if( $config['btn_cancel'] ) 
                        $this->out[$name] .= '  '. $this->addField('button',$config['btn_cancel_config'], $config['btn_cancel_config']['value']);
				break;
				case 'empty':
					if( isset($param['attr']) && $param['attr']['class'] != $config['input_class'] ) 
						$data += $param['attr'];
					$this->out[$name] .= $this->addField( 'div', $data, $param['value'] );
				break;
			}
			//IF CONTENT AFTER EXISTS
			if( isset($param['content']['after']) ) $this->out[$name] .= $param['content']['after']."\r\n";
			
			//IF PARENT TRUE
			if( $config['parent_group'] ) $this->out[$name] .= '</'.$config['parent_group_tag'].'>'."\r\n";
			
			//IF INPUT PARENT TRUE
			if( $config['input_parent'] ) $this->out[$name] .= '</'.$config['input_parent_tag'].'>'."\r\n";
		}
		
		$rtn = $this->elements( array_keys($fields), $this->out);

		if( !$print ){
			return $rtn;
		}else{
			foreach($rtn as $vlr)
			{
				echo $vlr;
			}
		}
	}
	// RENDERING FORM 
	public function render($field=null, $print=true)
	{
		//OPEN FORM TAG
		$this->out['form'] = '<form action="'.$this->form_action.'" method="post" accept-charset="utf-8" '.$this->forge_string($this->form_attr).' enctype="multipart/form-data">';
		//OPEN FIELDSET TAG
		if( $this->fieldset ) $this->out['fieldset'] = "\r\n<fieldset>\r\n<legend>".$this->fieldset_name."</legend>\r\n";
		
		if( is_null($field) ) 
			$field = array_keys($this->fields);
		if( is_null($field) ) 
			$field = $this->elements( $field, array_keys($this->fields) );

		$this->renderFields( $field, false);
		
		// SET FIELDSET CLOSE
		if( $this->fieldset ){
			$this->out['fieldset_close'] = "</fieldset>";
		}
		//SET FORM CLOSE
		$this->out['form_close'] = '</form>';
		
		if( !$print ){
			return $this->out;
		}else{
			foreach($this->out as $vlr){
				echo $vlr;
			}
		}
	}


	/*
	 * HELPER METHODS
	*/

	public function setHtml($tag, $attr, $content, $print=false)
	{	
		if( !is_array($tag) )
			$tag = array($tag);
		foreach ($tag as $key => $value) 
		{	
			$rtn = "\n<".$tag." ";
			if( is_array($attr) )
			{
				foreach($attr as $k => $a)
				{
					$rtn .= $k.'="'.$a.'" ';
				}
			}
			$rtn .= ">\n";
			$rtn .= $content."\n";
			$rtn .= "</".$tag.">";
		}

		if( $print )
		{
			echo $rtn;
		}else{
			return $rtn;
		}
		//fim
	}

	public function elements($items, $arr, $default = FALSE)
	{
		$return = array();
		
		if ( ! is_array($items))
		{
			$items = array($items);
		}
		
		foreach ($items as $item)
		{
			if (isset($arr[$item]))
			{
				$return[$item] = $arr[$item];
			}
			else
			{
				$return[$item] = $default;
			}
		}

		return $return;
	}
	
	// SET DROPDOWN FILL
	public function setOptions($qr, $value, $label)
	{
		$arr = array();
	    foreach($qr as $rs){
	        $arr[ $rs[$value] ] = $rs[ $label ];
	    }
	    return $arr;
	}

	/*
	 * PRIVATE METHODS
	*/

	private function getConfig($param)
	{
		$config = array('fieldset', 'fieldset_name', 'out_type', 'form_attr', 'form_action', 'parent_group', 'parent_group_tag', 
						 'parent_group_class', 'label', 'label_tag', 'label_class', 'input_parent', 'input_parent_tag', 'input_parent_class', 
	 					  'input_class', 'input_check', 'btn_submit', 'btn_submit_config', 'btn_cancel', 'btn_cancel_config', 'submit_config');
		$saida = array();
		foreach($config as $vl)
		{
			if( !isset($param[$vl]) )
				$saida[$vl] = $this->$vl;
			else
				$saida[$vl] = $param[$vl];
		}
		unset($config);
		return $saida;	
	}
	

	// ADD FIELDS IN VAR TO RENDER AS DB OR METHOD PARAMETER 
	private function getFields($fields)
	{
		//CONVERTE OBJ/JSON EM ARRAY
		if( is_object($fields) ) 
			$fields = get_object_vars($fields);
		if( is_object($this->fill) ) 
			$this->fill = get_object_vars($this->fill);
		if( is_string($fields) || is_string($this->fill) )
		{
			if( is_array(json_decode($fields, TRUE)) ) 
				$fields = json_decode($fields, TRUE);
			if( is_array(json_decode($this->fill, TRUE)) ) 
				$this->fill = json_decode($this->fill, TRUE);
		}
		// INICIA CONVERSÃO
		if( count($fields) == 0 )
		{
			if( $this->fill !== false && count($this->fill)> 0 )
			{
				$this->fields = $this->elements(array_keys($this->fill),  array() );
				
				$this->fill();
			}
		}
		else
		{
			$i=0;
			foreach($fields as $fdk=>$fdv)
			{
				if( !is_numeric($fdk) )
				{
					$this->fields[$fdk] = array( 'label'=>$fdv,  'sort'=>$i);
				}
				else
				{
					$this->fields[$fdv] = array( 'sort'=>$i );
				}
				$i++;
			}
			if( !empty($this->fill) && !isset($this->fill['query']) && is_array($this->fill) )
			{
				$this->qr = $this->fill;
				$this->fill();
			}
			else if( !empty($this->fill) && isset($this->fill['query']) && is_array($this->fill) )
			{
				$this->qr = $this->fill['query'];
				$this->fill();
			}
		}
		$this->fields['submit'] = array('type'=>'submit', 'config'=>$this->submit_config, 'label'=>'', 'sort'=>count($this->fields) );
		
		
	}

	// FORGE ARRAY TO HTML ATTRBUTES
	protected function forge_string($arr)
	{
		$rtn = '';
		foreach($arr as $k=>$v){
			$rtn .= $k.'="'.$v.'" '; 
		}
		return $rtn;
	}

	// SET DROPDOWN FILL
	private function setSelected($padrao, $valor)
	{
		if( $padrao == $valor ){
			return true;
		}	
	}
	
	//SORT FIELDS
    private function sortFields(&$array, $subfield)
	{
	    $sortarray = array();
	    foreach ($array as $key => $row)
	    {
	        $sortarray[$key] = $row[$subfield];
	    }

	    array_multisort($sortarray, SORT_ASC, $array);
	}


    private function addField($tag, $attr, $content, $unique=false)
    {
        $rtn = "\r\n<".$tag." ";
        if(is_array($attr)){
            foreach($attr as $k => $a){
                $rtn .= $k.'="'.$a.'" ';
            }
        }
        
        $rtn .= ">";
        $rtn .= $content."";
        if(!$unique) $rtn .= "</".$tag.">";

        return $rtn;
        //fim
    }

     // FILTER ARRAY TO VALUE => LABEL
    private function addOptions($param, $selected)
    {
        $rtn ='';
        foreach($param as $k => $v)
        {
            $rtn .= "\r\n<option value=\"".$k."\"";
            if( $k == $selected ) $rtn .= ' selected="selected"';
            $rtn .= ">".$v."</option>";
        }
        return $rtn;
    }
	//END CLASS


}
   
?>