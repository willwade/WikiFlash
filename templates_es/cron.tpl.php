Cron Done. 

<?php if (!empty($this->errors)){ ?>
	Errors Occurred:
<?php foreach($this->errors as $error){ ?>
    <?php echo $error."\n"?><br />
<?php } } ?>
