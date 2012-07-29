<?php $this->load->view('header'); ?>

<header>

</header>
<div role="main">

<h2>Display Logs</h2>

<div>

</div>

<a href="/nmapweb/logs">
<div class="refresh"> 
	Refresh
</div>
</a>

<a href="/nmapweb/">
<div class="refresh"> 
	Home
</div>
</a>

<div class="clearfix"></div>

<pre>
	<?php echo $log_contents; ?>
</pre>

</div>

<?php $this->load->view('footer'); ?>