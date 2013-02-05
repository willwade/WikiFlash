    <h1 class="cushycms">The rollcall of editors on OTWikiFlash</h1>
    <div class="text cushycms">
    <p><b>RollCall</b></p>
<p>Names here are collated from people who have submitted their wikipedia username to the site. To do so enter your details <a href="/action/support">here</a>. Please note that the list is ordered by number of edits. Quantity does not necessarily mean quality!</p>
<p>
<?php  if (empty($this->rollcall)){ echo "<b>Sorry. Something is wrong! Come back later. </b>"; } else { ?>
</p>
	<table id="mytable" border="0" cellspacing="0" cellpadding="0" summary="RollCall of Editors from OTWikiFlash">
	<tr>
	<th scope="col" abbr="Username">Username</th>
	<th scope="col" abbr="RealName">Real Name</th>
	<th scope="col" abbr="Edits">Edits 08</th>
	<th scope="col" abbr="Edits">Edits 09</th>
	<th scope="col" abbr="Edits">Edits 10</th>
	<th scope="col" abbr="Edits">All time edits</th>
	</tr>
	<?php $i=0; foreach ($this->rollcall as $rc){ 
	$i++;
	?>
	<tr <?php if ($i%2==0){?>class="odd"<? } ?>>
	<td>
	<a href="http://en.wikipedia.org/wiki/User:<?php echo $rc['userid'];?>"><?php echo htmlentities($rc['userid']);?></a>
	</td>
	<td>
	<?php echo htmlentities($rc['realname']); ?>
	</td>
	<td>
	<?php echo $rc['editsonwikiwk08']; ?>
	</td>
	<td>
	<?php echo $rc['editsonwikiwk09']; ?>
	</td>
		<td>
	<?php echo $rc['editsonwikiwk10']; ?>
	</td>
	<td>
	<?php echo $rc['total_edits']; ?>
	</td>
	</tr>
	<?php } ?>
	</table>  
	<?php } ?>  

</div>