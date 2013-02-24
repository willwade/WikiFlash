<?php
/*
	Several functions of this site:
	1. To display flickr images tagged with for-blog
	2. Grab image and a detail (title, tags and stuff) entry - use flickr-jquery code
	3.  
*/

/* RE-WRITING - NB: Now all done bar static files by this */
# 1. check to see if a "real" file exists..
if(file_exists($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])
	&& ($_SERVER['SCRIPT_FILENAME']!=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])
	&& ($_SERVER['REQUEST_URI']!="/")){
		include($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
		exit();
	}

# 2. if not, go ahead and check for dynamic content.
# For this site stuff is in KEY=VALUE key/value pairs
$url=strip_tags($_SERVER['REQUEST_URI']);
$urlar=explode("/",$url);
array_shift($urlar); //the first one is empty anyway
for ($i = 0; $i <= sizeof($urlar)-1; $i++) {
	$val=@strstr($urlar[$i+1], '?', true);
	$_GET[$urlar[$i]]=($val===true) ? urldecode($val) : urldecode($urlar[$i+1]);
	$i++; # Yes TWICE - because otherwise vals become keys - doubling the size of the array :)
}

/* INIT */
error_reporting(E_ALL);
set_include_path(get_include_path().':'.realpath('libs/'));
include_once('_init.php');
$startdate = $startdate08 = 1225670400;
$enddate08 = 1226188800;
$startdate09 = 1257120000;
$enddate09 = 1257638400;
$startdate10 = 1287964800; 
//1326153600;
$enddate = $enddate10 = 1288310400; 
//1336694399;
//menus

$menu = array('help'=>'Support/Help',
							'contribute'=>'3. Contribute!',
							'wikipedia'=>'2. Wikipedia?!',
							'why'=>'1. Why?',
							'home'=>'Introduction');
														
include_once('krumo/class.krumo.php');
krumo::disable();

// read in settings from .htaccess & Global.xml
$errors = $config =  $form = array();
$action = (isset($_GET['action'])) ? $_GET['action'] : 'home' ;
$lang = (isset($_GET['lang'])) ? $_GET['lang'] : 'en' ;
$format = (isset($_GET['format'])) ? $_GET['format']  :  'html' ;

// Search Params
// This is where you can set some defaults too
	
//do the php5 specific config setting
$xmlstr = file_get_contents('configs/Global.xml');
$config = new SimpleXMLElement($xmlstr);

// load some data
	$data['base_title'] = (string) $config->base_title;
	$data['base_description'] = (string) $config->base_description;
	$data['base_keywords'] = (string) $config->base_keywords;
	$data['email'] = (string) $config->email;
	
// Load the Savant2 class file and create an instance.
require_once 'libs/Savant3.php';
$tpl =& new Savant3();
if ($lang=='en'){
$tpl->addPath('template', realpath('templates/'));
} elseif( $lang=='es') {
$tpl->addPath('template', realpath('templates_es/'));
}
$tpl->assign('lang',$lang);

// Assign all the variables from the config file - who needs databases eh?
$tpl->assign('data',$data);
//assign menu
$tpl->assign('menu',$menu);

/* MAIN */
// From the _init
$tpl->assign('server_name',$server);
$tpl->assign('format',$format);

krumo($_GET);		
		

/* CONTROLLER */
switch ($action) {	
	// this is default btw
	case 'home':
		$sql = "SELECT total_edits, userid, realname FROM users order by total_edits DESC;";
		$r = q($sql);
		$tpl->assign('rollcall',$r);
		$tpl->assign('action','home');
		$tpl->assign('include','home.tpl.php');				
		$tpl->display('frame.tpl.php');	
	break;
	
	
	case 'support':
		$submitted = (isset($_GET['sendit']) && !empty($_GET['sendit'])) ? true : false ;
		$username = (isset($_POST['username'])) ? trim($_POST['username']) : '' ;
		$realname = (isset($_POST['realname'])) ? trim($_POST['realname']) : '' ;
		$year = (isset($_POST['year'])) ? trim($_POST['year']) : 'all' ;
		
		if ($year=='all') { 
			$startdate = $startdate08; $enddate =  $enddate10;
		} elseif($year=='08'||$year=='09'||$year=='10') {
			$startdate = ${"startdate$year"}; $enddate = ${"enddate$year"};
		}
		
		if ($submitted){
			
			// Validation
			if ($username==''){
				$errors[] = 'Sorry you at least need to enter a username';
			}
			
			if ($realname==''){
				$errors[] = 'No Problem if you don\'t want to supply your Real Name - but please place Anon or something similar!';
			}
			
			// Now check recaptcha
				if($usecaptcha){
					require_once('libs/recaptchalib.php');
					$privatekey = "CAPTCHA-KEY-GOES-HERE";
					$resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);			
					if (!$resp->is_valid) {
					  $errors[] = "The reCAPTCHA wasn't entered correctly. Go back and try it again.(reCAPTCHA said: " . $resp->error . ")";
  						$_SESSION["wikiflashcaptchad"] = 'no';
					} else {
						$_SESSION["wikiflashcaptchad"] = 'yes';
					}
			}
			
			
			// Get the details
			// convert username
			$finds = array(' ','#','/','\\');
			$replaces = array('_','','','');
			$username = ucwords(str_replace($finds,$replaces,$username));
			$url = 'http://en.wikipedia.org/w/api.php?action=query&list=usercontribs&uclimit=500&format=php&ucuser='.$username;
			
			// new safer file getting method
			$query = _getFile($url);
			
			$userdets = unserialize($query);
			$userdets = $userdets['query']['usercontribs'];
			if (empty($userdets)){
				$errors[]= 'Sorry! Your username returned no data from Wikipedia! Either you have entered an incorrect username or you haven\'t made any entries yet! Check and try again';
			}
			
			if (empty($errors)) {
				//Do Stuff 
				// OK so they have submitted their details. 
				// This is the stuff the user wants to see..
				$tpl->assign('userdets',$userdets);
				$tpl->assign('include','support_userdets.tpl.php');	
				
				//Lets do some quick sums
				$totaledits = $totaltalks = $totalimage = $totalwikiweek08 = $totalwikiweek09 = $totalwikiweek10 = 0;
				 foreach ($userdets as $det){ 
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

					}
				 }					
				 
				// Lets first add them to the queue to be added to the database
				$sql = "SELECT count(*) from users where userid='".$username."'";
				$r = q($sql);
				if($r<=0){					
					$sql = "INSERT INTO users (userid, total_edits, editsonwikiwk08, editsonwikiwk09, editsonwikiwk10 realname) values('".$username."','".$totaledits."','".$totalwikiweek08."','".$totalwikiweek09."','".$realname."')";
					q($sql);	
				} else {
					$sql = "UPDATE users SET total_edits=".$totaledits.", editsonwikiwk08=".$totalwikiweek08.", editsonwikiwk09=".$totalwikiweek09.", editsonwikiwk10=".$totalwikiweek10.", realname='".$realname."' where userid='".$username."';";
					q($sql);
				}
			} else {
				$tpl->assign('include','support.tpl.php');
			}
		} else {
			$tpl->assign('include','support.tpl.php');
		}
		
		$tpl->assign('usecaptcha',$usecaptcha);
		$tpl->assign('realname',$realname);
		$tpl->assign('username',$username);
		$tpl->assign('year',$year);
		$tpl->assign('startdate',$startdate);
		$tpl->assign('enddate',$enddate);		
		@$tpl->assign('errors',$errors);	
		@$tpl->assign('messages',$messages);	
		$tpl->assign('action','support');	
		$tpl->display('frame.tpl.php');
	break;
		
	case 'userdets':
		$username = (isset($_GET['username'])) ? trim($_GET['username']) : '' ;
		$realname = (isset($_GET['realname'])) ? trim($_GET['realname']) : '' ;
		$year = (isset($_GET['year'])) ? trim($_GET['year']) : 'all' ;
		
		if ($year=='all') { 
			$startdate = $startdate08; $enddate =  $enddate10;
		} elseif($year=='08'||$year=='09'||$year=='10') {
			$startdate = ${"startdate$year"}; $enddate = ${"enddate$year"};
		}
		
	    $sub = (isset($_GET['sub'])) ? trim($_GET['sub']) : '' ;
 		$url = 'http://en.wikipedia.org/w/api.php?action=query&list=usercontribs&uclimit=500&format=php&ucuser='.$username;
		$userdets = unserialize(_getFile($url));
		$userdets = $userdets['query']['usercontribs'];
		$tpl->assign('realname',$realname);
		$tpl->assign('username',$username);
		$tpl->assign('userdets',$userdets);
		$tpl->assign('year',$year);
		$tpl->assign('startdate',$startdate);
		$tpl->assign('enddate',$enddate);
		
		switch($sub) {
			case 'add':
			//see if it already exists
			$sql = "SELECT count(*) from users where username='".$username."'";
			$r = q($sql);
				print_r($r);
			if($r[0]<=0){
				$sql = "INSERT INTO users (userid,talks,edits,creations,firstedit) values( )";
				
			}
			break;
			case 'removeMONKEY':
				$sql = "DELETE from users where userid='".$username."'";
				q($sql);
			break;
			case 'cpdhtml':
				$tpl->display('cpdform.tpl.php');
				die();
			break;
			case 'mpdf':			
				define('_MPDF_PATH',realpath('libs/mpdf/').'/');
				include("libs/mpdf/mpdf.php");
				
				$mpdf=new mPDF(); 
				
				$mpdf->SetUserRights();
				$mpdf->title2annots = true;
				//$mpdf->annotMargin = 12;
				$mpdf->use_embeddedfonts_1252 = true;	// false is default
				
				$html = _getFile('http://otwikiflash.net/action/userdets/sub/cpdhtml/username/'.$username.'/realname/'.urlencode($realname).'/year/'.$year);
				$mpdf->WriteHTML($html);				
				$mpdf->Output();
				exit;
			
			break;
				
			case 'cpdpdf':
				$key = 'HTML2PDF-KEY-GOES-HERE';
				
				$path = realpath('media/pdfs/').'/' ;
				$client = new SoapClient("http://www.htm2pdf.co.uk/htm2pdf.asmx?wsdl");
				$fp = fopen($path.$username.'.pdf', 'w');
				
				// The server method
/*				$html = $tpl->fetch('cpdform.tpl.php');
				fwrite($fp, $client->Htm2PdfDoc(array('html'=>$html,'key'=>$key))->Htm2PdfDocResult) ;
				fclose($fp);
*/				
				// The getURL method
				$url = 'http://otwikiflash.net/action/userdets/sub/cpdhtml/username/'.$username.'/realname/'.urlencode($realname).'/year/'.$year;
				fwrite($fp, $client->Url2PdfDoc(array('aUrl'=>$url,'key'=>$key))->Url2PdfDocResult) ;
				fclose($fp);
				
				_outputpdf($path.$username.'.pdf');
				die();
												
				//$credit = $client->NumberOfCredits(array('key'=>$key))->NumberOfCreditsResult ;
				//echo $credit ;

				/* attempt at html->pdf but crap
				require_once("libs/dompdf/dompdf_config.inc.php");
				
				$html = $tpl->fetch('cpdform.tpl.php');
				$dompdf = new DOMPDF();
				$dompdf->load_html($html);

				$dompdf->render();
				// The next call will store the entire PDF as a string in $pdf
				//file_put_contents("saved_pdf.pdf", $pdf);	
				$dompdf->render();
				$dompdf->stream("OTWikiFlash08-CPD.pdf", array("Attachment" => 0));
				die();
				*/
			break;
			
			case 'cpdpdf-temp':
				require_once('libs/fpdf/fpdf.php');
				require_once('libs/FPDI/fpdi.php');
				
				// initiate FPDI
				$pdf =& new FPDI();
				// add a page
				$pdf->AddPage();
				// set the sourcefile
				$pdf->setSourceFile('templates/WikiFlashCPD.pdf');
				// import page 1
				$tplIdx = $pdf->importPage(1);
				// use the imported page and place it at point 10,10 with a width of 100 mm
				$pdf->useTemplate($tplIdx);
				
				// now write some text above the imported page
				$pdf->SetFont('Arial');
				$pdf->SetTextColor(44,165,124);
				$pdf->SetXY(38, 125.5);
				$pdf->Write(0, $username);

				$pdf->Output('OTWikiFlash08-CPD.pdf', 'D');
				die();
			break;
			
			case 'display':
			default:
				$tpl->assign('action',$action);	
				$tmpl = (file_exists(realpath('templates/').'/'.$action.'.tpl.php')) ? $action.'.tpl.php' : 'home.tpl.php' ;
				$tpl->assign('include',$tmpl);	
				$tpl->display('frame.tpl.php');			
			break;
			
		}
	break;
	
	case 'rollcall':
		$sql = "SELECT total_edits, editsonwikiwk08, editsonwikiwk09, editsonwikiwk10, userid, realname FROM users order by editsonwikiwk09 DESC, total_edits DESC;";
		$r = q($sql);
		$tpl->assign('rollcall',$r);
		$tpl->assign('action','rollcall');
		$tpl->assign('include','rollcall.tpl.php');				
		$tpl->display('frame.tpl.php');
	break;
		
	case 'cronupdate':
		// Get the details & update table - NB: this ignores the year
		$sql = 'SELECT id, userid from users;';
		$r = q($sql);
		foreach ($r as $result){
			
			$username = $result['userid'];
			$url = 'http://en.wikipedia.org/w/api.php?action=query&uclimit=500&list=usercontribs&format=php&ucuser='.$username;
			$userdets = unserialize(_getFile($url));
			$userdets = $userdets['query']['usercontribs'];
			
			//Lets do some quick sums
			$totaledits = $totaltalks = $totalimage = $totalwikiweek08 = $totalwikiweek09 = $totalwikiweek10 = 0;
			 foreach ($userdets as $det){ 
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
				}
			 }					
				 
			$sql = "UPDATE users SET total_edits=".$totaledits.", talks='".$totaltalks."', edits='".$totaledits."', editsonwikiwk08='".$totalwikiweek08."', editsonwikiwk09='".$totalwikiweek09."', editsonwikiwk10='".$totalwikiweek10."' where userid='".$username."';";
			if (!q($sql)){ $errors[]= mysql_error(); };
			sleep(5);
		}

		@$tpl->assign('errors',$errors);	
		@$tpl->assign('messages',$messages);	
		$tpl->display('cron.tpl.php');
	break;
	
	case 'phpinfo':
		phpinfo();
		die();
	break;
	
	case 'contact':
			$submitted = (isset($_GET['sendit']) && !empty($_GET['sendit'])) ? true : false ;

			if ($submitted){
			require('libs/class.phpmailer.php');

			$_form['clientEmail'] = $_POST['clientEmail'];
			$_form['clientName']  = $_POST['clientName'];
			$_form['clientSubj']  = $_POST['clientSubject'];
			$_form['clientMsg']   = $_POST['clientMsg'];

			$tpl->assign('form',$_form);

			// Validation
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_form['clientEmail'])) {
			  $errors[] = '"'. $_form['clientEmail'].'" is an Invalid email address';
			}
				
			// Now check recaptcha
			require_once('libs/recaptchalib.php');
			$privatekey = "6LcQrgMAAAAAAGLqB1ECNtlqhybaNxRO73-cBgKT";
			$resp = recaptcha_check_answer ($privatekey,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) {
			  $errors[] = "The reCAPTCHA wasn't entered correctly. Go back and try it again.(reCAPTCHA said: " . $resp->error . ")";
			}
			
			if (empty($errors)) {	
				// Now get on			
				$mail = new PHPMailer();
				$mail->SetLanguage('en', "libs/language/");
				$mail->IsSMTP();    // send via SMTP

				$mail->From     = $_form['clientEmail'];
				$mail->FromName = $_form['clientName'];
				$mail->SMTPAuth = true;
				$mail->Mailer   = "smtp";
				$mail->Host     = "mail.metaot.com";
				$mail->Port 	= "26";
				$mail->Username = 'mailserver+metaot.com';
				$mail->Password = 'PASSWORD-FOR-MAILSERVER';
				$mail->Subject  = '[OTWikiFlash]'. $_form['clientSubj'];
				$mail->Body     = $tpl->fetch('email_admin_html.tpl.php');
				$mail->AltBody  =  $tpl->fetch('email_admin_text.tpl.php');
				$mail->AddAddress($data['email'], "OxResGp Organisers");


				if(!$mail->Send()) {
					$errors[] = 'There has been a mail error sending to ' . $_form['clientEmail'] ;
					$errors[] = '('.$mail->ErrorInfo.')';
					$urldstring = urlencode($mail->From).'&amp;subject='.urlencode($mail->Subject).'&amp;message='.urlencode($_form['clientMsg']);
					$errors[] = 'Please try and email the wikiflash organisers using your own email client sending a mail to <a href="mailto:'.$urldstring.'">'.$data['email'].'</a>';
				} else {		
					// Send a mail to the client - Use all the same variables bar the ones below
					$messages[] = 'Your message has been successfully sent to the WikiFlash Organisers. We hope to get back to you soon!';
					$mail->From     = $data['email'];
					$mail->FromName = "OTWikiFlash Organisers";
					$mail->Body     = $tpl->fetch('email_client_html.tpl.php');
					$mail->AltBody  =  $tpl->fetch('email_client_text.tpl.php');
					$mail->AddAddress($_form['clientEmail'], $_form['clientName']);
					if(!$mail->Send()){
						$errors[] = 'Sadly we couldnt send you a copy of the Email sent to the OTWikiFlash Organisers. ';
					} else {
						$messages[] = 'We have also successfully sent yourself a copy of the email for your records. Thankyou';
					}
				} 
			}
			
			}
			
			@$tpl->assign('errors',$errors);	
			@$tpl->assign('messages',$messages);	
			@$tpl->assign('form',$_form);
			$tpl->assign('action','contact');	
			$tpl->assign('include','contact.tpl.php');	
			$tpl->display('frame.tpl.php');
	break;

	default:
		$tpl->assign('action',$action);	
		$tmpl = (file_exists(realpath('templates/').'/'.$action.'.tpl.php')) ? $action.'.tpl.php' : 'home.tpl.php' ;
		$tpl->assign('include',$tmpl);	
		$tpl->display('frame.tpl.php');
	break;
}

function _tidyFormForPresentation($form,$postfix=''){
	$form['date_begin'.$postfix] = date('d M Y',$form['date_begin']);
	$form['date_end'.$postfix] = date('d M Y',$form['date_end']); 
	return $form;
}

function _getURLforPost($form){
	$dataurl = '';
	foreach($form as $key=>$val){
		$dataurl.=rawurlencode($key).'/'.rawurlencode($val).'/';
	}
	return $dataurl;
}

/*
	Array
	(
	    [query] => Array
	        (
	            [usercontribs] => Array
	                (
	                    [0] => Array
	                        (
	                            [user] => Willwade
	                            [pageid] => 19623950
	                            [revid] => 243245616
	                            [ns] => 1
	                            [title] => Talk:Therapeutic activity
	                            [timestamp] => 2008-10-05T19:44:09Z
	                            [comment] => email to rhaworth
	                        )

	                    [1] => Array
	                        (
	                            [user] => Willwade
	                            [pageid] => 19623950
	                            [revid] => 243240123
	                            [ns] => 1
	                            [title] => Talk:Therapeutic activity
	                            [timestamp] => 2008-10-05T19:20:47Z
	                            [comment] => adding comment about deleting page and wikiflash event
	                        )

*/
function _summariseWikiData($array){
	// pass back an array with userid,talks,edits,creations,firstedit
	$contribs = $array['query']['usercontribs'];
	usort($contribs, "_cmp");	
	$summary = array('total'=>sizeof($contribs));
	$summary['firstedit']=$contribs[0]['timestamp'];
	$summary['userid'] = $contribs[0]['user'];
	
}

function _cmp($a, $b)
{
		$tza = strtotime($a['timestamp']);
		$tzb = strtotime($b['timestamp']);
		
    if ($tza == $tzb) {
        return 0;
    }
    return ($tza < $tzb) ? -1 : 1;
}

function _outputpdf($file){
    if (file_exists($file)) {
		header("Cache-Control: private");
        header('Content-Description: File Transfer');		
		header("Content-type: application/pdf");
		header("Content-Length: " . filesize($file));
		header("Content-Disposition:attachment;filename='OTWikiFlash08CPDForm.pdf'");
		readfile($file);  
		ob_clean();
		flush();
		readfile($file);
		exit;
    }
}

function _getFile($url)
{
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$url);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Safari');
	$query = curl_exec($curl_handle);
	curl_close($curl_handle);
	
	return $query;
}
?>
