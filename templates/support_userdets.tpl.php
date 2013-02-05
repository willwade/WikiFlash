    
    <h1 class="cushycms">Your data</h1>
    <div class="text cushycms">
    <p><b>Thanks for your details! Find below a summary of the information we could gather. We shall keep hold of your username for the purposes of the OTWikiFlash event and update the rollcall accordingly. </b></p>

<p><h2>Summary of Work (<?php echo $this->year?>)</h2>
<?php  if (empty($this->userdets)){ echo "<b>Sorry. There is either no user with that username or you have made no entries to wikipedia</b>"; } else { ?>

	<table id="mytable" border="0" cellspacing="0" cellpadding="0" summary="Output from <?php echo $this->username; ?>'s edits on Wikipedia">
	<tr>
	<th scope="col" abbr="Page">Page</th>
	<th scope="col" abbr="Comment">Comment &amp; Date</th>
	</tr>
	
	<?php $i=0; foreach ($this->userdets as $det){ 
	if (substr($det['title'],0,5)!='User:'){
	$i++;
	$timest=strtotime($det['timestamp']);
	if ($timest>=$this->startdate && $timest<=$this->enddate){
	?>
	<tr <?php if ($i%2==0){?>class="odd"<? } ?>>
	<td>
	<a href="http://en.wikipedia.org/w/index.php?title=<?php echo str_replace(' ','_',$det['title']);?>&oldid=<?php echo $det['revid']?>"><?php echo $det['title'];?></a>
	</td>
	<td>
	<?php echo $det['comment'];?><br /><span class="date"><?php echo date("F jS, Y", strtotime($det['timestamp']));?></span>
	</td>
	</tr>
	<?php }
	}
	} ?>
	</table>  
	<?php } ?>  
</p>

<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
);
</script>

<p>If you would like a certificate of your efforts click <a href="/action/userdets/sub/cpdhtml/username/<?php echo $this->username?>/realname/<?php echo urlencode($this->realname)?>/year/<?php echo $this->year?>" target="_new">here for a HTML version that you can view in your browser</a> or <a href="/action/userdets/sub/cpdpdf/username/<?php echo $this->username?>/realname/<?php echo urlencode($this->realname)?>/year/<?php echo $this->year?>" target="_new">here for a pdf version to download and keep</a></p>
</div>

