<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
				<title>: : <?php echo $this->data['base_title'] ?> : :</title>
				<meta name="keywords" content="<?php echo $this->data['base_keywords'] ?>" />
				<meta name="description" content="<?php echo $this->data['base_description'] ?>" />
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
				<script type="text/javascript" charset="utf-8" src="/js/jquery.simplemodal.js"></script>
				<script type="text/javascript" src="/js/jquery.liscroll.js"></script>
				<script type="text/javascript" src="/js/jquery.main.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
				<link rel="stylesheet" type="text/css" href="/css/liticker.css" />
    </head>

    <body>

        <div id="first_container">
            <div id="second_container">
                <div id="third_container">
                                
                    <ul id="menu">
					<?php foreach($this->menu as $url=>$text){
						if ($url == $this->action){
						?>
                        <li><span id="menuselected"><?php echo $text; ?></span></li>
						<?php } else { ?>
	                        <li><a href="/lang/<?php echo $this->lang; ?>/action/<?php echo $url; ?>"><?php echo $text; ?></a></li>
						<?php 
							}
						} ?>
                    </ul>
                    
                    <div class="title"></div>
                    <div class="border">
                        <div class="content">
						<?php if(isset($this->include)) { include $this->template($this->include); } ?>
                        </div>
                    </div>

                    <ul id="submenu">
                        <?php if($this->action == 'support') { ?>
                        <li><span id="submenuselected">Support Us</span></li>
                        <?php } else { ?>
                        <li><a href="/lang/<?php echo $this->lang;?>/action/support">Support Us</a></li>
                        <?php } ?>
                        <?php if($this->action == 'rollcall') { ?>
                        <li><span id="submenuselected">Roll Call</span></li>
                        <?php } else { ?>
                        <li><a href="/lang/<?php echo $this->lang;?>/action/rollcall">Roll Call</a></li>
                        <?php } ?>
                        <?php if($this->action == 'contact') { ?>
                        <li><span id="submenuselected">Contact</span></li>
                        <?php } else { ?>
                        <li><a href="/lang/<?php echo $this->lang;?>/action/contact">Contact</a></li>
                        <?php } ?>
                    </ul>
                    
                    <div class="designinfo">
                        <a href="/action/about">About the People behind this</a>&nbsp;|&nbsp;<a href="/lang/en/action/<?php echo $this->action; ?>"><img src="/images/lang-en.png" alt="English" width="16" height="16"></a>
                    </div>
                    
                </div>
            </div>
        </div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-87195-12");
pageTracker._trackPageview();
</script>                
    </body>
    
</html>
