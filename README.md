<h1>PHP functions for writing HTML</h1>
<p>this repo is for php functions that generate and style HTML</p>
these are the current files/class definitions contained in this repo:
<ul>
  <li>tables.php</li>
</ul>
<br><br>
<div>
<h2>files, classes, and usages</h2>
<h3>tables</h3>
<h4>general usage</h4>
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
</ol>
</div>
