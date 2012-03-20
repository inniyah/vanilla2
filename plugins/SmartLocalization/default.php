<?php if (!defined('APPLICATION')) die(); // …

$PluginInfo['SmartLocalization'] = array(
	'Name' => 'Smart Localization',
	'Description' => 'Allows overwrite translation code depending on the application (controller/method).',
	'Version' => '2.6',
	'Author' => 'Flak Monkey',
	'AuthorUrl' => 'http://www.urbandictionary.com/define.php?term=Flak+Monkey',
	'Date' => 'Summer 2011',
	'Updated' => 'Winter 2011',
	'RequiredApplications' => array('Dashboard' => '>=2.0.18')
);

class SmartLocalizationPlugin implements Gdn_IPlugin {
	
	private $Definition = array();
	private $bInitialized;
	private $LocaleContainer;
	
/*	public function RoleController_AfterRenderAsset_Handler($Sender) {
		$AssetName = $Sender->EventArguments['AssetName'];
		if ($AssetName == 'Content') {
			Gdn::Locale()->LocaleContainer = $this->LocaleContainer;
		}
	}*/
	
/*	public function RoleController_BeforeRenderAsset_Handler($Sender) {
		$AssetName = $Sender->EventArguments['AssetName'];
		if ($AssetName == 'Content') {
			Gdn::Locale()->Unload();
		}
	}*/
	
	public function RoleController_BeforeRolePermissions_Handler($Sender) {
		// TODO: Make unload only for content
		if (C('Plugins.SmartLocalization.DisableRolePermissionsTranslate')) Gdn::Locale()->Unload();
	}
	
	public function Gdn_Dispatcher_BeforeControllerMethod_Handler($Sender) {
		if (!$this->bInitialized) {
			$this->bInitialized = True;
			$Controller =& $Sender->EventArguments['Controller'];
			$this->LoadCustomTranslation();
			$this->SetCustomTranslation($Controller);
		}
	}
	
	protected function SetCustomTranslation($Sender) {
		// Get sender info
		$Application = mb_convert_case($Sender->Application, 2);
		$Controller = mb_convert_case(substr($Sender->ControllerName, 0, -10), 2);
		$Method = mb_convert_case($Sender->RequestMethod, 2);
		//d($Application.$Controller.$Method, $this->Definition);
		
		// Search custom definitions for this application and this controller
		$Codes = array();
		if (array_key_exists($Application, $this->Definition))
			$Codes = array_merge($Codes, (array)$this->Definition[$Application]);
		if (array_key_exists($Application.$Controller, $this->Definition))
			$Codes = array_merge($Codes, (array)$this->Definition[$Application.$Controller]);
		if (array_key_exists($Application.$Controller.$Method, $this->Definition))
			$Codes = array_merge($Codes, (array)$this->Definition[$Application.$Controller.$Method]);
		
		// Set translation
		Gdn::Locale()->SetTranslation($Codes);
	}
	
	protected function LoadCustomTranslation($ForceRemapping = False) {
		$LocaleName = Gdn::Locale()->Current();
		$CacheFile = PATH_CACHE . '/customtranslation_map.ini';
		if (!file_exists($CacheFile) || $ForceRemapping === True) $this->PrepareCache($LocaleName);
		// Load CacheFile.
		include $CacheFile;
		$LocaleSources = ArrayValue($LocaleName, $_, array());
		// Look for a config locale that is locale-specific.
		$ConfigCustomLocale = PATH_CONF."/locale-$LocaleName.custom.php";
		$LocaleSources[] = $ConfigCustomLocale;
		// Set up defaults.
		$Definition = array();
		$this->Definition =& $Definition;
		// Import all of the sources.
		for ($Count = count($LocaleSources), $i = 0; $i < $Count; ++$i) {
			if (file_exists($LocaleSources[$i])) include($LocaleSources[$i]);
		}
	}
	
	protected function PrepareCache($LocaleName = False) {
		$LocaleSources = array();
		if ($LocaleName === False) $LocaleName = Gdn::Locale()->Current();
		$EnabledApplications = Gdn::ApplicationManager()->EnabledApplicationFolders();
		$EnabledPlugins = Gdn::PluginManager()->EnabledPluginFolders();
		
		$FindPaths = array(
			PATH_APPLICATIONS => $EnabledApplications,
			PATH_PLUGINS => $EnabledApplications,
			PATH_THEMES => (array)C('Garden.Theme')
		);
		
		// Get locale special definition files
		foreach ($FindPaths as $Path => $List) {
			$PathSources = Gdn_FileSystem::FindAll($Path, CombinePaths(array('locale', $LocaleName.'.custom.php')), $List);
			if ($PathSources !== False) $LocaleSources = array_merge($LocaleSources, $PathSources);
		}

		// Get locale-based locale special definition files.
		$EnabledLocales = C('EnabledLocales', array());
		foreach ($EnabledLocales as $Key => $Locale) {
			if ($Locale != $LocaleName) continue;
			// Grab all of the files in the locale's folder (subdirectory custom)
			$Paths = glob(PATH_ROOT."/locales/$Key/custom/*.php");
			if (is_array($Paths)) foreach($Paths as $Path) $LocaleSources[] = $Path;
		}

		$PhpLocaleName = var_export($LocaleName, True);
		$PhpLocaleSources = var_export($LocaleSources, True);
		$PhpArrayCode = "\n\$_[$PhpLocaleName] = $PhpLocaleSources;";
		
		$CacheFile = PATH_CACHE . '/customtranslation_map.ini';
		if (!file_exists($CacheFile)) $PhpArrayCode = '<?php' . $PhpArrayCode;
		file_put_contents($CacheFile, $PhpArrayCode, FILE_APPEND | LOCK_EX);
	}
	
	public function Setup() {
		if (!function_exists('mb_convert_case')) 
			throw new Exception('mbstring extension (Multibyte String Functions) is required.');
	}
	
	
}










