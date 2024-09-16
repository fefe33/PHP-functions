<!DOCTYPE html>
<html>
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
	//style it (with inline CSS) -- its your as job not to fck this up. add a quote somewhere you shouldnt it will break your HTML 
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
?>



<div style='width:100%;text-align:center;'>
<?php
//create the table object
$mytable = new table(['title1', 'title2'], [['value A1','value A2'],['value B1']]);
//apply the styles
$mytable->style('table', 'margin:auto;text-align:center;width:40%;border:solid black:2px;');
$mytable->style('head', 'font-size:2rem;padding:2%;');
$mytable->style('body','color:green;');
$mytable->style('cells','padding:30px;border:solid grey 1px;');


//return the 
echo $mytable->write();
?>
</div>




</html>
