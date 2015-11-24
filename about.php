<?
	include_once('database/connection.php');
    include_once('database/users.php');
	include('template/header.php');
?>
<section class="column-group gutters article">
<div class="xlarge-15 large-15 medium-10 small-5 tiny-5">
</div>
<div class="xlarge-70 large-70 medium-80 small-90 tiny-90">
<h2 class="slab">Objective</h2>
<div class="level2">
<p>Create a web application where users can create, share, and manage events. </p>
<p>To create this application, students should:</p>
<ul>
	<li class="level1">Create a <strong>sqlite</strong> database where data about users and events is stored.</li>
	<li class="level1">Create documents using <strong><abbr title="HyperText Markup Language">HTML</abbr></strong> and <strong><abbr title="Cascading Style Sheets">CSS</abbr></strong> that represent the web pages of the application.</li>
	<li class="level1">Use <strong>PHP</strong> to generate those web pages after retrieving/changing data from the database.</li>
	<li class="level1">Use <strong>Javascript</strong> or <strong>jQuery</strong> to enhance the user experience (for example using Ajax).</li>
</ul>
</div>
<h2 class="slab half-top-space">Requirements</h2>
<div class="level2">
<p>The <strong>minimum</strong> expected requirements are the following:</p>
<ul>
<li class="level1"><div class="li"> Users should be able to <strong>register</strong> an account.</div>
</li>
<li class="level1"><div class="li"> Users should be able to <strong>login</strong>/<strong>logout</strong> from the system.</div>
</li>
<li class="level1"><div class="li"> Registered users should be able to <strong>create</strong> a event.</div>
</li>
<li class="level1"><div class="li"> Registered users should be able to <strong>manage</strong> (edit, delete, …) their events.</div>
</li>
<li class="level1"><div class="li"> Events should contain one <strong>image</strong>, a <strong>date</strong>, a <strong>description</strong> and a <strong>type</strong> (e.g. party, concert, conference, …).</div>
</li>
<li class="level1"><div class="li"> Users should be able to <strong>search</strong> for and <strong>register</strong> in events.</div>
</li>
<li class="level1"><div class="li"> Users should <strong>not</strong> be able to register <strong>twice</strong> in the same event.</div>
</li>
<li class="level1"><div class="li"> Event owners and users that registered to an event, should be able to add <strong>comments</strong> to the event.</div>
</li>
</ul>
<ul>
<li class="level1"><div class="li"> The following technologies should all be used: <strong><abbr title="HyperText Markup Language">HTML</abbr></strong>, <strong><abbr title="Cascading Style Sheets">CSS</abbr></strong>, PHP, <strong>Javascript</strong> (by means of <strong>jQuery</strong>), <strong>Ajax</strong>/<strong>JSON</strong>, <strong>PDO</strong>/<strong>SQL</strong> (using <strong>sqlite</strong>).</div>
</li>
<li class="level1"><div class="li"> The web site should be as <strong>secure</strong> as possible.</div>
</li>
<li class="level1"><div class="li"> Code should be <strong>organized</strong> and <strong>consistent</strong>.</div>
</li>
</ul>
<p>Some suggested <strong>extra</strong> requirements:</p>
<ul>
<li class="level1">Event owners should be able to decide if the event is <strong>public</strong> or <strong>private</strong>.</li>
<li class="level1">Private events should <strong>not</strong> appear on searches. Users must be invited to <strong>private</strong> events.</li>
<li class="level1">A <strong>forum</strong> (with threads and posts) for each event instead of a simple comment system.</li>
<li class="level1">Ability to add <strong>photos</strong> to events. And maybe albuns.</li>
<li class="level1">Possibility to <strong>share</strong> a event using email or a social network.</li>
<li class="level1">And whatever you come up with…</li>
</ul>
</div>
</div>
</section>
<?
	include('template/footer.php');
?>