<?php if (!defined('APPLICATION')) exit();
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<?php
    echo '<link rel="stylesheet" type="text/css" href="',Asset('plugins/SocialLogin/views/setting_style.css'),'" />';
	echo '<script type="text/javascript" src="',Asset('plugins/SocialLogin/views/setting_js.js'),'" ></script>';
?>
<script type="text/javascript">
function MakeRequest()
{

$('#login_radius_response').html('<div id ="loading">Contacting API - please wait ...</div>');	
var connection_url = $('#connection_url').val();
var apikey = $('#Form_Apikey').val();
var apisecret = $('#Form_Secretkey').val();
if (apikey == '') {
$('#login_radius_response').html('<div id="Error" style="color:RED;">please enter api key</div>');
return false;
}
if (apisecret == '') {
$('#login_radius_response').html('<div id="Error" style="color:Red;">please enter api secret</div>');
return false;
}
if ($('#Form_Use_Api').is(':checked')) {
var api_request = 'curl';
}
else if ($('#Form_Use_Api1').is(':checked')) {
var api_request = 'fsockopen'; 
}
$.ajax({
type: "GET",
url: "<?php echo Asset('plugins/SocialLogin/views/checkapi.php') ?>",
data: "apikey=" + apikey +"&apisecret="+apisecret+"&api_request="+api_request,
success: function(msg){
$("#login_radius_response").html(msg);
}
});
}
</script>
<h1><?php echo $this->Data('Title'); ?></h1>
<?php 
$apikey = C('Plugins.SocialLogin.Apikey');
$apisecret = C('Plugins.SocialLogin.Secretkey');
if( empty($apikey) && empty($apisecret))  {
echo '<div title="warning" class= "lr_warning" style="background-color: #FFFFE0; border:1px solid #E6DB55; margin-bottom:5px; width: 900px;">To activate the Social Login, insert LoginRadius API Key and Secret in the Social Login section below. Social Sharing do not require API Key and Secret.</div>';
}
?>

 <div style="float:left;">
    <div id="header_block">
    <div class="header_block1">
        <?php echo '<h4>Thank you for installing the LoginRadius Vanilla Plugin!</h4>'; ?>
     <p>
        <?php echo 'To activate the extension, please configure it and manage the social networks from you LoginRadius account. If you do not have an account, click <a href="http://www.loginradius.com" target="_blank">here</a> and create one for FREE! '; ?> </p><p>
        <?php echo 'We also have Social Plugin for <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#wordpressplugin" target="_blank">WordPress</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#drupalmodule" target="_blank">Drupal</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#vbulletinplugin" target="_blank">vBulletin</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#joomlaextension" target="_blank">Joomla</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#magentoextension" target="_blank">Magento</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#osCommerceaddons" target="_blank">osCommerce</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#prestashopmodule" target="_blank">PrestaShop</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#xcartextension" target="_blank">X-Cart</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#zencartplugin" target="_blank">Zen-Cart</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#dotnetnukemodule" target="_blank">DotNetNuke</a> and <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#blogengineextension" target="_blank">BlogEngine</a>!'; ?> 
      	<a href="http://www.loginradius.com/" target="_blank"><input name="<?php echo 'Set up my FREE account!'; ?>" type="button" class="Button SliceSubmit" value="<?php echo 'Set up my FREE account!'; ?>" style="margin:10px;"></a>
</p>
</div>
<div class="header_block2">
	<h4 style="border-bottom: #d7d7d7 1px solid;"><?php echo T('Help and Documentations'); ?></h4>
	<ul style="float: left;margin-right: 43px;">
  <li><a href="http://support.loginradius.com/customer/portal/articles/638461-how-do-i-implement-social-login-on-vanilla-2" target="_blank"><?php echo 'Plugin Installation, Configuration and Troubleshooting'; ?></a></li>
  <li><a href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret" target="_blank"><?php echo 'How to get LoginRadius API Key and Secret'; ?></a></li>
	<li><a href="http://community.loginradius.com/" target="_blank"><?php echo 'Discussion Forum'; ?></a></li></ul><ul style="float: left;margin-right: 43px;">
	<li><a href="https://www.loginradius.com/Loginradius/About" target="_blank"><?php echo 'About LoginRadius'; ?></a></li>
	<li><a href="https://www.loginradius.com/product/sociallogin" target="_blank"><?php echo 'LoginRadius Products'; ?></a></li>
	<li><a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms" target="_blank"><?php echo 'Social Plugins'; ?></a></li></ul><ul style="float: left;">
	<li><a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-sdks" target="_blank"><?php echo 'Social SDKs'; ?></a></li>
	<li><a href="https://www.loginradius.com/loginradius/Testimonials" target="_blank"><?php echo 'Testimonials'; ?></a></li>
</ul>
</div>
</div>
<div id= "header_block_right">
<div class="header_block_right1">
	<h4 style="border-bottom: #d7d7d7 1px solid;"><?php echo T('Stay Updated!');?></h4>
<p>
<?php echo T('To receive updates on new features, releases, etc, please connect to one of our social media pages.'); ?> <br>
<iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px; height: 70px;" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&send=false&layout=box_count&width=90&show_faces=false&action=like&colorscheme=light&font=arial&height=90" data-original-title="Like us on Facebook"></iframe>
</p>
</div>
<div class="header_block_right2" style="height: 115px;">
<h4 style="border-bottom: #d7d7d7 1px solid;"><?php echo 'Support Us'; ?></h4>
<p>
<?php echo T('If you liked our FREE open-source plugin, please send your feedback/testimonial to <a href="mailto:feedback@loginradius.com">feedback@loginradius.com</a>! '); ?></p>
</div>


</div>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>

<div id='menu_tabs'> 
<ul> 
<li style="float:left"><a href="#tab-1">API Settings</a></li> 
<li style="float:left"><a href="#tab-2">Social Login</a></li> 
<li style="float:left"><a href="#tab-3">Social Sharing</a></li> 
</ul> 
</div> 
</div>
<div>
    <div class="container" id="tab-1">
<div class="contentblock">
	<h4><label>To activate the plugin, insert the LoginRadius API Key & Secret</label></h4>
	                               
	<div><label style="float:left; width:100px; margin:18px 0 0 15px; font-weight:bold">API Key</label>
		 <?php echo $this->Form->TextBox('Apikey',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px; margin: 15px 0 0 10px' 
		     ));?>	</div>
			 <div>
	<label style="float:left; width:100px; margin:18px 0 0 15px; font-weight:bold">API Secret</label>
		 <?php echo $this->Form->TextBox('Secretkey',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px; margin: 15px 0 10px 10px' 
		     ));
            ?></div></div>
			<div class="contentblock"  style="height: 160px;">
            <h4><label>What API Connection Method do you prefer to use to enable API communication?</label></h4><div style="margin-top: 15px;">
	     <?php $this->connection_method= array(
        'CURL' => T('Use cURL (Recommended API connection method but sometimes this is disabled at hosting server.)'.'<br/>'),
        'Fsockopen' => T('Use FSOCKOPEN (Choose this option, if cURL is disabled at your hosting server.)'.'<br>'),
		);
		echo $this->Form->RadioList('Use_Api', $this->connection_method, array('default' => 'CURL','style'=> 'margin:3px 2px 0 6px;'));?>
      <input class="SmallButton" type="button" style="font-weight:bold;float:left;margin-top:20px;" id="login_radius_detect_api" value="Verify API Settings" onclick="MakeRequest();" /> <div id="login_radius_response" style="margin: 0px 0 14px 25px;"></div></div>
</div></div></div>


<div class="container" id="tab-2">
<div class="contentblock">
<h4><label>Social Login Interface settings</label></h4>
<div><h4><label>Do you want to enable Social Login for your website?</label></h4></div><div>
 <?php $this->Enable_sociallogin= array( 'Yes' => T('Yes'),'No' => T('No'));
echo $this->Form->RadioList('Enablesociallogin', $this->Enable_sociallogin, array('style'=> 'margin:3px 2px 10px 10px;'));?> 
</div>
<div class="lrborder"></div>
<div><h4><label>What text should be displayed above the Social Login interface? Leave blank if you do not want any text to be displayed</label></h4></div>
<?php $Sociallogintitle=$this->Form->GetFormValue('Sociallogintitle');

			 echo $this->Form->TextBox('Sociallogintitle', array( 
		        'maxlength' => 255,
		        'value' =>$Sociallogintitle, 
		        'style' => 'width: 280px; margin: 8px 0 10px 21px;' 
		     ) 
		  );
?>
<br />
</div>
<div class="contentblock">
<h4><label>Social Login Interface Customization</label></h4>
<div><h4><label>Select the icon size to use in the Social Login interface</label></h4></div><div>
 <?php $this->Enable_socialicon= array( 'small' => T('Small'),'medium' => T('Medium'));
echo $this->Form->RadioList('Enablesocialicon', $this->Enable_socialicon, array('style'=> 'margin:3px 2px 10px 10px;'));?> 
</div>
<div class="lrborder"></div>
<div><h4><label>How many social icons would you like to be displayed per row?</label></h4></div>
<?php $Sociallogincolumns=$this->Form->GetFormValue('Sociallogincolumns');
			 echo $this->Form->TextBox('Sociallogincolumns', array( 
		        'maxlength' => 255,
		        'value' =>$Sociallogincolumns, 
		        'style' => 'width: 115px; margin: 8px 0 10px 21px;' 
		     ) 
		  );
?>
<br />
<div class="lrborder"></div>
<div><h4><label>What background color would you like to use for the Social Login interface? <a title="<?php echo 'Leave empty for transparent. You can enter hexa-decimal code of the color as well as name of the color.';?>" href="javascript:void(0)" style="text-decoration:none"> (?)</a></label></h4></div>
<?php $Socialloginbackground=$this->Form->GetFormValue('Socialloginbackground');
			 echo $this->Form->TextBox('Socialloginbackground', array( 
		        'maxlength' => 255,
		        'value' =>$Socialloginbackground, 
		        'style' => 'width: 280px; margin: 8px 0 10px 21px;' 
		     ) 
		  );
?>
<br />
</div>
<div class="contentblock">
<h4><label>User Email Settings</label></h4>
<div><h4><label>Do you want to send emails to users with their email and password after registration?</label></h4></div>
<?php $this->welcome_email= array(
        'Yes' => T('YES, send an email to users after registration<br/>'),
        'No' => T('NO, do not send email to users after registration')
		);
		echo $this->Form->RadioList('lrwelcomeemail', $this->welcome_email, array('default' => 'Yes','style'=> 'margin:3px 2px 0 18px;'));
?>
<div class="lrborder"></div>
<div><h4><label>What text should be displayed above the Email PopUp?</label></h4></div>
<?php $Emailtitle=$this->Form->GetFormValue('Emailtitle');
			 echo $this->Form->TextBox('Emailtitle', array( 
		        'maxlength' => 255,
		        'value' =>'Please enter your email address to proceed', 
		        'style' => 'width: 280px; margin: 8px 0 10px 21px;' 
		     ) 
		  );
?>
<div class="lrborder"></div>
<div><h4><label>What text should be displayed when user enters incorrect email?</label></h4></div>
<?php $Emailerrortitle=$this->Form->GetFormValue('Emailerrortitle');
			 echo $this->Form->TextBox('Emailerrortitle', array( 
		        'maxlength' => 255,
		        'value' =>'Please enter your correct email address to proceed', 
		        'style' => 'width: 280px; margin: 8px 0 10px 21px;' 
		     ) 
		  );
?>
<div class="lrborder"></div>
<div><h4><label>A few ID Providers do not supply users' e-mail IDs as part of the user profile data they provide. Do you want to prompt users to provide their email IDs before completing registration process if it is not provided by the ID Provider?</label></h4></div>
<div>
<?php $this->Email_required= array(
        'Yes' => T('YES, ask users to enter their email address in a pop-up<br/>'),
        'No' => T('NO, just auto-generate random email IDs for users')
		);
		echo $this->Form->RadioList('EmailRequired', $this->Email_required, array('default' => 'Yes','style'=> 'margin:3px 2px 0 10px;'));
		?> 
 </div>
 <div class="lrborder"></div>
 <div><h4><label>Do you want users to skip email verification when logging in from social ID providers like Facebook, Google, Yahoo, etc. and email is already provided by the ID provider?</label></h4></div>
 <div>
<?php $this->Email_skip= array(
        'Yes' => T('YES'),
        'No' => T('NO') 
		);
		echo $this->Form->RadioList('SkipEmail', $this->Email_skip, array('default' => 'No','style'=> 'margin:3px 2px 16px 10px;'));
		?> 
 </div>
</div>
<div class="contentblock">
<h4><label>Redirection Settings</label></h4>
<div><h4><label>Where do you want to redirect your users after successfully logging in?</label></h4></div>
<div>
 <?php
	    $this->Login_Redirect= array(
        'Loginredirect1' => T('Redirect to Same page where the user logged in <strong>(Default)</strong></br>'),
        'Loginredirect2' => T('Redirect to account of user </br>'),
		'Loginredirect4' => T('Redirect to home page</br> '),
		'Loginredirect3' => T('Redirect to Custom URL</br> '),
		
		);					  
		echo $this->Form->RadioList('Loginredirect', $this->Login_Redirect, array('default' => 'Loginredirect1','style'=> 'margin:3px 2px 0 10px;'));
		$Loginredirecturl=$this->Form->GetFormValue('Loginredirecturl');
        echo $this->Form->TextBox('Loginredirecturl', array( 
		        'maxlength' => 255,
		        'value' => $Loginredirecturl, 
		        'style' => 'width: 280px; margin: 8px 0 8px 21px;')
				);
		?>

</div>
<div class="lrborder"></div>
<div><h4><label>Do you want to automatically link your existing users' accounts to their social accounts?</label></h4></div>
<div><div> <?php $this->Account_linking= array(
        'Yes' => T('Yes'),
        'No' => T('No'),
		);
		echo $this->Form->RadioList('Accountlinking', $this->Account_linking, array('default' => 'Yes','style'=> 'margin:3px 2px 0 10px;'));        ?> </div></div>
		<div class="lrborder"></div>
<div><h4><label>What text should be displayed above the mapping interface? Leave blank if you do not want any text to be displayed</label></h4></div>
<?php $Mappingtitle=$this->Form->GetFormValue('Mappingtitle');
			 echo $this->Form->TextBox('Mappingtitle', array( 
		        'maxlength' => 255,
		        'value' =>$Mappingtitle, 
		        'style' => 'width: 280px; margin: 8px 0 8px 21px;' 
		     ) 
		  );
?>
</div>
<div class="contentblock">
<h4><label>User Profile Data Option</label></h4>
<div><h4><label>Do you want to update User Profile Data in your Vanilla database, every time user logs into your website?<a title="<?php echo 'If you disable this option, user profile data will be saved only once when user logs in first time at your website, user profile details will not be updated in your Vanilla database, even if user changes his/her social account details.';?>" href="javascript:void(0)" style="text-decoration:none"> (?)</a></label></h4></div>
<div> <?php $this->profile_option= array('Yes' => T('Yes'),'No' => T('No'));
echo $this->Form->RadioList('updateprofile', $this->profile_option, array('default' => 'Yes','style'=> 'margin:3px 2px 16px 10px;')); ?> </div>
</div>
</div>
<div class="container" id="tab-3">
<div class="contentblock">
<script type="text/javascript">
jQuery(document).ready(function() { 
var choosesharing= "<?php echo C('Plugins.SocialShare.Horizontalsharingtheme') ?>";
var chooseverticalsharing= "<?php echo C('Plugins.SocialShare.verticalsharingtheme') ?>";

if(choosesharing  == '' || choosesharing =='horizonSharing32' || choosesharing == 'horizonSharing16') {
displaysharing_horizontal(); 
}
else if(choosesharing =='single-image-theme-large' || choosesharing == 'single-image-theme-small') {
hiddensharing_horizontal();
}

else if(choosesharing =='hybrid-horizontal-horizontal' || choosesharing == 'hybrid-horizontal-vertical') {
displaycounter_horizontal();
}
if(chooseverticalsharing=='16VerticlewithBox' || chooseverticalsharing == '32VerticlewithBox') {
displayvertical_sharing();
}

else if(chooseverticalsharing=='hybrid-verticle-horizontal' || chooseverticalsharing == 'hybrid-verticle-vertical') {
displayvertical_counter();
}
if(choosesharing == '' || choosesharing == 'horizonSharing32' || choosesharing == 'horizonSharing16' || choosesharing == 'single-image-theme-large' || choosesharing == 'single-image-theme-small' || choosesharing == 'hybrid-horizontal-horizontal' ||  choosesharing == 'hybrid-horizontal-vertical'){
Makehorivisible();
}
else {
Makevertivisible();
}

});
</script>
<h4><label>Social Share Settings</label></h4>
<div><h4><label>Do you want to enable Social Sharing for your website?</label></h4></div>
 <?php $this->Enable_socialsharing= array(
        'Yes' => T('Yes'),
        'No' => T('No')
		);
echo $this->Form->RadioList('Enablesocialsharing', $this->Enable_socialsharing, array('style'=> 'margin:3px 2px 0 17px;'));?> 
</div>
<div class="contentblock">
<h4><label>Social Sharing Theme selection</label></h4>
<div><h4><label>What Social Sharing widget theme do you want to use across your website?</label></h4></div><br/>
<?php $choosesharing=trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
$chooseverticalsharing=trim(C('Plugins.SocialShare.verticalsharingtheme'));
?>
	<a id="mymodal1" href="javascript:void(0);" onClick="Makehorivisible();" style="color: <?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small' || $choosesharing == 'hybrid-horizontal-vertical'|| $choosesharing == 'hybrid-horizontal-horizontal'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;margin: 10px 0 0 30px;"><?php echo 'Horizontal'; ?></a> &nbsp;|&nbsp;
	     <a id="mymodal2" href="javascript:void(0);" onClick="Makevertivisible();" style="color: <?php if($chooseverticalsharing == '16VerticlewithBox' || $chooseverticalsharing == '32VerticlewithBox' || $chooseverticalsharing =='hybrid-verticle-horizontal' || $chooseverticalsharing == 'hybrid-verticle-vertical'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;"><?php echo 'Vertical'; ?></a>
		 <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin: 9px 12px 10px 12px;">
		  <span id = "arrow" style=" height: 11px;margin: -21px 0 0 40px;position: absolute;width: 21px;
 <?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small' || $choosesharing == 'hybrid-horizontal-vertical'|| $choosesharing == 'hybrid-horizontal-horizontal'){echo '2px';} else{ echo '90px';}?>;"></span>
		 <div id="horizontal_sharing">
		 <div><label style="font-weight: bold;">Do you want to enable Horizontal Social Sharing for your website?</label></div>
 <?php $this->Enable_horisocialsharing= array(
        'Yes' => T('Yes'),
        'No' => T('No')
		);
echo $this->Form->RadioList('Enablehorizontalsharing', $this->Enable_horisocialsharing, array('style'=> 'margin:3px 2px 0 -2px;'));?> 
<div class="lrborder"></div> 
</div>
<div id="vertical_sharing">
<div><label style="font-weight: bold;">Do you want to enable Vertical Social Sharing for your website?</label></div>
 <?php $this->Enable_verticalsharing= array('Yes' => T('Yes'),'No' => T('No'));
echo $this->Form->RadioList('Enableverticalsharing', $this->Enable_verticalsharing, array('style'=> 'margin:3px 2px 0 -2px;'));?> 
<div class="lrborder"></div> 
</div>
<div><label style="font-weight: bold;">Choose a Sharing theme</label></div><br/>
	     <div id="sharehorizontal" style="display:<?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small'){echo 'Block';} else{ echo 'none';}?>">
	      <?php $this->Horizontal_sharingtheme= array(
        'horizonSharing32' => T(Img('/plugins/SocialLogin/views/images/socialshare/horizonSharing32.png').'<br>'),
        'horizonSharing16' => T(Img('/plugins/SocialLogin/views/images/socialshare/horizonSharing16.png').'<br>'),
		'single-image-theme-large' => T(Img('/plugins/SocialLogin/views/images/socialshare/single-image-theme-large.png').'<br>'),
		'single-image-theme-small' => T(Img('/plugins/SocialLogin/views/images/socialshare/single-image-theme-small.png').'<br>'),
		'hybrid-horizontal-horizontal' => T(Img('/plugins/SocialLogin/views/images/socialshare/hybrid-horizontal-horizontal.png').'<br>'),
		'hybrid-horizontal-vertical' => T(Img('/plugins/SocialLogin/views/images/socialshare/hybrid-horizontal-vertical.png').'<br>')
		);
echo $this->Form->RadioList('Horizontalsharingtheme', $this->Horizontal_sharingtheme, array('default' => 'horizonSharing32','style'=> 'vertical-align: super;'));?><br />
  </div>  
  <div id="sharevertical" style="display:<?php if($chooseverticalsharing == '16VerticlewithBox' || $chooseverticalsharing == '32VerticlewithBox' || $chooseverticalsharing == 'hybrid-verticle-horizontal' || $chooseverticalsharing == 'hybrid-verticle-vertical'){echo 'Block';} else{ echo 'none';}?>;">
         <?php $this->Horizontal_sharingtheme= array(
        '16VerticlewithBox' => T(Img('/plugins/SocialLogin/views/images/socialshare/16VerticlewithBox.png', array('style'=> 'vertical-align:top;'))),
        '32VerticlewithBox' => T(Img('/plugins/SocialLogin/views/images/socialshare/32VerticlewithBox.png', array('style'=> 'vertical-align:top;'))),
		'hybrid-verticle-horizontal' => T(Img('/plugins/SocialLogin/views/images/socialshare/hybrid-verticle-horizontal.png', array('style'=> 'vertical-align:top;'))),
		'hybrid-verticle-vertical' => T(Img('/plugins/SocialLogin/views/images/socialshare/hybrid-verticle-vertical.png', array('style'=> 'vertical-align:top;'))),
		
		);
echo $this->Form->RadioList('verticalsharingtheme', $this->Horizontal_sharingtheme, array('style'=> 'vertical-align:top;margin:3px 2px 0 6px;'));?><br /><br />
<div style="overflow:auto; background:#EBEBEB; padding-bottom:10px;">
<h4><label><?php echo 'Select the position of Social Sharing widget'; ?></label></h4>
<?php $this->Vertical_sharingposition= array(
        'topleft' => T('Top Left<br>'),
        'topright' => T('Top Right<br>'),
		'bottomleft' => T('Bottom Left<br>'),
		'bottomright' => T('Bottom Right<br>')
		);
echo $this->Form->RadioList('Verticalsharingposition', $this->Vertical_sharingposition, array('default' => 'topleft', 'style'=> 'margin:0px 0 0 20px;'));?>      
<h4><label><?php echo 'Specify distance of vertical sharing interface from top. (Leave empty for default behaviour)';?><a title="<?php echo 'Enter a number (For example - 200). It will set the \'top\' CSS attribute of the interface to the value specified. Increase in the number pushes interface towards bottom.';?>" href="javascript:void(0)" style="text-decoration:none"> (?)</a></label></h4>
<?php $Sharingoffset=$this->Form->GetFormValue('Sharingoffset');
			 echo $this->Form->TextBox('Sharingoffset', array( 
		        'maxlength' => 255,
		        'value' =>$Sharingoffset, 
		        'style' => 'width: 280px; margin: 0px 0 0 18px;' 
		     ) 
		  );
?>         
</div></div>    
</div>
<?php $rearrange=C('Plugins.SocialShare.loginRadiusLIrearrange'); 
?>
<div id ="rearrangedefaulttext"><h4><label><?php echo T('What sharing network order do you prefer for your sharing widget?');?></h4></label></div>
<div id ="rearrangedefault">
<ul onMouseOver="lr_sortable();" id="sortable" class="ui-sortable" style="margin: 0px 0px 0px 40px; padding: 0px;">
	<?php 
	if (empty($rearrange)) {
		$rearrange = array('Facebook' => 'Facebook','Pinterest' => 'Pinterest','GooglePlus' => 'GooglePlus','Twitter' => 'Twitter','LinkedIn' => 'LinkedIn');
    }					
	foreach($rearrange  as $provider){
	?>
   <li title="<?php echo $provider ?>" id="loginRadiusLIcheckrearrange<?php echo strtolower($provider) ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($provider) ?>">
	<input type="hidden" name="rearrange_settings[]" value="<?php echo $provider ?>" />
	</li>
	<?php
  }
?>
</ul>
<div class="lrborder"></div>
<ul  id="counter_sortable" style="display:none;">
	<?php 
	$counter_rearrange=C('Plugins.SocialShare.loginRadiuscounter');
	if (empty($counter_rearrange)) {
		$counter_rearrange = array('Facebook Like', 'Google+ Share','Hybridshare' ,'Pinterest Pin it','Twitter Tweet');
	}						
	foreach($counter_rearrange  as $provider){
	?>
	<input type="hidden" name="counter_rearrange_settings[]" id= "loginRadiuscounter<?php echo strtolower($provider) ?>" value="<?php echo $provider ?>" /><?php
	}
?>
</ul>
</div>
<div id="loginRadiusSharingLimit" style="color: red; display: none; margin: 10px 0 0 20px;"><?php echo 'You can select only 9 providers.'; ?></div>
<div id="shareprovider">
<div><h4><label>What Sharing Networks do you want to show in the sharing widget? (All other sharing networks will be shown as part of LoginRadius sharing icon)</label></h4></div>
<div id="socialshare_providers_list" style="margin-left:8px;margin-bottom:10px;"></div>
<div id="socialcounter_providers_list" style="margin-left:8px;margin-bottom:10px;"></div>
</div>
<div id ="verticalrearrangedefaulttext"><h4><label><?php echo T('What sharing network order do you prefer for your sharing widget?');?></h4></label></div>
<div id ="verticalrearrangedefault">
<ul onMouseOver="verticallr_sortable();" id="vertical_sortable" class="ui-sortable" style="margin: 0px 0px 0px 40px; padding: 0px;">
	<?php 
	 $vertical_rearrange=C('Plugins.SocialShare.loginRadiusLIverticalrearrange'); 
	if (empty($vertical_rearrange)) {
		$vertical_rearrange = array('Facebook' => 'Facebook','Pinterest' => 'Pinterest','GooglePlus' => 'GooglePlus','Twitter' => 'Twitter','LinkedIn' => 'LinkedIn');
    }					
	foreach($vertical_rearrange  as $provider){
	?>
   <li title="<?php echo $provider ?>" id="loginRadiusLIverticalrearrange<?php echo strtolower($provider) ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($provider) ?>">
	<input type="hidden" name="vertical_rearrange_settings[]" value="<?php echo $provider ?>" />
	</li>
	<?php
  }
?>
</ul>
<div class="lrborder"></div>
<ul  id="vertical_counter_sortable" style="display:none;">
	<?php 
	$vertical_counter_rearrange=C('Plugins.SocialShare.loginRadiusverticalcounter');
	if (empty($vertical_counter_rearrange)) {
		$vertical_counter_rearrange = array('Facebook Like', 'Google+ Share','Hybridshare' ,'Pinterest Pin it','Twitter Tweet');
	}						
	foreach($vertical_counter_rearrange  as $provider){
	?>
	<input type="hidden" name="vertical_counter_rearrange_settings[]" id= "loginRadiusverticalcounter<?php echo strtolower($provider) ?>" value="<?php echo $provider ?>" /><?php
	}
?>
</ul>
</div>
<div id="loginRadiusverticalSharingLimit" style="color: red; display: none; margin: 10px 0 0 20px;"><?php echo 'You can select only 9 providers.'; ?></div>
<div id="verticalshareprovider">
<div><h4><label>What Sharing Networks do you want to show in the sharing widget? (All other sharing networks will be shown as part of LoginRadius sharing icon)</label></h4></div>
<div id="socialshare_verticalproviders_list" style="margin-left:8px;margin-bottom:10px;"></div>
<div id="socialcounter_verticalproviders_list" style="margin-left:8px;margin-bottom:10px;"></div>

</div>
</div>
</div>
<div style="width:100%;float:right; margin:20px 0 0 0;"><div> <?php echo $this->Form->Button('Save Changes', array('class' => 'Button SliceSubmit')); ?></div></div>
