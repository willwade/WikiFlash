**README**

This is the code base of otwikiflash. Not the most amazing of sites - but neatly created a rollcall of editors who submitted there name to the site and would track number of edits.. and provide users with a certificate for their CPD.

Requirements:
* LAMP (Linux, Apache, Mysql, PHP web server stack)
* PHP 5+
* DB Access. There is no fancy install script sadly!

Credits: 
* Makes significant use of savant3 for templating. 
* Sarah Bodell and Angela for the idea at COT Conference in Brighton some years ago

**Instructions**

1.  Download the [zip](archive/master.zip) of the site
2.  Set-up the libs folder. To save re-distribution of already opensource projects I have omitted most of the libraries for the site. You will need to download them and place in the file structure to get this site to work! So install the following into Libs folder:
    * Savant3: http://phpsavant.com/download/
    * MPDF: http://www.mpdf1.com/mpdf/download NB: 5.3 tested
    * Krumo: http://krumo.sourceforge.net NB: @version $Id: class.krumo.php 2 2007-04-17 04:43:21Z mrasnika $ tested
    * PHPMailer: http://phpmailer.worxware.com NB: Version 2.2.1 tested on php 5
    * Recaptcha lib: http://code.google.com/p/recaptcha/downloads/list?q=label:phplib-Latest

 NB: My libs folder looks like this:
    * JSON.php
    * Savant3/ (sub-files omitted from listing)
    * Savant3.php
    * class.phpmailer.php
    * class.pop3.php
    * class.smtp.php
    * krumo/ (sub-files omitted from listing)
    * language/ (the lang folder of phpmailer)
    * mpdf/ (sub-files omitted from listing)
    * recaptchalib.php

3. Create a database in mysql, import otwikiflash.sql, set the config up in _init.php. NB: you can set a development server and live server details here.

        if ($live == true) {
        // For live server 
            $dbh=mysql_connect("localhost", "DBUSER", "DBPASS") or die ('I cannot connect to the database because: ' . mysql_error());
            mysql_select_db("otwikiflash");
            $server = 'http://otwikiflash.net';
            $usecaptcha = false;
        } else {
        // For development server
            $dbh=mysql_connect("localhost", "DBUSER-Test", "DBPASS-Test") or die ('I cannot connect to the database because: ' . mysql_error());
            mysql_select_db("lamp-wikiflash");
            $server = 'http://lamp.otjobinfo';
            $server = 'http://lamp.otwikiflash';
            $usecaptcha = false;
        }
4. Create and edit the templates. The files for these are in templates/, templates_es (for spanish) and some site general information in configs/Global.xml  It's all pretty straightforward. Note too some nasty hacks in index.php like "$menu = array.." on line 43. that helps create the dynamic menus. 
5. CHMOD -R 755 media/
6. Change the line in .htaccess to point to your realpath on the server, e.g.
    
        RewriteRule !\.(gif|jpg|png|css|js|ico|xml|pdf|doc|html)$ /path/to/website/eg/public/htdocs/index.php

6. Set-up the PDF functionality. This is to create a nice downloadable, print-ready certificate. There are three options buried in index.php but they all rely on converting a html page (using template cpdform.tpl.php) to pdf. First is to use a service from [www.htm2pdf.co.uk](http://www.htm2pdf.co.uk). Its pretty fail-safe. Just enter a key available by signing up to their service on line 284.

        case 'cpdpdf':
            $key = 'HTML2PDF-KEY-GOES-HERE';

    Or set your system to use mpdf or fpdi. Your mileage may vary with this. 
6.  Set-up mail-server details. Yes, you are probably wondering why all the settings aren't in config.xml.. look this was a quick hack ok?! Go easy! See line 457 on index.php
    
   				$mail->Host     = "mail.metaot.com";
				$mail->Port 	= "26";
				$mail->Username = 'mailserver+metaot.com';
				$mail->Password = 'PASSWORD-FOR-MAILSERVER';

7.  Set-up a cron-job from anywhere. Doesn't have to be the server it is on. Mine looks like this:
    
    4 1,13 * * * lynx http://otwikiflash.net/action/cronupdate  > /dev/null  > /dev/null 2>&1

**A little aside on how it all works**

* All requests are piped to index.php thanks to the .htaccess line. Any php/html files that exist on the server are served up untouched.
* The site works on a nice url path:
        
        action/value 
    e.g
        action/home
    
    looks up the home template. If you want a new page just pop a template file in the template folder and call it by domain.com/action/newfile. Also bear in mind lang/ and format/ - which are also GET requests.
* There are some nasty hacks to allow edits to only be counted during a set period. Look at the top of index.php to get an idea of what is going on. Notably:
    
        $startdate = $startdate08 = 1225670400;
        $enddate08 = 1226188800;
        $startdate09 = 1257120000;
        $enddate09 = 1257638400;
        $startdate10 = 1287964800; 
        //1326153600;
        $enddate = $enddate10 = 1288310400; 
        
    This all gets hardcoded into index.php - so that the edited user gets the correct year updated. There are only two places for it all. One at around 180 (usual submit) and one around 390 (cronupdate). It looks something like this:
    
            if (substr($det['title'],0,5)!='User:'){
            if (substr($det['title'],0,5)!='Talk:'){
                $totaltalks++;
            }
            if (substr($det['title'],0,6)!='Image:'){
                $totalimage++;
            }
            $timestr = strtotime($det['timestamp']);
            if ($timestr<$enddate08 && $timestr>$startdate08){
                $totalwikiweek08++;
            }
            if ($timestr<$enddate09 && $timestr>$startdate09){
                $totalwikiweek09++;
            }
            if ($timestr<$enddate10 && $timestr>$startdate10){
                $totalwikiweek10++;
            }
            $totaledits++;

    That should be pretty understandable. I.e. if a user has updated in the set-date period update the database for the user. 
    

Apologies for the poor documentation! This was all a rather hacky, last minute affair! 