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

// EnabledPlugins
$Configuration['EnabledPlugins']['GettingStarted'] = 'GettingStarted';
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';

// Garden
$Configuration['Garden']['Title'] = 'AUla Libre Asturiana';
$Configuration['Garden']['Cookie']['Salt'] = 'GVCWGY87OR';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Registration']['ConfirmEmail'] = TRUE;
$Configuration['Garden']['Email']['SupportName'] = 'AUla Libre Asturiana';
$Configuration['Garden']['Version'] = '2.0.18.3';
$Configuration['Garden']['RewriteUrls'] = FALSE;
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Installed'] = TRUE;

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0.18.3';

// Last edited by inniyah (85.152.220.76)2012-03-20 09:04:47