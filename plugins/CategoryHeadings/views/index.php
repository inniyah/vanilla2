<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
include($this->FetchViewLocation('helper_functions', 'discussions', 'vanilla'));
if($this->DiscussionData){
	WriteFilterTabs($this);
}

$this->CategoryData=$this->Data['CategoryChildren'];
include($this->FetchViewLocation('All', '','plugins/CategoryHeadings')); 
if($this->DiscussionData){
	if ($this->DiscussionData->NumRows() > 0 || (isset($this->AnnounceData) && is_object($this->AnnounceData) && $this->AnnounceData->NumRows() > 0)) {
	?>
   <div class="Tabs Headings">
      <div class="ItemHeading"><?php echo sprintf(T('%s Discussions'),Gdn_Format::Text($this->Category->Name)); ?></div>
   </div>
	<ul class="DataList Discussions">
	   <?php include($this->FetchViewLocation('discussions','discussions', 'vanilla')); ?>
	</ul>
	<?php
	   $PagerOptions = array('RecordCount' => $this->Data('CountDiscussions'), 'CurrentRecords' => $this->Data('Discussions')->NumRows());
	   if ($this->Data('_PagerUrl')) {
		  $PagerOptions['Url'] = $this->Data('_PagerUrl');
	   }
	   echo PagerModule::Write($PagerOptions);
	} else {
	   ?>
	   
	   <?php
	}
}
