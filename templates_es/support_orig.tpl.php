    <h1 class="cushycms">Show your support</h1>
    <div class="text cushycms">
    <p><b>So you are ready to go! To make sure your recognition is counted show your support and follow the links below</b></p>
<p>If you are on <a href="http://facebook.com/">Facebook</a> join the <a href="http://www.new.facebook.com/event.php?eid=42484655866">event here</a></p>
<h2>Get a certificate for your CPD and add your name to the rollcall</h2>
<p>So we know how successful the event is it would be great if you could submit your wikipedia username and real name to us. Note: we don't keep any passwords. There are some things in it for you! Once you have filled in your details you can get a nice looking certificate and you join the rollcall of entrants.</p>

<?php if (!empty($this->errors)){ ?>
<script type='text/javascript' language='javascript1.1'>
<!--
$(document).ready(function () {
	$('#error').modal({onClose: modalClose});	
});      
//-->
</script>
<? } ?>

<?php if (!empty($this->messages)){ ?>
<script type='text/javascript' language='javascript1.1'>
<!--
$(document).ready(function () {
	$('#error').modal({onClose: modalCloseReturn});
});      
//-->
</script>
<? } ?>

<!-- errors -->
<?php if (!empty($this->errors)){ ?>
<div id="error">
Eeek! There has been a problem trying to send your message. See the possible problems and try again: <br />
<?php foreach($this->errors as $error){ ?>
    <?php echo $error?> <br />
<?php } ?><hr>
</div>
<?php } ?>

<!-- messages -->
<?php if (!empty($this->messages)){ ?>
<div id="error">
<?php foreach($this->messages as $message){ ?>
    <?php echo $message?> <br />
<?php } ?><hr>
</div>
<?php } ?>

<form action="/action/support/sendit/true" method="post" id='contactForm' name='contactForm'>
	<label for="username">Your UserName as it is on Wikipedia</label>
	<input id="username" type="text" size="20" value="<?php echo $this->username ?>" name='username'><br />
	<label for="realname">Your Real Name</label>
	<input id="realname" type="text" size="20" value="<?php echo $this->realname ?>" name='realname'><br />
<script>
 var RecaptchaOptions = {
    theme : 'white'
 };
 </script>
	<?
require_once('libs/recaptchalib.php');
$publickey = "6LcQrgMAAAAAANRWNG0K-NMq2pSyQXsbLpEK4ueM"; // you got this from the signup page
echo recaptcha_get_html($publickey);
	?>
	
	<label for="send"></label>
	<input id="send" type="submit" value="send">
</form>
</div>

