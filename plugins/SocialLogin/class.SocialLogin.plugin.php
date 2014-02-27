<?php if (!defined('APPLICATION')) exit();
session_start();
// Define the plugin:
$PluginInfo['SocialLogin'] = array(
	'Name' => 'Social Login',
	'Description' => 'Let your users log in and comment via their accounts with popular ID providers such as Facebook, Google, Twitter, Yahoo, Vkontakte and over 21 more!.',
	'Version' => '2.1',
	'RequiredTheme' => FALSE,
	'RequiredPlugins' => FALSE,
	'MobileFriendly' => TRUE,
	'SettingsUrl' => '/dashboard/settings/SocialLogin',
	'SettingsPermission' => 'Garden.Settings.Manage',
	'HasLocale' => TRUE,
	'RegisterPermissions' => FALSE,
	'Author' => "LoginRadius Team",
	'AuthorEmail' => 'developers@loginradius.com',
	'AuthorUrl' => 'http://www.LoginRadius.com'
);	
require_once("LoginRadius.php");
class SocialLoginPlugin extends Gdn_Plugin {
	/**
	* Add Social Login interface at entry controller Popup.
	*/
	public function EntryController_SignIn_Handler($Sender, $Args) {
		$Sender->AddCssFile('plugins/SocialLogin/style.css');
		$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$Secret = trim(C('Plugins.SocialLogin.Secretkey')); 
		$subtitle= trim(C('Plugins.SocialLogin.Sociallogintitle'));
		$subtitle=!empty($subtitle) ? $subtitle :'';
	    if (!empty($Secret) && !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
			$SignInHtml = "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact 
LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
		}
		else {
			$SignInHtml='';
			if(C('Plugins.SocialLogin.Enablesociallogin') == 'Yes') {
				$jsfiles='<script>$(function(){ loginradius_interface();});</script>';
				$SignInHtml='<h4 style="margin:8px; color:#1e79a7; font-weight: bold;">'.$subtitle.'</h4><br/>' .$jsfiles.
							'<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
			}
		}
		$SocialLoginMethod = array(
			'Name' => 'SocialLogin',
			'SignInHtml' => $SignInHtml
		);
		$Sender->Data['Methods'][] = $SocialLoginMethod;
	}
	/*
	* Call Interface and Sharing Script inside Head tag.
	*/
	public function Base_Render_Before($Sender){
		$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$http = ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://" );
		if (isset($_SERVER['REQUEST_URI'])) {
		 	$loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
		}
		else {
			$loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']);
		}
		$interfaceiconsize = (C('Plugins.SocialLogin.Enablesocialicon') == "small" ? "small" : "");
		$interfacebackgroundcolor=C('Plugins.SocialLogin.Socialloginbackground');
		$interfacebackgroundcolor = (!empty($interfacebackgroundcolor) ? trim($interfacebackgroundcolor) : "");
		$interfacerow = C('Plugins.SocialLogin.Sociallogincolumns');
		$interfacerow = (!empty($interfacerow) && is_numeric($interfacerow)? trim($interfacerow) : 0);
		$loginradius_interfacescript='<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> 
		function loginradius_interface() { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "' . $interfaceiconsize . '";$ui.lrinterfacebackground="' . $interfacebackgroundcolor . '";$ui.noofcolumns=' . $interfacerow . ';$ui.apikey = "'.$AppID.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv";  LoginRadius_SocialLogin.init(options); } var options={};  options.login=true;  LoginRadius_SocialLogin.util.ready(loginradius_interface); </script>';
		if(C('Plugins.SocialLogin.Enablesociallogin') == 'Yes') {
			$Sender->Head->AddString($loginradius_interfacescript);
		}
		if(C('Plugins.SocialShare.Enablesocialsharing') == 'Yes') {
			$share_script=$this->loginradius_sharescript();
			$Sender->Head->AddString($share_script);
		}
	}
    /*
	* Before Log-in into website, show LoginRadius Interface.
	*/		
	public function Base_BeforeSignInButton_Handler($Sender, $Args) {
		$UserModel = new UserModel();
		unset($_SESSION['lrdata_store']);
		$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$Secret = trim(C('Plugins.SocialLogin.Secretkey'));
		$subtitle= trim(C('Plugins.SocialLogin.Sociallogintitle'));
		$EmailRequired = C('Plugins.SocialLogin.Email_required');
		$Account_linking= c('Plugins.SocialLogin.Account_linking');
		$subtitle= !empty($subtitle) ? $subtitle :'';
		echo '<h4 style="margin:0 0 5px 0;">'.$subtitle.'</h4>';
		if (!empty($Secret) && !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
			echo "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact 
		LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
		}
		else { 
			if(C('Plugins.SocialLogin.Enablesociallogin') == 'Yes') {
				echo '<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
			}
		}
		/*
		Get the User Profile Data. and defines the roles of user. 
		*/
		$obj = new LoginRadius();
		$userprofile = $obj->loginradius_get_data($Secret);
		$lrdata=array(); 
		if ($obj->IsAuthenticated == TRUE) {
			$lrdata['FullName'] =  (!empty($userprofile->FullName) ? $userprofile->FullName : "");
			$lrdata['ProfileName'] = (!empty($userprofile->ProfileName) ? $userprofile->ProfileName :"");
			$lrdata['fname']  = (!empty($userprofile->FirstName) ? $userprofile->FirstName : ""); 
			$lrdata['lname']  = (!empty($userprofile->LastName) ? $userprofile->LastName : "");
			$lrdata['id'] = (!empty($userprofile->ID) ? $userprofile->ID :"");
			$lrdata['NickName'] =(!empty($userprofile->NickName) ? $userprofile->NickName:"");
			$lrdata['Provider']    = (!empty($userprofile->Provider) ? $userprofile->Provider :"");
			for ($i=0; $i<sizeof($userprofile->Email); $i++) {
				if ($userprofile->Email[$i]->Type == 'Primary') {
					$lrdata['email'] = (!empty($userprofile->Email[$i]->Value ) ? $userprofile->Email[$i]->Value  : '');
					break;
				}
			 }
			$lrdata['email']  = (!empty($lrdata['email'] )?$lrdata['email'] :"");
			$lrdata['aboutme'] =(!empty($userprofile->About)?$userprofile->About:"");
			$website = (!empty($userprofile->ProfileUrl)?$userprofile->ProfileUrl:"");
			$lrdata['thumbnail']  =(!empty($userprofile->ThumbnailImageUrl) ? trim($userprofile->ThumbnailImageUrl):"");
			$lrdata['dob']  = (!empty($userprofile->BirthDate)? $userprofile->BirthDate :"");	
		    $lrdata['gender']  = (!empty($userprofile->Gender)? (($userprofile->Gender == 'F') ? "F" : "m" ) : "m");
			$lrdata['roles']= (C('Garden.Registration.Method'));
			if($lrdata['roles']=='Approval')
				$lrdata['rolevalue']=4;
			if (empty($lrdata['email']) && $EmailRequired == "No") {
				switch(  $lrdata['Provider']  ){
				case 'twitter':
					$lrdata['email'] = $lrdata['id'].'@'.$lrdata['Provider'] .'.com';
				break;
				default:
					$Email_id = substr($lrdata['id'],7);
					$Email_id2 = str_replace("/","_",$Email_id);
					$lrdata['email'] = str_replace(".","_",$Email_id2).'@'.$lrdata['Provider'].'.com';
				break;
				}
			}
			/*
			check the Provider id present in UserAuthentication table.
			*/
			$UserDataCheck = Gdn::SQL()
				->Select('UserID')
				->From('UserAuthentication')
				->Where('ForeignUserKey',$lrdata['id'])
				->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($UserDataCheck as $UpdateUser) {
				$UserID = GetValue('UserID', $UpdateUser);
			}
			/*
			check retrieved User id is also present in User Table.
			*/
			$UserDataAuth = Gdn::SQL()
				->Select('UserID')
				->From('User')
				->Where('UserID', $UserID)
				->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($UserDataAuth as $AuthUser) {
				$UserID = GetValue('UserID', $AuthUser);
			}
			/*
			when email not empty,then check the user is present in User table.
			*/
			if(!empty($lrdata['email'])) {	
				$UserDataCheckEmail = Gdn::SQL()
					->Select('UserID')
					->From('User')
					->Where('Email', $lrdata['email'])
					->Get()->Result(DATASET_TYPE_ARRAY);
				foreach ($UserDataCheckEmail as $UpdateUser) {
					$UserIDEmail = GetValue('UserID', $UpdateUser);
				}
			}
			/*
			when userId present then provide Login.
			*/
			if (!empty($UserID)) {
				Gdn::Session()->Start($UserID);
				if(C('Plugins.SocialLogin.updateprofile') =='Yes') {
					$this->updateprofile($lrdata, $UserID);
				}
				$_SESSION['lrdata_store']=$lrdata['id'];
				$loc=$this->RedirectUrl();
				Redirect($loc);
			}
			/*
			Auto Linking, user can link their traditional account to social login account.
			*/
			$Link_UserID=Gdn::SQL()
				->Select('UserID')
				->From('UserAuthentication')
				->Where('UserID',$UserIDEmail)
				->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($link_UserID as $UpdateUser) {
				$Check_Link_UserID = GetValue('UserID', $UpdateUser);
			}
			if (!empty($UserIDEmail) && empty($UserID) && $Account_linking=="Yes" && empty($Check_Link_UserID))  {
				$DataAuth1 = array(
				'ForeignUserKey'=>$lrdata['id'],
				'ProviderKey'=>$lrdata['Provider'],
				'UserID'=>	$UserIDEmail	
				);
				Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $DataAuth1);	
				Gdn::Session()->Start($UserIDEmail);
				if(C('Plugins.SocialLogin.updateprofile') =='Yes') {
					$this->updateprofile($lrdata , $UserIDEmail);
				}
				$_SESSION['lrdata_store']=$lrdata['id'];
				$loc=$this->RedirectUrl();
				Redirect($loc);
			}
			else if(!empty($UserIDEmail) && empty($UserID)) {
				Gdn::Session()->Start($UserIDEmail);
				if(C('Plugins.SocialLogin.updateprofile') =='Yes') {
					$this->updateprofile($lrdata , $UserIDEmail);
				}
				$_SESSION['lrdata_store']=$lrdata['id'];
				$loc=$this->RedirectUrl();
				Redirect($loc);
			}
			/*
			* popup box open when email required and emailis empty.
			*/
			if (empty( $lrdata['email']) && $EmailRequired == "Yes" && empty($UserID)) {
				$EmailTitle=trim(C('Plugins.SocialLogin.Emailtitle'));
				$EmailTitle=!empty($EmailTitle)? $EmailTitle :'';
				$_SESSION['lrdata']=$lrdata;
				$msg=$EmailTitle;
				$showinvitation="no";
				if($lrdata['roles']=='Invitation'){
					$showinvitation="yes";
					$msg='Please enter Invitation code:';
				}
				$this->popup($msg, $showinvitation) ;
			}
			if($lrdata['roles']=='Invitation') {
				$_SESSION['lrdata']=$lrdata;
				$showinvitation="yes";
				$msg='Please enter Invitation code:';
				$this->popup($msg, $showinvitation) ;
				return false;
			}
		}
		if (isset($_REQUEST['LoginRadiusEmailPopup'])) {
			$lrdata=$_SESSION['lrdata'];
			$lrdata['email']= $_REQUEST['email'];
			$flag=1;
			$Invitation_code=$_REQUEST['invitecode'];
			if($lrdata['roles']=='Invitation') {
				$Invitation= Gdn::SQL()
					->Select('Email')
					->From('Invitation')
					->Where('Code',$Invitation_code)
					->Where('AcceptedUserID',NULL)
					->Get()->Result(DATASET_TYPE_ARRAY);
				foreach($Invitation as $update) {
					$Invitation=GetValue('Email',$update);
				}
				if(!empty($Invitation)) { 
					$lrdata['email']=$Invitation;
					$lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
				}else {
					$msg='<p style ="color:red;"> Please enter valid invitation code </p>';
					$this->popup($msg,'yes');
					return false;
				}
			}
			else {
				$lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
				$EmailErrorMsg=trim(C('Plugins.SocialLogin.Emailerrortitle'));
				$EmailErrorMsg=!empty($EmailErrorMsg) ? $EmailErrorMsg : '';
				$validateemail = $this->ValidateEmail($lrdata['email']) ;
				if ($validateemail == FALSE) {
					$msg='<p style ="color:red;">'.$EmailErrorMsg.'</p>';
					$this->popup($msg,'no');
					return false;
				}
				$UserEmail = Gdn::SQL()
					->Select('UserID')
					->From('User')
					->Where('Email',$lrdata['email'])
					->Get()->Result(DATASET_TYPE_ARRAY);
				foreach ($UserEmail as $UpdateUser) {
					$UserID = GetValue('UserID', $UpdateUser);
				}
				if(!empty($UserID)) {
					$_SESSION['lrdata']=$lrdata;
					$msg = "<p style ='color:red;'>This Email is already registered or invalid please choose another one to proceed</p>";
					$this->popup($msg,'no');
					return false;
				}
			}	 
		}
		else if (isset($_POST['cancel'])) {
			unset($_SESSION['lrdata']);
		}
		if (!empty( $lrdata['fname']) && !empty( $lrdata['lname'] )) {
			$lrdata['username'] = $lrdata['fname']. ' ' . $lrdata['lname'] ;
		} 
		elseif (!empty($lrdata['FullName'])) {
			$lrdata['username']= $lrdata['FullName'];
		} 
		elseif (!empty($lrdata['ProfileName'])) {
			$lrdata['username'] = $lrdata['ProfileName'];
		}   
		elseif (!empty($lrdata['NickName'] )) {
			$lrdata['username'] = $lrdata['NickName'] ;
		} 
		elseif (!empty($lrdata['email'])) {
			$user_name = explode('@', $lrdata['email']);
			$lrdata['username']  = $user_name[0];
		} 
		else {
			$lrdata['username'] =  $lrdata['id'] ;
		}
		$UserData = Gdn::SQL()
			->Select('UserID')
			->From('UserAuthentication')
			->Where('ForeignUserKey',$lrdata['id'] )
			->Get();
	if (($UserData->NumRows()==0)&& !empty($lrdata['email']))  {
		unset($_SESSION['lrdata']);
		$result = Gdn::SQL()
			->Select('UserID')
			->From('User')
			->Like('Name',$lrdata['username'].'%')
			->Get(); 
		$MatchCount=$result->NumRows();
		if($MatchCount>0)  {
			for($i=0;$i<$MatchCount;$i++){
				$index++;
			}
			$lrdata['username']=$lrdata['username'].$index;
		}
		$User = Gdn::Session()->User;
		$ConfirmationCode = RandomString(8);
		$PasswordHash = new Gdn_PasswordHash();
		$Password = RandomString(8);
		$PasswordHashed = $PasswordHash->HashPassword($Password);
		$Data = array(
			'Name'=>$lrdata['username'],
			'Password'=> $PasswordHashed,
			'Email'=>$lrdata['email'],
			'Photo'=>$lrdata['thumbnail'],
			'About'=>$lrdata['aboutme'],
	   	    'Gender' => $lrdata['gender'],
			'DateOfBirth'=>Gdn_Format::ToDate($lrdata['dob'] ),
			'DateFirstVisit' =>   Gdn_Format::ToDateTime(),
			'InsertIPAddress' =>Gdn::Request()->IPAddress(),
			'LastIPAddress' =>Gdn::Request()->IPAddress(),
			'DateInserted' => Gdn_Format::ToDateTime(strtotime('-1 day'))
		);
		/*
		* Message the roles for email confirmation.
		*/
		$SkipEmail=C('Plugins.SocialLogin.SkipEmail');
		if((!empty($lrdata['email'])&& $SkipEmail=='No') || $flag==1) {
			if ( C('Garden.Registration.ConfirmEmail')==1) {
				$lrdata['rolevalue']=C('Garden.Registration.ConfirmEmailRole');
				TouchValue('Attributes', $Data, array());
				$Data['Attributes']['EmailKey'] = $ConfirmationCode;
			}
		}
		$Data['Attributes'] = serialize($Data['Attributes']);
		Gdn::SQL()->Options('Ignore', TRUE)->Insert('User', $Data); 
		$UserDataw = Gdn::SQL()
			->Select('UserID')
			->From('User')
			->Where('Email',  $lrdata['email'])
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($UserDataw as $UpdateUser) {
				$UserID = GetValue('UserID', $UpdateUser);
			}
		$DataAuth = array(
			'ForeignUserKey'=>$lrdata['id'],
			'ProviderKey'=>$lrdata['Provider'],
			'UserID'=>$UserID			
		);
		Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $DataAuth);
		if(	empty( $lrdata['rolevalue']))
			$lrdata['rolevalue']=8;
			$DataRole = array(
				'UserID'=>$UserID,
				'RoleID'=> $lrdata['rolevalue']
			);
		Gdn::SQL()->Options('Ignore', FALSE)->Insert('UserRole', $DataRole);
		$role=(C('Garden.Registration.Method'));
		if($role=='Invitation') {
			Gdn::SQL()
				->Update('Invitation')
				->Set('AcceptedUserID', $UserID)
				->Where('Email', $lrdata['email'])
				->Put();
		}
		$UserDataw = Gdn::SQL()
			->Select('UserID')
			->From('UserAuthentication')
			->Where('ForeignUserKey',  $lrdata['id'])
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($UserDataw as $UpdateUser) {
				$UserID = GetValue('UserID', $UpdateUser);
			}
		}
		if(!empty( $lrdata['email'])&& $UserID >0) {
		if(C('Plugins.SocialLogin.lrwelcomeemail') == 'Yes') {
		  $UserModel->SendWelcomeEmail($UserID,$Password, 'Add');
		 }
		 else if((!empty($lrdata['email'])&& $SkipEmail=='No') || $flag==1) {
		   $UserModel->SendEmailConfirmationEmail($UserID);
		 }
			$Session=Gdn::Session();
			Gdn::Session()->Start($UserID);
			$_SESSION['lrdata_store']=$lrdata['id'];
			$loc=$this->RedirectUrl();
			Redirect($loc);
		}
	}
	/*
	* Update User profile that you get from LoginRadius.
	*/
	public function updateprofile($lrdata, $UserID){
	   Gdn::SQL()->Update('User') ->Set(array(
	   	'Photo' => $lrdata['thumbnail'],
	   	'About' => $lrdata['aboutme'],
	   	'Gender' => $lrdata['gender'],
	   	'DateOfBirth' =>Gdn_Format::ToDate($lrdata['dob']),
	   	'DateUpdated' => Gdn_Format::ToDateTime(),
	   	'LastIPAddress' =>Gdn::Request()->IPAddress(),
       ))
         ->Where('UserID', $UserID)
         ->Put();
	}
    /*
    * Add Interface on Profile Page and Map the user Accounts.
	*/  
	public function ProfileController_AfterAddSideMenu_Handler($Sender) {
		$MappingTitle=trim(C('Plugins.SocialLogin.Mappingtitle'));
		$MappingTitle=!empty($MappingTitle)? $MappingTitle :'';
		$Get_Lr_Data=$_SESSION['lrdata_store'];
		$Is_User_Login=Gdn::Session()->UserID;
		if(Gdn::Session()->UserID >0) {
		    $map = '<ul>';
			$Map_Provider=Gdn::SQL()->Select('ForeignUserKey, ProviderKey') ->From('UserAuthentication') ->Where('UserID',$Is_User_Login) ->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($Map_Provider as $UpdateUser) {
			$msg ='Connected with ';
			if($UpdateUser['ForeignUserKey'] == $Get_Lr_Data) {
				$msg ='<span style="color:green;">Currently connected with </span>';
			}
			$map.='<li style="color:#1e79a7;font-family:Helvetica,arial,sans-serif; font-size:11px; ">'.Img('plugins/SocialLogin/Images/'.$UpdateUser['ProviderKey'].'.png', array('style' => 'max-width: 763px;')).$msg.ucfirst($UpdateUser['ProviderKey']).' '.Anchor(Img('/plugins/SocialLogin/Images/delete-small.png', array('style' => 'max-width: 763px;','title'=> 'delete')), '?kmode=remove&provider_id='.$UpdateUser['ForeignUserKey']. '&title=delete' ).'</li>';
		}
		$map .= '</ul>';
		if($_GET['kmode'] == 'remove'){
			Gdn::SQL()->Delete('UserAuthentication', array('ForeignUserKey' => $_GET['provider_id'],'UserID'=> $Is_User_Login));
			$Sender->InformMessage(T("Your Account have sucessfully Deleted"));
			$Sender->RedirectUrl= Url('profile.php');
		}
		$SideMenu = $Sender->EventArguments['SideMenu'];
		if(C('Plugins.SocialLogin.Enablesociallogin') == 'Yes') {
		$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$Secret = trim(C('Plugins.SocialLogin.Secretkey'));
			if (!empty($Secret) && !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
				$SideMenu->AddLink($MappingTitle,'<center style="margin:10px 0 0 0"><p style="color:red">Your LoginRadius API secret is not valid, please correct it or contact 
		LoginRadius support at <a href="http://www.LoginRadius.com" target="_blank">www.loginradius.com</a></p></center>'.$map);
			}
			else {
				if (!empty($AppID) && (!empty($Secret)))
					$SideMenu->AddLink($MappingTitle,'<center style="margin:10px 0 0 0"><div id="interfacecontainerdiv" class="interfacecontainerdiv"></div></center>'.$map);
		 	}
		}
		$Sender->EventArguments['SideMenu'] = $SideMenu;
		$Secret = trim(C('Plugins.SocialLogin.Secretkey'));
		$obj1 = new LoginRadius();
		$userprofile1 = $obj1->loginradius_get_data($Secret);
		$lrdata=array();
		if ($obj1->IsAuthenticated == TRUE && $Is_User_Login>0) {
			$lrdata['id'] = (!empty($userprofile1->ID) ? $userprofile1->ID :"");
			$lrdata['Provider'] = (!empty($userprofile1->Provider) ? $userprofile1->Provider :"");
			$Map_Lr_Id=Gdn::SQL()
			->Select('ForeignUserKey')
			->From('UserAuthentication')
			->Where('ForeignUserKey',$lrdata['id'])
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($Map_Lr_Id as $UpdateUser) {
				$Check_Map_Lr_Id = GetValue('ForeignUserKey', $UpdateUser);
			}
			if(empty($Check_Map_Lr_Id)) {
				$Check_Provider=Gdn::SQL()
				->Select('ProviderKey')
				->From('UserAuthentication')
				->Where('ProviderKey',$lrdata['Provider'])
				->Where('UserId',$Is_User_Login)
				->Get()->Result(DATASET_TYPE_ARRAY);
				foreach ($Check_Provider as $UpdateUser1) {
					$Check_Provider= GetValue('ProviderKey', $UpdateUser1);
				}
			if(empty($Check_Provider)) {
				$Map_Data = array('ForeignUserKey'=>$lrdata['id'],'ProviderKey'=>$lrdata['Provider'],'UserID'=> $Is_User_Login);
				Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $Map_Data);
				 $_SESSION['lrdata_store']=$lrdata['id'];
				$Sender->InformMessage(T("Your Account have sucessfully mapped"));
				$Sender->RedirectUrl= Url('profile.php');
			}
			else {
				$Sender->InformMessage(T("Account cannot be mapped as it already exists in our database."));
				$Sender->RedirectUrl= Url('profile.php');
			}
		}
		else
			$Sender->InformMessage(T("Your account have already mapped with this account"));
		}
	}
}
    /* 
	function for adding the widget before comment body on discussion page.
	*/	
	public function DiscussionController_AfterComment_Handler($Sender) {
		$Enablehorizontalsharing=trim(C('Plugins.SocialShare.Enablehorizontalsharing'));
		$Enableverticalsharing=trim(C('Plugins.SocialShare.Enableverticalsharing'));
		$hsharing_theme= trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
		$vsharing_theme= trim(C('Plugins.SocialShare.verticalsharingtheme'));
		if(C('Plugins.SocialShare.Enablesocialsharing') == 'Yes' ) { 
			if(C('Plugins.SocialShare.Enablehorizontalsharing') == 'Yes')  {
				if (GetValue('Type', $Sender->EventArguments) != 'Comment') {
					if( $hsharing_theme == 'horizonSharing32' || $hsharing_theme == 'horizonSharing16' || $hsharing_theme == 'single-image-theme-large' || $hsharing_theme == 'single-image-theme-small' ) {
						echo '<div><div class="lrsharecontainer"></div></div>';
					}
					else {
						echo '<div><div class="lrcounter_simplebox"></div></div>';
					}
				}
			}
			if(C('Plugins.SocialShare.Enableverticalsharing') == 'Yes')  {
				if (GetValue('Type', $Sender->EventArguments) != 'Comment') {
					if($vsharing_theme =='16VerticlewithBox' || $vsharing_theme =='32VerticlewithBox') {
						echo '<div><div class="lrshareverticalcontainer"></div></div>';
					}
					else {
						echo '<div><div class="lrcounter_verticalsimplebox"></div></div>';
					}
				}
			}
		}
	}	
	/*
	* Add to dashboard side menu.
 	*/ 
  public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
	  $Menu->AddItem('Add-ons', T('Addons'), FALSE, array('class' => 'Addons'));
      $Menu->AddLink('Add-ons', T('LoginRadius'), 'dashboard/settings/SocialLogin', 'Garden.Settings.Manage');
   }
    /*
    function for Returning Sharing code.
    */
	public function loginradius_sharescript() {
		$code='<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;var hybridsharing = true;</script> 
		<script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script>';
		if(C('Plugins.SocialShare.Enablehorizontalsharing') == 'Yes')  {
			$hsharing_theme= trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
			if($hsharing_theme == 'horizonSharing32' || $hsharing_theme =='horizonSharing16' || $hsharing_theme =='single-image-theme-large' || $hsharing_theme =='single-image-theme-small') {
			$provider_list=  C('Plugins.SocialShare.loginRadiusLIrearrange'); 
			if(empty($provider_list)) {		
	 			 $provider_list = array('0' => 'Facebook','1' => 'Pinterest','2' => 'GooglePlus','3' => 'Twitter','4' => 'LinkedIn');			
			}
	  		$providers = implode('","', $provider_list);
			if($hsharing_theme =='horizonSharing32' || $hsharing_theme =='horizonSharing16') {
	 			 $interface='horizontal';
			}
			else {
	  			$interface='simpleimage';
			}
			if($hsharing_theme =='horizonSharing32' || $hsharing_theme =='single-image-theme-large') {
	 		 	$size=32;
			}
			else {
	  			$size=16;
			}
			$AppID=trim(C('Plugins.SocialLogin.Apikey'));
			$sharecounttype = (!empty($AppID) ? ('$u.apikey="'.$AppID.'";$u.sharecounttype='."'url'".';') : '$u.sharecounttype='."'url'".';'); 
	  		$code .= '<script type="text/javascript">LoginRadius.util.ready(function () { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings; ' . $sharecounttype . ' $i.size = ' . $size . ';$i.show("lrsharecontainer"); });</script>'; 
		}
		else {
			if($hsharing_theme =='hybrid-horizontal-horizontal' || $hsharing_theme =='hybrid-horizontal-vertical') {
				$provider_list=  C('Plugins.SocialShare.loginRadiuscounter'); 
				if (empty($provider_list)) {
					$provider_list = array('Facebook Like', 'Google+ Share','Hybridshare' ,'Pinterest Pin it','Twitter Tweet');
				}
				$providers = implode('","', $provider_list);
				$interface='simple';
				if($hsharing_theme == 'hybrid-horizontal-horizontal') {
					$type ='horizontal';
				}
				else {
					$type ='vertical';
				}
				$code .= '<script type="text/javascript">LoginRadius.util.ready(function () { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.' . $interface . '; $S.isHorizontal = true; $S.countertype = \'' . $type . '\'; $S.show("lrcounter_simplebox"); });</script>';
			}
		}
	}
	if(C('Plugins.SocialShare.Enableverticalsharing') == 'Yes')  {
		$vsharing_theme= trim(C('Plugins.SocialShare.verticalsharingtheme'));
		if($vsharing_theme =='16VerticlewithBox' || $vsharing_theme =='32VerticlewithBox') {
			$interface='Simplefloat';
			$provider_list=  C('Plugins.SocialShare.loginRadiusLIverticalrearrange');
			if(empty($provider_list)) {		
	  			$provider_list = array('0' => 'Facebook','1' => 'Pinterest','2' => 'GooglePlus','3' => 'Twitter','4' => 'LinkedIn');			
			}
			$providers = implode('","', $provider_list);
			if($vsharing_theme =='16VerticlewithBox') {
				$size=16;
			}
			else {
				$size=32;
			}
			$AppID=trim(C('Plugins.SocialLogin.Apikey'));
  			$sharecounttype = (!empty($AppID) ? ('$u.apikey="'.$AppID.'";$u.sharecounttype='."'url'".';') : '$u.sharecounttype='."'url'".';');
   			$code .= '<script type="text/javascript">LoginRadius.util.ready(function () { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings; ' . $sharecounttype . ' $i.size = ' . $size . ';';
  			 $vsharing_position= trim(C('Plugins.SocialShare.Verticalsharingposition'));
			if ($vsharing_position == 'topleft') {
      			$position1 = 'top';
       			$position2 = 'left';
     		}
     		elseif ($vsharing_position == 'topright') {
       			$position1 = 'top';
       			$position2 = 'right';
     		}
     		elseif ($vsharing_position=='bottomleft') {
       			$position1 = 'bottom';
      			$position2 = 'left';
    		 }
     		else {
      			$position1 = 'bottom';
       			$position2 = 'right';
     		}
	 		$sharing_offset=(C('Plugins.SocialShare.Sharingoffset'));
    		if (isset($sharing_offset) && trim($sharing_offset) != "" && is_numeric($sharing_offset)) {
       			$code .= '$i.top = \'' . trim($sharing_offset) . 'px\'; $i.' . $position2 . ' = \'0px\';$i.show("lrshareverticalcontainer"); });</script>';
     		}
     		else {
       			$code .= '$i.' . $position1 . ' = \'0px\'; $i.' . $position2 . ' = \'0px\';$i.show("lrshareverticalcontainer"); });</script>';
     		}
		}
		else {
			$provider_list=  C('Plugins.SocialShare.loginRadiusverticalcounter'); 
			if (empty($provider_list)) {
				$provider_list = array('Facebook Like', 'Google+ Share','Hybridshare' ,'Pinterest Pin it','Twitter Tweet');
			}
			$providers = implode('","', $provider_list);
			if($vsharing_theme =='hybrid-verticle-vertical') {
				$type = 'vertical';
			}
			else {
				$type = 'horizontal';
			}
			$code .= '<script type="text/javascript">LoginRadius.util.ready(function () { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.simple; $S.isHorizontal = false; $S.countertype = \'' . $type . '\';';
	 		$vsharing_position= trim(C('Plugins.SocialShare.Verticalsharingposition'));
			if ($vsharing_position == 'topleft') {
       			$position1 = 'top';
       			$position2 = 'left';
			}
     		elseif ($vsharing_position == 'topright') {
      			$position1 = 'top';
       			$position2 = 'right';
     		}
    		elseif ($vsharing_position=='bottomleft') {
       			$position1 = 'bottom';
       			$position2 = 'left';
     		}
    		else {
       			$position1 = 'bottom';
       			$position2 = 'right';
     		}
			$sharing_offset=(C('Plugins.SocialShare.Sharingoffset'));
     		if (isset($sharing_offset) && trim($sharing_offset) != "" && is_numeric($sharing_offset)) {
       			$code .= '$S.top = \'' . trim($sharing_offset) . 'px\'; $S.' . $position2 . ' = \'0px\';$S.show("lrcounter_verticalsimplebox"); });</script>';
     		}
    	 	else {
       			$code .='$S.' . $position1 . ' = \'0px\'; $S.' . $position2 . ' = \'0px\';$S.show("lrcounter_verticalsimplebox"); });</script>';
     		}	 
		}
	}
	return $code;
	}
   /*
   * Validate Email that get you from mail Popup box.
   */
	public function ValidateEmail($Value, $Field = '') {
    	$Result = PHPMailer::ValidateAddress($Value);
    	$Result = (bool)$Result;
    	return  $Result;
   	}
	/**
	* Admin Configuration option.
	*/
  	public function SettingsController_SocialLogin_Create($Sender, $Args) {
    	$Sender->Permission('Garden.Settings.Manage');
    	if ($Sender->Form->IsPostBack()) {
			$Apikey=trim($Sender->Form->GetFormValue('Apikey'));
			$Secretkey=trim($Sender->Form->GetFormValue('Secretkey'));
      				$rearangesettings = $_REQUEST['rearrange_settings'];
					$counter_rearrange_settings = $_REQUEST['Form/loginRadiuscounter'];
					$vertical_rearrange = $_REQUEST['vertical_rearrange_settings'];
					$verticalcounter_rearrange_settings = $_REQUEST['Form/loginRadiusverticalcounter'];
      				$Settings = array(
     				'Plugins.SocialLogin.Apikey' =>$Apikey,
     				'Plugins.SocialLogin.Secretkey' =>$Secretkey, 
	 				'Plugins.SocialLogin.Use_Api' =>$Sender->Form->GetFormValue('Use_Api'),
	 				'Plugins.SocialLogin.Sociallogintitle' =>$Sender->Form->GetFormValue('Sociallogintitle'),
					'Plugins.SocialLogin.updateprofile' =>$Sender->Form->GetFormValue('updateprofile'),	
					'Plugins.SocialLogin.Sociallogincolumns' =>$Sender->Form->GetFormValue('Sociallogincolumns'),
					'Plugins.SocialLogin.Socialloginbackground' =>$Sender->Form->GetFormValue('Socialloginbackground'), 
					'Plugins.SocialLogin.Email_required' => $Sender->Form->GetFormValue('EmailRequired'),
	 				'Plugins.SocialLogin.Loginredirect' => $Sender->Form->GetFormValue('Loginredirect'),
	 				'Plugins.SocialLogin.Loginredirecturl' =>$Sender->Form->GetFormValue('Loginredirecturl'),
	 				'Plugins.SocialLogin.Account_linking' => $Sender->Form->GetFormValue('Accountlinking'),
	 				'Plugins.SocialLogin.Emailtitle' => $Sender->Form->GetFormValue('Emailtitle'),
	 				'Plugins.SocialLogin.Emailerrortitle' => $Sender->Form->GetFormValue('Emailerrortitle'),
	 				'Plugins.SocialLogin.Mappingtitle' => $Sender->Form->GetFormValue('Mappingtitle'),
	 				'Plugins.SocialLogin.SkipEmail' => $Sender->Form->GetFormValue('SkipEmail'),
					'Plugins.SocialLogin.lrwelcomeemail' => $Sender->Form->GetFormValue('lrwelcomeemail'),
					'Plugins.SocialShare.Enablesocialsharing' =>$Sender->Form->GetFormValue('Enablesocialsharing'),
					'Plugins.SocialLogin.Enablesociallogin' =>$Sender->Form->GetFormValue('Enablesociallogin'),
					'Plugins.SocialLogin.Enablesocialicon' =>$Sender->Form->GetFormValue('Enablesocialicon'),
					'Plugins.SocialShare.Enablehorizontalsharing' =>$Sender->Form->GetFormValue('Enablehorizontalsharing'),
					'Plugins.SocialShare.Enableverticalsharing' =>$Sender->Form->GetFormValue('Enableverticalsharing'),
	 				'Plugins.SocialShare.Horizontalsharingtheme' =>$Sender->Form->GetFormValue('Horizontalsharingtheme'),
					'Plugins.SocialShare.verticalsharingtheme' =>$Sender->Form->GetFormValue('verticalsharingtheme'),
	 				'Plugins.SocialShare.Verticalsharingtheme' =>$Sender->Form->GetFormValue('Verticalsharingtheme'),
	 				'Plugins.SocialShare.Verticalsharingposition' =>$Sender->Form->GetFormValue('Verticalsharingposition'),
	 				'Plugins.SocialShare.Sharingoffset' =>$Sender->Form->GetFormValue('Sharingoffset'),
	 				'Plugins.SocialShare.loginRadiusLIrearrange' => $rearangesettings, 
					'Plugins.SocialShare.loginRadiuscounter' => $counter_rearrange_settings, 
					'Plugins.SocialShare.loginRadiusLIverticalrearrange' => $vertical_rearrange, 
					'Plugins.SocialShare.loginRadiusverticalcounter' => $verticalcounter_rearrange_settings);
     				SaveToConfig($Settings);
					$Sender->InformMessage(T("Your settings have been saved."));
				
  		}
    	else {
	 		$LoginRedirect=((C('Plugins.SocialLogin.Loginredirect')=='')?'Loginredirect1':C('Plugins.SocialLogin.Loginredirect'));
			$UseApi=((C('Plugins.SocialLogin.Use_Api')=='')?'CURL':C('Plugins.SocialLogin.Use_Api'));
			$EmailRequired=((C('Plugins.SocialLogin.Email_required')=='')?'Yes':C('Plugins.SocialLogin.Email_required'));
			$SkipEmail=((C('Plugins.SocialLogin.SkipEmail')=='')?'No':C('Plugins.SocialLogin.SkipEmail'));
			$lrwelcomeemail=((C('Plugins.SocialLogin.lrwelcomeemail')=='')?'Yes':C('Plugins.SocialLogin.lrwelcomeemail'));
			$AccountLinking=((C('Plugins.SocialLogin.Account_linking')=='')?'Yes':C('Plugins.SocialLogin.Account_linking'));
			$EnableSocialSharing=((C('Plugins.SocialShare.Enablesocialsharing')=='')?'Yes':C('Plugins.SocialShare.Enablesocialsharing'));
			$Enablesociallogin=((C('Plugins.SocialLogin.Enablesociallogin')=='')?'Yes':C('Plugins.SocialLogin.Enablesociallogin'));
			$updateprofile=((C('Plugins.SocialLogin.updateprofile')=='')?'Yes':C('Plugins.SocialLogin.updateprofile'));
			$Enablesocialicon=((C('Plugins.SocialLogin.Enablesocialicon')=='')?'small':C('Plugins.SocialLogin.Enablesocialicon'));
			$Enablehorizontalsharing=((C('Plugins.SocialShare.Enablehorizontalsharing')=='')?'Yes':C('Plugins.SocialShare.Enablehorizontalsharing'));
			$Enableverticalsharing=((C('Plugins.SocialShare.Enableverticalsharing')=='')?'Yes':C('Plugins.SocialShare.Enableverticalsharing'));
			$HorizontalSharing=((C('Plugins.SocialShare.Horizontalsharingtheme')=='')?'horizonSharing32':C('Plugins.SocialShare.Horizontalsharingtheme'));
			$VerticalSharing=((C('Plugins.SocialShare.verticalsharingtheme')=='')?'16VerticlewithBox':C('Plugins.SocialShare.verticalsharingtheme'));
			$VerticalSharingPosition=((C('Plugins.SocialShare.Verticalsharingposition')=='')?'topleft':C('Plugins.SocialShare.Verticalsharingposition'));
		    /**
		    * sociallogin setting call
		    */
			$Sender->Form->SetFormValue('Apikey', C('Plugins.SocialLogin.Apikey'));
			$Sender->Form->SetFormValue('Secretkey', C('Plugins.SocialLogin.Secretkey'));	  
			$Sender->Form->SetFormValue('Use_Api', $UseApi);
			$Sender->Form->SetFormValue('Sociallogintitle', C('Plugins.SocialLogin.Sociallogintitle'));	
			$Sender->Form->SetFormValue('updateprofile', $updateprofile);	
			$Sender->Form->SetFormValue('Socialloginbackground', C('Plugins.SocialLogin.Socialloginbackground'));
			$Sender->Form->SetFormValue('Sociallogincolumns', C('Plugins.SocialLogin.Sociallogincolumns')); 
			$Sender->Form->SetFormValue('EmailRequired', $EmailRequired);
			$Sender->Form->SetFormValue('SkipEmail', $SkipEmail);
			$Sender->Form->SetFormValue('lrwelcomeemail', $lrwelcomeemail);
			$Sender->Form->SetFormValue('Loginredirect', $LoginRedirect);
			$Sender->Form->SetFormValue('Accountlinking', $AccountLinking);
			$Sender->Form->SetFormValue('Emailtitle', C('Plugins.SocialLogin.Emailtitle'));
			$Sender->Form->SetFormValue('Emailerrortitle', C('Plugins.SocialLogin.Emailerrortitle'));
		    /*
			socialsharing setting call
			*/
			$Sender->Form->SetFormValue('Enablesocialsharing',$EnableSocialSharing);
			$Sender->Form->SetFormValue('Enablesociallogin',$Enablesociallogin);
			$Sender->Form->SetFormValue('Enablesocialicon',$Enablesocialicon);
			$Sender->Form->SetFormValue('Enablehorizontalsharing',$Enablehorizontalsharing);
			$Sender->Form->SetFormValue('Enableverticalsharing',$Enableverticalsharing);
			$Sender->Form->SetFormValue('Horizontalsharingtheme', $HorizontalSharing);
			$Sender->Form->SetFormValue('verticalsharingtheme', $VerticalSharing);
			$Sender->Form->SetFormValue('Verticalsharingtheme', C('Plugins.SocialShare.Verticalsharingtheme'));
			$Sender->Form->SetFormValue('Verticalsharingposition', $VerticalSharingPosition);
			$Sender->Form->SetFormValue('Sharingoffset', C('Plugins.SocialShare.Sharingoffset'));
			$Sender->Form->SetFormValue('loginRadiusLIrearrange', C('Plugins.SocialShare.loginRadiusLIrearrange'));
			$Sender->Form->SetFormValue('loginRadiuscounter', C('Plugins.SocialShare.loginRadiuscounter'));
			$Sender->Form->SetFormValue('loginRadiusLIverticalrearrange', C('Plugins.SocialShare.loginRadiusLIverticalrearrange'));
			$Sender->Form->SetFormValue('loginRadiusverticalcounter', C('Plugins.SocialShare.loginRadiusverticalcounter'));
		 }
    	$Sender->AddSideMenu();
   		$Sender->SetData('Title', T('LoginRadius Social Plugin Settings'));
    	$Sender->Render('Settings', '', 'plugins/SocialLogin');
   }
	/**
	* Redirection options
	*/
	public function RedirectUrl() {
  		$Loginredirect = C('Plugins.SocialLogin.Loginredirect'); 
  		$Loginredirecturl= C('Plugins.SocialLogin.Loginredirecturl'); 
  		if ($Loginredirect=="Loginredirect1") {
		$http = ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://");
    	$loc = $http.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; 
		}
  		else if ($Loginredirect=="Loginredirect2") {
    		$loc = 'profile.php';
		}
		else if($Loginredirect == 'Loginredirect4') {
		    $loc ='/';
		}
  		else {
    		$loc = $Loginredirecturl;
  		}
	  return $loc;
	}
	/**
	* Show Popup box to get Email address.
	*/
   	public function popup($msg, $showinvitation)  { ?> 
    	<link rel="stylesheet" href="plugins/SocialLogin/login.css" type="text/css" />
    	<div id="fade" class="LoginRadius_overlay">
    	<div id="popupouter">
    	<div id="popupinner">
		<div id="textmatter"><?php
    	if ($msg) {
      		echo "<b>" . $msg . "</b>";
    	}?></div>
    	<form id="wp_login_form"  method="post"  action="">
		<?php if($showinvitation == 'no' || $showinvitation == '') {?>
    	Email <input type="text" name="email" id="email" class="inputtxt" />
		<?php } else {?>
	 	Invitation code  <input type="text" name="invitecode" id="invitecode" class="inputtxt" />
		<?php } ?>
    	<input type="submit" id="LoginRadiusEmailPopup" name="LoginRadiusEmailPopup" value="Submit" class="inputbutton">
    	<input type="submit" value="Cancel" class="inputbutton" onClick="history.back()" />
    	</form></div></div></div>
<?php }

 
 public function OnDisable() {}
}