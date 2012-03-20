<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['CommentsRSS'] = array(
   'Description' => 'Creates an RSS feed of all comments and discussions',
   'Version' => '1.0',
   'Author' => "Jonathan Pautsch",
   'AuthorEmail' => 'themes@secondwindprojects.com',
   'AuthorUrl' => 'http://secondwindprojects.com/'
);

class CommentRSS extends Gdn_Plugin
{
	public function Setup()
	{
		return TRUE;
	}
   
	/* Returns all posts, can be filtered by category */
	public function GetAllComments($CategoryID)
	{
		$Limit = Gdn::Config('Vanilla.Comments.PerPage', 50);
		$Offset = 0;
		
		// Retrieves all comments
		Gdn::SQL()
			->Select('d.Name as Name')
			->Select('d.DiscussionID as DiscussionID')
			->Select('c.DateInserted as DateInserted')
			->Select('c.Body as Body')
			->Select('c.Format as Format')
            ->Select('iu.Name as FirstName')
			->Select('c.CommentID as CommentID')
			->From('Comment c')
            ->Join('User iu', 'c.InsertUserID = iu.UserID', 'left')
			->Join('Discussion d', 'c.DiscussionID = d.DiscussionID', 'left');
			
			if($CategoryID)
				Gdn::SQL()->Where('d.CategoryID', $CategoryID);
			
			Gdn::SQL()->Limit($Limit, $Offset)
			->OrderBy('c.DateInserted', 'desc');
			
		$CommentSql = Gdn::SQL()->GetSelect();
			
		Gdn::SQL()->Reset();

		// Retrieves all discussions
		Gdn::SQL()
			->Select('d.Name as Name')
			->Select('d.DiscussionID as DiscussionID')
			->Select('d.DateInserted as DateInserted')
			->Select('d.Body as Body')
			->Select('d.Format as Format')
			->Select('iu.Name as FirstName')
			->Select('d.Name as CommentID')
			->From('Discussion d')
			->Join('User iu', 'd.InsertUserID = iu.UserID', 'left');
			
			if($CategoryID)
				Gdn::SQL()->Where('d.CategoryID', $CategoryID);
			
			Gdn::SQL()->Limit($Limit, $Offset)
			->OrderBy('d.DateInserted', 'desc');
			
		$DiscussionSql = Gdn::SQL()->GetSelect();
		
		// Unions comments and discussions together into one list
		$Sql = "(".$CommentSql.")\nunion all\n(".$DiscussionSql.")\nORDER BY DateInserted DESC";
			
		$Result = Gdn::SQL()->Query($Sql);
      
		return $Result;
	}
	
	/* Renders an RSS feed of comments and discussions together
	   /discussions/comments/all/feed.rss gives a list of all comments
	   /discussions/comments/[categoryname]/feed.rss gives a list of comments within a category */
	public function DiscussionsController_Comments_Create($Sender, $Args)
	{
		if ($Args[0] == 'all')
		{
			$Comments = $this->GetAllComments();
			$Title = Gdn::Config('Garden.Title');
		}
		else
		{
			$Category = Gdn::SQL()
				->Select('c.CategoryID, c.Name')
				->From('Category c')
				->Where('c.UrlCode',$Args[0])
				->Get()
				->FirstRow(DATASET_TYPE_ARRAY);
			$Comments = $this->GetAllComments($Category['CategoryID']);
			$Title = Gdn::Config('Garden.Title').' > ' . $Category['Name'];
		}
		
		if ($Sender->Head)
			$Sender->Head->Title($Title);
			
		$Sender->View = 'index';
		
		$CountComments = sizeof($Comments);
		$Sender->SetData('CountDiscussions', $CountComments);
		$Sender->SetData('DiscussionData', $Comments, TRUE);
		$Sender->SetData('IsComment', TRUE);
      
		// Set timezone
		$CurrentUser = Gdn::Session()->User;
		if (is_object($CurrentUser))
		{
			$ClientHour = $CurrentUser->HourOffset + date('G', time());
			$Sender->AddDefinition('SetClientHour', $ClientHour);
		}
	  
		$Sender->Render();
	}
}

?>