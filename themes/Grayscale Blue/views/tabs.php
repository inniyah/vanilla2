<?php if (!defined('APPLICATION')) exit();

// Get the tab sort order from the user-prefs.
$SortOrder = FALSE;
$SortOrder = ArrayValue('ProfileTabOrder', $this->User->Preferences, FALSE);
// If not in the user prefs, get the sort order from the application prefs.
if ($SortOrder === FALSE)
   $SortOrder = Gdn::Config('Garden.ProfileTabOrder');

if (!is_array($SortOrder))
   $SortOrder = array();
   
// Make sure that all tabs are present in $SortOrder
foreach ($this->_ProfileTabs as $TabCode => $TabInfo) {
   if (!in_array($TabCode, $SortOrder))
      $SortOrder[] = $TabCode;
}
?>
<div class="Tabs ProfileTabs">
   <ul>
   
<?php
   // Get sorted tabs
   foreach ($SortOrder as $TabCode) {
      $CssClass = $TabCode == $this->_CurrentTab ? 'Active ' : '';
      // array_key_exists: Just in case a method was removed but is still present in sortorder
      if (array_key_exists($TabCode, $this->_ProfileTabs)) {
         $TabInfo = GetValue($TabCode, $this->_ProfileTabs, array());
         $CssClass .= GetValue('CssClass', $TabInfo, '');
         echo '<li'.($CssClass == '' ? '' : ' class="'.$CssClass.'"').'>'.Anchor(GetValue('TabHtml', $TabInfo, $TabCode), GetValue('TabUrl', $TabInfo), array('class' => 'TabLink'))."</li>\r\n";
      }
   }
   ?>

<?php 
	$MyDrafts = T('My Drafts');
	if ($Session->IsValid()) {
      		$CountDrafts = $Session->User->CountDrafts;
   	}
	if (is_numeric($CountDrafts) && $CountDrafts > 0)
      		$MyDrafts .= '<span>'.$CountDrafts.'</span>';
	
	if ($CountDrafts > 0 || $Sender->ControllerName == 'draftscontroller') {
      		?>
      		<li<?php echo $Sender->ControllerName == 'draftscontroller' ? ' class="Active"' : ''; ?>><?php echo 	Anchor($MyDrafts, '/drafts', 'MyDrafts TabLink'); ?></li> <?php
      } ?>

   </ul>
</div>