<?php if (!defined('APPLICATION')) exit();
/**
* 
* # File Upload Detect #
* 
* ### About ###
*  This a fork of [FileUpload](http://vanillaforums.org/addon/fileupload-plugin) 
*  by Tim Gunter and released under the same GPL licence.
*  
*  At the moment this links [Open Icon Library](http://openiconlibrary.sourceforge.net/) 
*  "Oxygen" icons to uploads, with a file mapping.  So it will display the attachments 
*  with the relevant graphic based on file extension. 
*  
*  ### Important ###
*  It is a good idea not only to disable other file upload plugins, but also move their folder, into another folder 
*  This will reduce the chance of conflicts by removing those from the plugins folder.
*
* ### Sponsor ###
* Special thanks to mikizzi for making this happen.
* 
*/

class MediaModel extends VanillaModel {

   static $FileIconDef =array();
   public function __construct() {
      parent::__construct('Media');
	/* File Upload Detect - inlcude mapping*/
	require PATH_PLUGINS.DS.'FileUpload'.DS.'images'.DS.C('Plugins.FileUpload.DetectLibrary','oxygen-style').'-fileicons.php';
	
      self::$FileIconDef=$FileIconDef;
      /* File Upload Detect*/
   }
   
   public function GetID($MediaID) {
      $this->FireEvent('BeforeGetID');
      $Data = $this->SQL
         ->Select('m.*')
         //->Select('iu.*')
         ->From('Media m')
         //->Join('User iu', 'm.InsertUserID = iu.UserID', 'left') // Insert user
         ->Where('m.MediaID', $MediaID)
         ->Get()
         ->FirstRow();
		
		return $Data;
   }
   
   /**
    * If passed path leads to an image, return size
    *
    * @param string $Path Path to file.
    * @return array [0] => Height, [1] => Width.
    */
   public static function GetImageSize($Path) {
      // Static FireEvent for intercepting non-local files.
      $Sender = new stdClass();
      $Sender->Returns = array();
      $Sender->EventArguments = array();
      $Sender->EventArguments['Path'] =& $Path;
      $Sender->EventArguments['Parsed'] = Gdn_Upload::Parse($Path);
      Gdn::PluginManager()->CallEventHandlers($Sender, 'FileUploadDetectPlugin', 'CopyLocal');
      
      //die($Path);
   
      if (!in_array(strtolower(pathinfo($Path, PATHINFO_EXTENSION)), array('bmp', 'gif', 'jpg', 'jpeg', 'png')))
         return array(0, 0);

      $ImageSize = @getimagesize($Path);
      if (is_array($ImageSize))
         return array($ImageSize[0], $ImageSize[1]);
      return array(0, 0);
   }
   
   public function PreloadDiscussionMedia($DiscussionID, $CommentIDList) {
      $this->FireEvent('BeforePreloadDiscussionMedia');
      
      $StartT = microtime(true);
      $Data = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->BeginWhereGroup()
            ->Where('m.ForeignID', $DiscussionID)
            ->Where('m.ForeignTable', 'discussion')
         ->EndWhereGroup()
         ->OrOp()
         ->BeginWhereGroup()
            ->WhereIn('m.ForeignID', $CommentIDList)
            ->Where('m.ForeignTable', 'comment')
         ->EndWhereGroup()
         ->Get();

      // Assign image heights/widths where necessary.
      $Data2 = $Data->Result();
      foreach ($Data2 as &$Row) {
         if ($Row->ImageHeight === NULL || $Row->ImageWidth === NULL) {
            list($Row->ImageWidth, $Row->ImageHeight) = self::GetImageSize(MediaModel::PathUploads().'/'.ltrim($Row->Path, '/'));
            $this->SQL->Put('Media', array('ImageWidth' => $Row->ImageWidth, 'ImageHeight' => $Row->ImageHeight), array('MediaID' => $Row->MediaID));
         }
      }
/*
      $DiscussionData = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->Where('m.ForeignID', $DiscussionID)
         ->Where('m.ForeignTable', 'discussion')
         ->Get()->Result(DATASET_TYPE_ARRAY);

      $CommentData = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->WhereIn('m.ForeignID', $CommentIDList)
         ->Where('m.ForeignTable', 'comment')
         ->Get()->Result(DATASET_TYPE_ARRAY);
      
      $Data = array_merge($DiscussionData, $CommentData);
*/

		return $Data;
   }
   
   public function Delete($Media, $DeleteFile = TRUE) {
      $MediaID = FALSE;
      if (is_a($Media, 'stdClass'))
         $Media = (array)$Media;
            
      if (is_numeric($Media)) 
         $MediaID = $Media;
      elseif (array_key_exists('MediaID', $Media))
         $MediaID = $Media['MediaID'];
      
      if ($MediaID) {
         $Media = $this->GetID($MediaID);
         $this->SQL->Delete($this->Name, array('MediaID' => $MediaID), FALSE);
         
         if ($DeleteFile) {
            $DirectPath = MediaModel::PathUploads().DS.GetValue('Path',$Media);
            if (file_exists($DirectPath))
               @unlink($DirectPath);
         }
      } else {
         $this->SQL->Delete($this->Name, $Media, FALSE);
      }
   }
   
   public function DeleteParent($ParentTable, $ParentID) {
      $MediaItems = $this->SQL->Select('*')
         ->From($this->Name)
         ->Where('ForeignTable', strtolower($ParentTable))
         ->Where('ForeignID', $ParentID)
         ->Get()->Result(DATASET_TYPE_ARRAY);
         
      foreach ($MediaItems as $Media) {
         $this->Delete(GetValue('MediaID',$Media));
      }
   }
   
   /**
    * Return path to upload folder.
    *
    * @return string Path to upload folder.
    */
   public static function PathUploads() {
      if (defined('PATH_LOCAL_UPLOADS'))
         return PATH_LOCAL_UPLOADS;
      else
         return PATH_UPLOADS;
   }

   public static function ThumbnailHeight() {
      static $Height = FALSE;

      if ($Height === FALSE)
         $Height = C('Plugins.FileUpload.ThumbnailHeight', 128);
      return $Height;
   }

   public static function ThumbnailWidth() {
      static $Width = FALSE;

      if ($Width === FALSE)
         $Width = C('Plugins.FileUpload.ThumbnailWidth', 256);
      return $Width;
   }

/* File Upload Detect - Auto Preview Method*/
    public static function AutoPreviewImage($FileName, $Default='misc.png'){
	 $F='';
	 $PreviewImage='plugins/FileUpload/images/'.C('Plugins.FileUpload.DetectLibrary','oxygen-style').'/'.$Default.'?'.$FileName;
	 foreach(self::$FileIconDef As $Icon => $Formats)
		foreach ($Formats As $Format){
			$Format='.'.$Format;
			If(strcasecmp(substr($FileName, strlen($FileName) - strlen($Format)),$Format)===0 && substr_count($Format,'.')>substr_count($F,'.')){
				 $PreviewImage='plugins/FileUpload/images/'.C('Plugins.FileUpload.DetectLibrary','oxygen-style').'/'.$Icon;
				 $F=$Format;
			}
		}
	return $PreviewImage;
  }
   /* File Upload Detect*/

   public static function ThumbnailUrl($Media) {
      $Width = GetValue('ImageWidth', $Media);
      $Height = GetValue('ImageHeight', $Media);

      if (!$Width || !$Height)
         return self::AutoPreviewImage(GetValue('Path', $Media));/* File Upload Detect - Auto Preview Method*/

      $RequiresThumbnail = FALSE;
      if (self::ThumbnailHeight() && $Height > self::ThumbnailHeight())
         $RequiresThumbnail = TRUE;
      elseif (self::ThumbnailWidth() && $Width > self::ThumbnailWidth())
         $RequiresThumbnail = TRUE;

      $Path = ltrim(GetValue('Path', $Media), '/');
      if ($RequiresThumbnail) {
         $ThumbPath = MediaModel::PathUploads()."/thumbnails/$Path";
         if (file_exists(MediaModel::PathUploads()."/thumbnails/$Path"))
            $Result = "/uploads/thumbnails/$Path";
         else
            $Result = "/utility/thumbnail/$Path";
      } else {
         $Result = "/uploads/$Path";
      }
      return $Result;
   }

   public static function Url($Media) {
      static $UseDownloadUrl = NULL;
      if ($UseDownloadUrl === NULL)
         $UseDownloadUrl = C('Plugins.FileUpload.UseDownloadUrl');

      if (is_string($Media)) {
         $SubPath = $Media;
         if (method_exists('Gdn_Upload', 'Url'))
            $Url = Gdn_Upload::Url("/$SubPath");
         else
            $Url = "/uploads/$SubPath";
      } elseif ($UseDownloadUrl) {
         $Url = '/discussion/download/'.GetValue('MediaID', $Media).'/'.rawurlencode(GetValue('Name', $Media));
      } else {
         $SubPath = ltrim(GetValue('Path', $Media), '/');
         if (method_exists('Gdn_Upload', 'Url'))
            $Url = Gdn_Upload::Url("/$SubPath");
         else
            $Url = "/uploads/$SubPath";
      }

      return $Url;
   }
   
}