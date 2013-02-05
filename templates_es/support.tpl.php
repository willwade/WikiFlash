    <h1 class="cushycms">Show your support</h1>
    <div class="text cushycms">
    <p><b>So you are ready to go! To make sure your recognition is counted show your support and follow the links below</b></p>
<p>If you are on <a href="http://facebook.com/">Facebook</a> join the <a href="http://www.facebook.com/event.php?eid=108884928685">event here</a></p>
<h2>Get a certificate for your CPD and add your name to the rollcall</h2>
<p>So we know how successful the event is it would be great if you could submit your wikipedia username and real name to us. Note: we don't keep any passwords. There are some things in it for you! Once you have filled in your details you can get a nice looking certificate and you join the rollcall of entrants.</p>
<p>NB: <b>You need to have already made an edit or creation on wikipedia before adding your name here</b></p>

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
	<?php if($this->usecaptcha){ ?>
	<script>
	var RecaptchaOptions = {
	   theme: 'custom',
	   lang: 'en',
	   custom_theme_widget: 'recaptcha_widget'
	};
	</script>
	<form method="POST" action="">
	<div id="recaptcha_widget" style="display:none">
		<b>To prevent automated spammers we require you to enter a confirmation code. <span class="recaptcha_only_if_image">The code is displayed in the image you should see below. If you are visually impaired you can <a href="javascript:Recaptcha.switch_type('audio')">get an audio Choice</a></span><span class="recaptcha_only_if_audio">The code is displayed in the sound clip you should see below.<a href="javascript:Recaptcha.switch_type('image')">Get an image Choice</a> if this doesn't work for you</span></b>		
		<div id="recaptcha_image"></div><br />
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
		<label for="recaptcha_response_field" class="recaptcha_only_if_image">Enter the words above (<a href="javascript:Recaptcha.showhelp()">?</a>, <a href="javascript:Recaptcha.reload()">Reload</a>):</label>
		<label for="recaptcha_response_field" class="recaptcha_only_if_audio">Enter the numbers you hear (<a href="javascript:Recaptcha.showhelp()">?</a>, <a href="javascript:Recaptcha.reload()">Reload</a>):</label>
		<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" style="margin-bottom: 2px;"/><br />		
	</div>
	<?php
require_once('libs/recaptchalib.php');
$publickey = "6LcQrgMAAAAAANRWNG0K-NMq2pSyQXsbLpEK4ueM"; // you got this from the signup page
echo recaptcha_get_html($publickey);
	} ?>
	<label for="year">Year you would like to see  stats for</label>
	<select id='year' name='year' size='1'>
	<option label='2008' value='08'>08</option><option label='2009' value='09'>09</option><option label='2010' value='10'>10<option label='all' value='all' selected>all</option>
	</select><br />
	<label for="send"></label>
	<input id="send" type="submit" value="send">
</form>
<br />
</div>

