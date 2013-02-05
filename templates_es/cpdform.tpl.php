<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="/css/cpdform.css" />
    </head>

    <body>
    <div id="topBanner"><h1>OTWikiFlash <?php if($this->year == 'all'){?>'08-'10<? } else { ?>20<?php echo $this->year; }?></h1><h2>Certificate of Work for <?php echo $this->realname; ?></h2></div>
    <div id="bottomFrame"><p>OT Wikiflash is an event held online with the aim to improve the quality and volume of content on the publicly accessible and editable information resource Wikipedia. To read more about the event visit the site online at <a href="http://otwikiflash.net ">http://otwikiflash.net</a></p>
<p>This certifies that <b><?php echo $this->username; ?></b> (real name: <b><?php echo $this->realname; ?></b>) participated in OTWikiFlash in <?php if($this->year == 'all'){?>2008-2010<? } else { ?>20<?php echo $this->year; }?>. Please note that the quality or volume of detail has not been verified. The following entries have been supplied by the user as entries made:</p>
    <p>
    
    <?php  if (empty($this->userdets)){ echo "<b>Sorry. There is either no user with that username or you have made no entries to wikipedia</b>"; } else { ?>

	<table id="mytable" border="0" cellspacing="0" cellpadding="0" summary="Output from <?php echo $this->username; ?>'s edits on Wikipedia">
	<tr>
	<th scope="col" abbr="Page">Page</th>
	<th scope="col" abbr="Comment">Comment &amp; Date</th>
	</tr>
	
	<?php 
	$i=0; foreach ($this->userdets as $det){ 
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
	}?>
	</table>  
	<?php } ?>  
    </p>
    
    
    </div>
    </body>
</html>