<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');
?>
<div class="ink-grid push-center all-60 large-70 medium-85 small-100 tiny-100">
<h2 class="slab">Objective</h2>
<p>Create a web application where users can create, share, and manage events. </p>
<p>To create this application, students should:</p>
<ul>
<li>Create a <strong>sqlite</strong> database where data about users and events is stored.</li>
<li>Create documents using <strong>HTML</strong> and <strong>CSS</strong> that represent the web pages of the application.</li>
<li>Use <strong>PHP</strong> to generate those web pages after retrieving/changing data from the database.</li>
<li>Use <strong>Javascript</strong> or <strong>jQuery</strong> to enhance the user experience (for example using Ajax).</li>
</ul>
<h2 class="slab half-top-space">Requirements</h2>
<p>The <strong>minimum</strong> expected requirements are the following:</p>
<ul>
<li>Users should be able to <strong>register</strong> an account.</li>
<li>Users should be able to <strong>login</strong>/<strong>logout</strong> from the system.</li>
<li>Registered users should be able to <strong>create</strong> a event.</li>
<li>Registered users should be able to <strong>manage</strong> (edit, delete, …) their events.</li>
<li>Events should contain one <strong>image</strong>, a <strong>date</strong>, a <strong>description</strong> and a <strong>type</strong> (e.g. party, concert, conference, …).</li>
<li>Users should be able to <strong>search</strong> for and <strong>register</strong> in events.</li>
<li>Users should <strong>not</strong> be able to register <strong>twice</strong> in the same event.</li>
<li>Event owners and users that registered to an event, should be able to add <strong>comments</strong> to the event.</li>
</ul>
<ul>
<li>The following technologies should all be used: <strong>HTML</strong>, <strong>CSS</strong>, <strong>PHP</strong>, <strong>Javascript</strong> (by means of <strong>jQuery</strong>), <strong>Ajax</strong>/<strong>JSON</strong>, <strong>PDO</strong>/<strong>SQL</strong> (using <strong>sqlite</strong>).</li>
<li>The web site should be as <strong>secure</strong> as possible.</li>
<li>Code should be <strong>organized</strong> and <strong>consistent</strong>.</li>
</ul>
<p>Some suggested <strong>extra</strong> requirements:</p>
<ul>
<li>Event owners should be able to decide if the event is <strong>public</strong> or <strong>private</strong>.</li>
<li>Private events should <strong>not</strong> appear on searches. Users must be invited to <strong>private</strong> events.</li>
<li>A <strong>forum</strong> (with threads and posts) for each event instead of a simple comment system.</li>
<li>Ability to add <strong>photos</strong> to events. And maybe albuns.</li>
<li>Possibility to <strong>share</strong> a event using email or a social network.</li>
<li>And whatever you come up with…</li>
</ul>
</div>
<?
	include('template/footer.php');
?>