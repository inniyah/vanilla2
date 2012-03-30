<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['UnreadIcon'] = array(
    'Name' => 'Unread Icon',
    'Description' => 'Inserts unread icon in front of unread comments and the last comment within indivdual discussions and Recent Icon for messages less than X hours old',
    'Version' => '1.2.1',
    'RequiredApplications' => array('Vanilla' => '2.0.17.8'),
    'RequiredTheme' => FALSE,
    'RequiredPlugins' => FALSE,
    'HasLocale' => FALSE,
    'SettingsUrl' => FALSE,
    'SettingsPermission' => 'Plugins.UnreadIcon.Manage',
    'Author' => "Peregrine"
);

class UnreadIcon extends Gdn_Plugin {


    protected $_Comids;

    public function GetData($DiscussionID, $Limit = 200) {

        $SQL = Gdn::SQL();
        $this->_ComIds = $SQL
                        ->Select('CommentID')
                        ->From('Comment')
                        ->Where('DiscussionID', $DiscussionID)
                        ->Get()->ResultArray();
        return $this->_ComIds;
    }

    protected $CommmentIdArray = Array();
    protected $lid;

    public function CacheUnread($CommmentIdArray) {
        $this->CommmentIdArray = $CommmentIdArray;
    }

    public function GetCacheUnread() {
        return $this->CommmentIdArray;
    }

    public function CacheLastDid($lid) {
        $this->lid = $lid;
    }

    public function GetCacheLastDid() {
        return $this->lid;
    }

    public function PluginController_UnreadIcon_Create($Sender, $Args = array()) {

        $Sender->Permission('Plugins.UnreadIcon.Manage');
    }

    protected $unread;
    protected $totcount;
    protected $totdelete;

    public function DiscussionController_BeforeDiscussion_Handler($Sender) {

        // get the number of unread comments, last comment id
        $unread = $Sender->Discussion->CountComments - $Sender->Discussion->CountCommentWatch;
        $lid = $Sender->Discussion->LastCommentID;

        $this->CacheLastDid($lid);

        $this->lid = $lid;

        // get all comment ids for discussion

        $result = $this->GetData($Sender->Discussion->DiscussionID, $Limit = 300);

        $CommmentIdArray[0] = 0;

        $arrsize = count($result);

        for ($x = 0; $x < $arrsize; $x++) {
            $CommmentIdArray[$x] = $result[$x]['CommentID'];
        }

        rsort($CommmentIdArray);

        $totcount = count($CommmentIdArray);
        $todelete = $totcount - $unread;

        while ($todelete-- > 0) {
            array_pop($CommmentIdArray);
        }

        $this->CacheUnread($CommmentIdArray);
    }

    protected $cid;
    protected $recentTime;
    protected $insertTime;

    public function DiscussionController_BeforeCommentMeta_Handler($Sender) {

        $CommmentIdArray = $this->GetCacheUnread();
        $lid = $this->GetCacheLastDid();


        $this->SessionInfo = Gdn::Session();
        $this->userID = $this->SessionInfo->UserID;

        $cid = $Sender->EventArguments['Comment']->CommentID;

        // display last posted icon
        if ($lid == $cid) {
            echo sprintf(' <img src="%s" class="LastIcon" title="last posted message in discussion" alt="last recent message in discussion" />', $this->GetWebResource('img/last-icon.png', FALSE, TRUE), $Key);
        }

/* ------------- Uncomment this block to display  icons for recent posts.
       
        // display recently posted icon
        $recentTime = time() - (5 * 3600 );
        $insertTime = strtotime($Sender->EventArguments['Comment']->DateInserted);

        if (($lid != $cid) && ($recentTime < $insertTime)) {
            echo sprintf(' <img src="%s" class="RecentIcon" title="recent comment" alt="recent comment" />', $this->GetWebResource('img/recent-yellow.png', FALSE, TRUE), $Key);
        }
*/   //---------------    Uncomment this block to display  icons for recent posts.  
        
        
        // display last posted icon
        if (($this->userID > 0) && (in_array($cid, $CommmentIdArray))) {

            echo sprintf(' <img src="%s" class="UnreadIcon" title="unread comment"  alt="unread comment" />', $this->GetWebResource('img/unread-icon.png', FALSE, TRUE), $Key);
        }
        
   
    }

   
   
}
       
       
      




