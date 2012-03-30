Read me for UnreadIcon plugin

to display additional recent comment icon (approx 5 hours) that will display whether user is logged in or not.

uncomment the block in class.unreadicon.php.
 


before================================================== 


/* ------------- Uncomment this block to display  icons for recent posts.
       

         
       
        // display recently posted icon
        $recentTime = time() - (5 * 3600 );
        $insertTime = strtotime($Sender->EventArguments['Comment']->DateInserted);

        if (($lid != $cid) && ($recentTime < $insertTime)) {
            echo sprintf(' <img src="%s" class="RecentIcon" title="recent comment" alt="recent comment" />', $this->GetWebResource('img/recent-yellow.png', FALSE, TRUE), $Key);
        }
*/   //---------------    Uncomment this block to display  icons for recent posts. 



after==============================================================

  // display recently posted icon
        $recentTime = time() - (5 * 3600 );
        $insertTime = strtotime($Sender->EventArguments['Comment']->DateInserted);

        if (($lid != $cid) && ($recentTime < $insertTime)) {
            echo sprintf(' <img src="%s" class="RecentIcon" title="recent comment" alt="recent comment" />', $this->GetWebResource('img/recent-yellow.png', FALSE, TRUE), $Key);
        }
        
        
