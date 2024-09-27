<?php
require 'generate_html.php';

echo "<h1> example table </h1><br>";

//call new instance of object
//args as follows:
//	Table([title, title], [[item, item]])

$mytable = new Table(['titleA', 'titleB'], [['value_A1',' value_A2'],['value_B1','value_B2']]);

//args as follows:
//	(element/element-set, inline css);
$mytable->style('table','text-align:center;width:98%;margin:0% 1%;');//style the elements 
$mytable->style('head','font-weight:bold;');
$mytable->style('cells', 'padding:2%; border:solid black 1px;');
echo $mytable->write(); // return as html;
echo "<br><hr><br><h1> example form: </h1>";


//form example

//call the constructor
//args:
//	(formID, action, method)

$form = new Form('myform-1','/example.php','get');
//create some text inputs
//args:
//	(name, type, attributes [html_attr=>label])
//
//	where:
//
//		valid attribute keys include:
//			- any valid html attribute
//			- label --> to add a label. this is different from most of the input methods in that its passed as an attribute
//		valid types include:
//			text, email, password,search,tel,url
$form->text_input('text', 'text', ['label'=>'enter some text: ']);
$form->text_input('search', 'search', ['label'=>'search for something: ']);
$form->text_input('email', 'email', ['label'=>'enter an email: ','']);
$form->text_input('password', 'password', ['label'=>'enter a password: ']);
$form->text_input('phone', 'tel', ['label'=>'enter a phone number ']);
$form->text_input('url', 'url', ['label'=>'enter a URL: ']);

//add a fieldset
//args:
//	($name, $legend, $selection ['label'=>'value'];)
$form->fieldset('choice', 'example fieldset. choose an option', ['choice A'=>'option_1', 'choice B'=>'option_2', 'choice C'=>'option_3']);

//add a file input
//args:
//	($name, $label, $min [int or null], $max [int or null], $step [int or null])
$form->range_input('range','choose a value (0-10): ', 0, 10, 1);

//add a numeric input
//args:
//	($name, $label, $min, $max, $step)
$form->numeric_input('number','select a number(+/-100): ',-100, 100, 0.5);

//add a color input
//args:
//	($name, $label);


$form->color('color','Select a color: ');

//add a datetime input:
//
//args:
//	($name, $label, $type);
//	valid types include:
//		- time
//		- date
//		- month
//		- datetime-local
$form->datetime_input('time', 'enter a time: ', 'time');
$form->datetime_input('date', 'enter a date: ', 'date');
$form->datetime_input('month', 'enter a month/year: ', 'time');
$form->datetime_input('datetime-local', 'enter a time', 'time');

//write the submit button
//args:
//	($innertext, $is_btn (bool), $attr ['html_attr'=>'value'])
//
//	where:
//		innerText = the text displayed in value (if its an input) or the innertext inside the 
$form->submit_input('Submit', false, ['style'=>'padding:2%;']);
//write it as HTML
//args:
//	($break [bool])
echo $form->write(false); //call with $break=true so that inputs are separated by line breaks;

?>
