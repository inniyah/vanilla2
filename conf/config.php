<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.0.18.3';

// Database
$Configuration['Database']['Name'] = 'aula_vanilla';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'aula';
$Configuration['Database']['Password'] = '4ul4cpr';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';

// EnabledLocales
$Configuration['EnabledLocales']['spanish'] = 'es-ES';

// EnabledPlugins
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['AllViewed'] = TRUE;
$Configuration['EnabledPlugins']['Flagging'] = TRUE;
$Configuration['EnabledPlugins']['VanillaInThisDiscussion'] = TRUE;
$Configuration['EnabledPlugins']['SplitMerge'] = TRUE;
$Configuration['EnabledPlugins']['Tagging'] = TRUE;
$Configuration['EnabledPlugins']['VanillaStats'] = TRUE;
$Configuration['EnabledPlugins']['cleditor'] = TRUE;
$Configuration['EnabledPlugins']['Gravatar'] = TRUE;
$Configuration['EnabledPlugins']['Voting'] = TRUE;
$Configuration['EnabledPlugins']['QnA'] = TRUE;
$Configuration['EnabledPlugins']['LocaleDeveloper'] = TRUE;
$Configuration['EnabledPlugins']['NillaBlog'] = TRUE;
$Configuration['EnabledPlugins']['CommentsRSS'] = TRUE;
$Configuration['EnabledPlugins']['FirstLastNames'] = TRUE;
$Configuration['EnabledPlugins']['VanillaFancybox'] = TRUE;
$Configuration['EnabledPlugins']['Quotes'] = TRUE;
$Configuration['EnabledPlugins']['FileUpload'] = TRUE;
$Configuration['EnabledPlugins']['UnreadIcon'] = TRUE;

// Garden
$Configuration['Garden']['Title'] = 'AUla Libre Asturiana';
$Configuration['Garden']['Cookie']['Salt'] = 'GVCWGY87OR';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Registration']['ConfirmEmail'] = '1';
$Configuration['Garden']['Registration']['Method'] = 'Captcha';
$Configuration['Garden']['Registration']['ConfirmEmailRole'] = '8';
$Configuration['Garden']['Registration']['CaptchaPrivateKey'] = '6LfSdc8SAAAAADpfjOoW4_Cm0v7EJnW05zbZmujd';
$Configuration['Garden']['Registration']['CaptchaPublicKey'] = '6LfSdc8SAAAAAH2_frscYrhOqFD6ZZbfq1FKDaqD';
$Configuration['Garden']['Registration']['InviteExpiration'] = '-1 week';
$Configuration['Garden']['Registration']['InviteRoles'] = 'a:5:{i:3;s:1:"0";i:4;s:1:"0";i:8;s:1:"0";i:32;s:1:"0";i:16;s:1:"0";}';
$Configuration['Garden']['Email']['SupportName'] = 'AUla Libre Asturiana';
$Configuration['Garden']['Version'] = '2.0.18.3';
$Configuration['Garden']['RewriteUrls'] = FALSE;
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Installed'] = TRUE;
$Configuration['Garden']['Locale'] = 'es-ES';
$Configuration['Garden']['InstallationID'] = '8A00-2DACB86F-49E56DAB';
$Configuration['Garden']['InstallationSecret'] = 'c17d9df9e47624b2f0cb4f77c1bc3b1390d6dcb6';
$Configuration['Garden']['Html']['SafeStyles'] = FALSE;

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['GettingStarted']['Registration'] = '1';
$Configuration['Plugins']['GettingStarted']['Profile'] = '1';
$Configuration['Plugins']['LocaleDeveloper']['CaptureDefinitions'] = '1';
$Configuration['Plugins']['LocaleDeveloper']['Key'] = 'aula';
$Configuration['Plugins']['LocaleDeveloper']['Name'] = 'aula';
$Configuration['Plugins']['LocaleDeveloper']['Locale'] = 'aula';
$Configuration['Plugins']['FileUpload']['Enabled'] = TRUE;

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0.18.3';
$Configuration['Vanilla']['AdminCheckboxes']['Use'] = TRUE;

// Last edited by inniyah (212.89.9.53)2012-03-30 05:39:37