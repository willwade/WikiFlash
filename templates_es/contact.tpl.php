    <h1 class="cushycms">Contact the organisers</h1>
    <div class="text cushycms">
<p>Use the form below to get in touch. We will try and get back to you as soon as possible but please be patient - we are all doing regular jobs on top of this commitment.</p>


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

    
<form action="/action/contact/sendit/true" method="post" id='contactForm' name='contactForm'>
	<label for="clientName">Your Name</label>
	<input id="clientName" type="text" size="20" value="<?php echo $this->form['clientName']?>" name='clientName'><br />
	<label for="clientEmail">Your Email</label>
	<input id="clientEmail" type="text" size="20" value="<?php echo $this->form['clientEmail']?>" name='clientEmail'><br />

	<label for="clientSubject">Concerning</label>
	<select id="clientSubject" size="1" name='clientSubject'>
		<option label="Support and Help" value="support">Support and Help</option>
		<option label="I Love It!" value="loveit">I love it!</option>
		<option label="I Hate It!" value="hateit">I hate it!</option>
		<option label="Feedback" value="feedback">Feedback</option>
		<option label="General Enquiry" value="query">General Enquiry</option>
	</select><br />

	<label for="clientMsg">Your message</label>
	<textarea id="clientMsg" rows="5" cols="20" name='clientMsg'><?php echo $this->form['clientMsg'] ?></textarea><br /><br />
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
		<label for="recaptcha_response_field" class="recaptcha_only_if_image">Enter the words (<a href="javascript:Recaptcha.showhelp()">?</a>, <a href="javascript:Recaptcha.reload()">Reload</a>):</label>
		<label for="recaptcha_response_field" class="recaptcha_only_if_audio">Enter the numbers you hear (<a href="javascript:Recaptcha.showhelp()">?</a>, <a href="javascript:Recaptcha.reload()">Reload</a>):</label>
		<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" style="margin-bottom: 2px;"/><br />
	</div>
	<?
require_once('libs/recaptchalib.php');
$publickey = "6LcQrgMAAAAAANRWNG0K-NMq2pSyQXsbLpEK4ueM"; // you got this from the signup page
echo recaptcha_get_html($publickey);
	?>
	
	<label for="send"></label>
	<input id="send" type="submit" value="send">
</form>

</div>
