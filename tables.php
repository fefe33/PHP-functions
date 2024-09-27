<?php
//function to create an HTML table
class Table {
	protected $table = "<table %%%>###</table>";
	protected $thead = '<thead %H%>###</thead>';
 	protected $tbody = '<tbody %B%>###</tbody>';
 	protected $tr = '<tr %R%>###</tr>'; 
 	protected $td = '<td %C%>###</td>';
	protected $HTML;
	protected $valid_elements = ['table'=>'%%%', 'head'=>'%H%','body'=>'%B%', 'rows'=>'%R%','cells'=>'%C%'];
	function __construct($headers, $rows) {//pass args as index arrays
		
		//
		// ...sanitize the input for HTML...
		//


		$len = count($headers);
		foreach($headers as $i) {
			if (!isset($h)) {
				$h = "<th>$i</th>";
			} else {
				$h .= "<th>$i</th>";
			}

		}
		//if no header is provided return the error enclosed in <p> tags
		if (!isset($h)) {
			return "<p> FAILED TO GENERATE TABLE. NO TABLE HEADER PROVIDED.</p>";
		}
		//append headers to thead
		$this->thead = str_replace('###', str_replace('###', $h, $this->tr),$this->thead);
		$r = '';
		for ($i=0;$i<count($rows);$i++) {
			//array to hold cells
			//iterate through all the rows (provided in the indexed array). saving each as a string containing a set of HTML <td> cells
			$ttd = $this->td;
			//try to write a single rp
			try {
				$j = 0;
				foreach($rows[$i] as $rw) {
					//replace the hashes in the cell with the content 
			
					//
					if (!$j) {
						$ttd = str_replace('###',$rw,$ttd);
					} else {
						$ttd .= str_replace('###',$rw,$this->td);
					}
					$j++;
				}
				$r.= str_replace('###', $ttd,$this->tr);
			} catch (Exception $e) {
				$ttd = "<td colspan=$len style='color:red;'>ERROR GENERATING ROW: $e</td>";
				$r.= str_replace('###', $ttd,$this->tr);
			}	
		}
		//append the resulting string of html to the table body then return the two appended to the table as HTML
		$this->tbody = str_replace('###',$r,$this->tbody);
		$this->HTML = str_replace('###', $this->thead.$this->tbody, $this->table);
		return $this;
	}
	//style it (with inline CSS) -- . i need to fix this. 
	public function style($element, $icss) {
		if (!array_key_exists($element, $this->valid_elements)) {
			return false;	//return false if elements isnt valid;
		}
		$this->HTML = str_replace($this->valid_elements[$element], 'style="'.$icss.'"', $this->HTML);
	}

	//return the HTML for the document
	public function write() {
		//return $this->HTML;
		//remove all control characters
		if (str_contains($this->HTML, '%%%')) { 	
			$this->HTML = str_replace('%%%','',$this->HTML);
		}
		if (str_contains($this->HTML, '%H%')) {
			$this->HTML = str_replace('%H%','',$this->HTML);
		}
		if (str_contains($this->HTML, '%B%')){
			$this->HTML = str_replace('%B%','',$this->HTML);
		}
		if (str_contains($this->HTML, '%R%')) {
			$this->HTML = str_replace('%R%','',$this->HTML);
		}
		if (str_contains($this->HTML, '%C%')) {
			$this->HTML = str_replace('%C%','',$this->HTML);
		}
		return $this->HTML;
	}
}





class Form {	
	///html
	private $input = '<input ###>';
	private $button = '<button %%%>###</button>';
	private $fset = '<fieldset id=\'%%%\'>###</fieldset>'; // fset = fieldset
	private $legd = '<legend %%%>###</legend>';		//legd = legend
	private $label = '<label for=\'%%%\'>###</label>';
	private $form = '<form %%%>###</form>';
	
	//ids
	private $id_iterator=0; //this is the iterator for the ids
	private $form_id; //this is the main id of the form
	
	//controls 
	private $has_submit = 0; // this determines whether or the instance of the form already has a submit button


	//iterate
	private function get_id() {
		$this->id_iterator++;
		return $this->form_id.'-'.$this->id_iterator;
	} 


	protected $inputs = array(); //this holds html strings for later concatenation
	protected function stripQuotes($v) {
		$v = str_replace('"', '', $v);
		return str_replace('\'','',$v);
	}
	private function escHTML($html) {
		$html = str_replace('\'', '&#39;', $html);
		$html = str_replace('"', '&quot;', $html);
		$html = str_replace('&', '&amp;', $html);
		$html = str_replace('<', '&lt;', $html);
		return str_replace('>', '&gt;', $html);
	}
	
	//TODO: add something to protect from adding duplicate attributes
	private function assoc_to_string($arr) {
		$s = '';
		foreach($arr as $k=>$v) {
			$v = str_replace("'", '', $v);
			if ($v == 'css') {
				$v = 'style';
			}
			$s.="$k=".$this->stripQuotes($v)." ";
		}
		return $s;
	}
	
	//the folliwing function write to the $inputs array
	//ids will be generated dynamically based on the when this function is called
	public function text_input($name, $type, $attributes) {
		$id = $this->get_id(); 
		$l;
		$valid = ['text', 'password', 'search', 'tel', 'url','email'];
		if (!in_array($type, $valid)) {
			return false;
		}
		$attributes['type'] = $type;
		$pair = array('label'=>null, 'input'=>null);
		//check if there is a label provided
		if (array_key_exists('label',$attributes)) {
			//escape it
			$l = $this->escHTML($attributes['label']);
			//write it as the text in the label
			$l = str_replace('###', $l, $this->label);
			$pair['label'] = str_replace('%%%', $id, $l); // make it for the current $id
			//unset the label attribute
			unset($attributes['label']);
		}
		//remove apostrophes from id
		$id = str_replace('\'','',$id);
		$pair['input'] = str_replace('###', $this->assoc_to_string($attributes)."id='$id' name='$name'", $this->input);
		//return $pair['label'].$pair['input'];
		$this->inputs[] = $pair['label'].$pair['input'];
	}
	//provide a set of label/value pairs for which you wish to include for the set of radio buttons (the name of the element is passed as its own argument). provide an int for a set of radios with no labels
	public function fieldset($name, $legend, $selection) {
		$id = $this->get_id();
		$s = '';
		$n = 0;
		if (!is_array($selection) || !is_string($id)||!is_string($name)) {
			return false;
		}

		//handle the legend
		if (!$legend) {
			$has_legend = false;
		} else {
			$has_legend =true;
			$legend = str_replace('###', $legend, $this->legd);
			$legend = str_replace('%%%', "id='$id-legend'", $legend);
		}
		try {
			foreach ($selection as $this_name => $this_item) {
				$N = (string)$n;
				$l = str_replace('###', $this_name,$this->label);
				$l = str_replace('%%%', "$id-$name-$N", $l);
				$s.= $l.str_replace('###', "type='radio' name='$name' id='$id-$name-$N' value='".$this->stripQuotes($this_item)."'",$this->input);
				$n++;
			}
		} catch (Exception $e){
				return "parse error: ".$e;
		}	
		$f = str_replace('%%%',$id, $this->fset);
		if (!$has_legend) {
			$o= str_replace('###', $s, $f);
		} else {
			$o= str_replace('###', $legend.$s, $f);
		}
		$this->inputs[] = $o;
	}
	//write a range input
	public function range_input($name, $label, $min, $max, $step) {	
		$id = $this->get_id();
		$id = $this->stripQuotes($id);
		$label = $this->stripQuotes($label);
		$l;
		if ($label) {
			$l = str_replace('###', $label, $this->label);
			$l = str_replace('%%%', $id, $l);
		}
		$name = $this->stripQuotes($name);
		if (!(is_int($min) && is_int($max) && is_int($step))) {
			return false; //returns false if min, max, and step arent integers
		}
		$s = "type='range' id='$id' name='$name' min=$min max=$max step=$step";
		if (!$l) {
			$this->inputs[] = str_replace('###',$s,$this->input);
		} else {	 
			$this->inputs[] = $l.str_replace('###',$s,$this->input);
		}	
	}
	//write a numeric input
	public function numeric_input($name,$label, $min, $max, $step) {
		$id = $this->get_id(); 
		$id = $this->stripQuotes($id);
		$name = $this->stripQuotes($name);
		$l;
		//if there is a label, make it
		if ($label) {
			$l = str_replace('###',$label,$this->label);
			$l = str_replace('%%%',$id,$l);
		}

		$s = "id='$id' name='$name' type='number' ";
		if ($min) {
			$s.="min=$min ";
		}
		if ($max){
			$s.="max=$max ";
		}
		if ($step){
			$s.="step=$step ";
		}
		if (!$l) {
			$this->inputs[] = str_replace('###', $s, $this->input);
		} else {
			$this->inputs[] = $l.str_replace('###', $s, $this->input);	
		}
	}
	public function file_input($name, $label, $accept, $multiple){
		
		$id = $this->get_id();
		//make the string
		$id = $this->stripQuotes($id);
		$name = $this->stripQuotes($name);
		$l;
		//if there is a label, make it
		if ($label) {
			$l = str_replace('###',$label,$this->label);
			$l = str_replace('%%%',$id,$l);
		}
		$accept=$this->stripQuotes($accept);
		if ($multiple) {
			$multiple = 'true';
		} else {
			$multiple = 'false';
		}
		$s = "type='file' id='$id' name='$name' accept='$accept' multiple=$multiple"; 
		if ($l) { $this->inputs[]=$l.str_replace('###', $s, $this->input);}else{ $this->inputs[]=str_replace('###',$s,$this->input);}
	}
	//for rgb, provide as array [0-255, 0-255, 0-255]
	public function color($name, $label) {
		$id = $this->get_id();
		$l;
		//if there is a label, make it
		if ($label) {
			$l = str_replace('###',$label,$this->label);
			$l = str_replace('%%%',$id,$l);
		}
		$o;
		if ($l) {
			$o= $l.str_replace('###', "id='$id' type='color' name='$name'", $this->input);
		} else { 
			$o= str_replace('###', "id='$id' type='color' name='$name'", $this->input);
		}
		$this->inputs[] = $o;
	}
	//valid types include time, date, month, and datetime-local
	public function datetime_input($name, $label, $type) {
		
		$id = $this->get_id();
		$id = $this->stripQuotes($id);
		$name = $this->stripQuotes($name);
		if ($label) {
			$l = str_replace('###',$label,$this->label);
			$l = str_replace('%%%',$id,$l);
		}

		$valid_types = ['time', 'date', 'month', 'datetime-local'];
		if (!in_array($type, $valid_types)) {
			return false;
		}
		if ($l) {
			$o = $l.str_replace('%%%', "id='$id' name='$name' type='$type' ", $this->input);
		} else {
			$o = str_replace('%%%', "id='$id' name='$name' type='$type' ", $this->input);
		}
		$this->inputs[]=$o;
		

	}
	//writes the submit button
	//set $is_button to true to have the element be a <button> instead of a <input>
	public function submit_input($innerText, $is_btn ,$attr) {
		if ($this->has_submit) {
			return false; //return false if there is alread a submit btn
		}
		$this->has_submit = true;
		if (!is_null($attr)) {
			$a_s = 'type=\'submit\' '.$this->assoc_to_string($attr);
		} else {
			$a_s = ' type=\'submit\'';
		}
		if ($is_btn) {
			$o = str_replace('###',$innerText, $this->button);
			$o = str_replace('%%%', $a_s,$o);
		} else {
			$a_s.='value='.$this->stripQuotes($innerText).' ';
			$o = str_replace('###', $a_s, $this->input);
		}
		$this->inputs[]=$o;
	}

	//these are the methods for appending input elements
	// call as such:
	//
	// 	 $object->add_input['name'](args)
	//
	//
	
	
	//initialize the class with the form's ID, its action (or URL), and method.
	//
	//
	
	function __construct($form_id, $url, $method) {
		$this->form_id = $this->stripQuotes($form_id);
		//write the attributes for the main form element
		$this->form = str_replace('%%%', "id='".$this->stripQuotes($form_id)."' method='".$this->stripQuotes($method)."' action='".$this->stripQuotes($url)."'", $this->form);
		//convert the methods into an associative array of name->function
	}


	
	//call this to return the HTML of the form.

	public function write($break) {
		//writes list of inputs to string, wraps with main <form> and returns output HTML
		//concat list to string 
		$s='';
		foreach ($this->inputs as $i) {
			$s.=$i;
			if ($break) {
				$s.="<br>\n";
			}
		};
		//do the final replacement
		return str_replace('###', $s,$this->form);
	}
}


/*

// usage example creating a form:
$testform = new Form('form-0', 'http://localhost/example.php', 'get');
//text inputs
$testform->text_input('textinput1', 'text', ['label'=>'input some text here: ']);
$testform->text_input('textinput2', 'email', ['label'=>'enter email: ']);
$testform->text_input('textinput3', 'password', ['label'=>'enter passcode: ']);

$testform->submit_input('submit', true, null);
echo $testform->write(true, null)


 */
?>
