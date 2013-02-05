<script type="text/javascript">
$(function(){ 
    $("ul#news-slide-list").liScroll(); 
}); 
</script>
<?php include $this->template('_home.tpl.php'); ?>
<?php  if (!empty($this->rollcall)){ ?>
    <ul id="news-slide-list">
    <li><a href="/action/rollcall">The rollcall of editors</a>: </li>
	<?php $i=0; foreach ($this->rollcall as $rc){ $i++;?>
      <li><span><?php echo $i ?>.</span><?php echo htmlentities($rc['realname']); ?> (<a href="http://en.wikipedia.org/wiki/User:<?php echo $rc['userid'];?>"><?php echo htmlentities($rc['userid']);?></a>): <?php echo $rc['total_edits']; ?> edits,</li>
<?php } echo '</ul>'; } ?>    
</div>
<!--<div class="line"><img src="/images/h_line.jpg" alt="" width="400	" height="1" title="" /></div>-->