<h1>PHP functions for writing HTML</h1>
<br><br>
<div>
<h3>Table class</h3>
<ol>
<li>
  <p>call the constructor: </p>
  <code>$mytable = new Table([&lt;titleA&gt;, &lt;titleB&gt;], [[&lt;value_A1&gt;,&lt; value_A2&gt;],[&lt;value_B1&gt;,&lt;value_B2&gt;],...);</code>
</li>
<li>
  <p>apply styles</p>
  <code>$mytable->style(&lt;element_or_set&gt;,&lt;inline_css&gt;);</code>
</li>
<li>
  <p>valid elements and sets of elements include:</p>
  <ul>
    <li>table --&gt; which applies to the html table wrapper</li>
    <li>head --&gt; which applies to the thead wrapper</li>
    <li>body --&gt; which applies to the tbody wrapper</li>
    <li>rows --&gt; which applies to all of the rows (tr) contained in the generated table</li>
    <li>cells --&gt; which applies to all of the individual cells contained </li>
  </ul>
</li>
<li>
  <p>return the HTML</p>
  <code>$mytable->write();</code>
</li>
</ol><br><br>
<h3>Form class</h3>
<ol>
<li>
  <p>call the constructor: </p>
  <code>$myform = new Form(&lt;formID&gt;, &lt;action&gt;, &lt;method&gt;);</code>
</li>
<li>
  <p>add inputs</p>
  <em>methods:</em>
  <ul>
    <li><p>for text based inputs: </p><br><code>$myform-&gt;text_input($name, $type, $attributes)</code> <p>where valid types include: </p> <code>['text', 'search', 'email', 'password', 'tel', 'url']</code></li>
    <li><p>for fieldsets: </p><br> <code>$myform-&gt;fieldset($name, $label, $selection)</code> <p>where <code>$selection</code> is in the following format: <code>array('label'=>'value', 'label'=>'value', ...]</code></p></li>
    <li><p>for range sliders:<br> <code> $myform-&gt;range_input($name,$label,$min,$max,$step);</code></p></li>
    <li><p>for numeric inputs:<br> <code>$myform-&gt;numeric_input($name,$label,$min,$max,$step)</code></p></li>
    <li><p>for datetime inputs:<br> <code>$myform->datetime_input($name, $label, $type)</code></p><br> where valid types include <code>['time', 'date', 'datetime-local', 'month']</code></li>
    <li><p>for submit buttons:</p> <code>$myform->submit_input($innerText, $is_button, $attr)</code><p>, where $innertext is the text either as the value attribute when its not a button or the inner text when it is.</p></li>
    <li><p>for numeric inputs:</p> <code>$myform-&gt;numeric_input($name,$label,$min,$max,$step)</code></li>
  </ul>
</li>
<li>
  <p>return the HTML:</p><br>
  <code>$myform->write($break);</code><br><p>where $break is a boolean value determining whether or not to add line breaks between elements</p>
</li>
</ol>
<h4>id structure in returned HTML</h4>
<p>IDs are all based on the original formID provided in the constructor. each input within the form has an id whose syntax is as such: <code>$formID-&lt;int&gt; </code> where the integer is the index of the input relative all the others in the form (whose order is determined by the order in which one calls the methods). radios contained in fieldsets have ids in a different syntax: <code>formID-&lt;int&gt;-Name-&lt;int&gt;-</code>. the first int is the index of the <em>fieldset</em> relative to the <em>form</em>, the second is the index of the radio-element relative to the fieldset.</p>
  
<em>see file example.php for more detailed examples of how to use the different methods.</em>
</div>
