<?php if(!defined('APPLICATION')) die();
/**
*
* # Maintenance Plugin for Vanilla 2 #
* You can change closed.php to be whatever you want to be shown while you are closed for upgrade.
* 
*
*/

// Define the plugin:
$PluginInfo['Maintenance'] = array(
   'Name' => 'Vanilla Maintenance',
   'Description' => '<a href="#" target="_blank">Maintenance plugin for Vanilla 2.</a>',
   'Version' => '0.4',
   'Author' => "Adrian Speyer",
   'AuthorEmail' => 'adriansprojects+vanilla@gmail.com',
   'AuthorUrl' => 'http://www.adrianspeyer.com/projects',
   'RequiredApplications' => array('Vanilla' => '>=2')
);

class WebHead implements Gdn_IPlugin {

    public function Base_Render_Before(&$Sender) {
		if ( Gdn::Session()->CheckPermission('Garden.Settings.Manage'))
		{
		echo '<div style="color:#00FF00; background-color:red;text-align:center;"><b>The site is currently in Maintenance Mode</b></div>';
		}
		else
	header( 'Location:'.$Url.'./plugins/Maintenance/closed.php' ) ;
}
    public function Setup() {
    }
}
