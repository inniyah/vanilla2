<?php if (!defined('APPLICATION')) exit();
/**
* # Category Headings #
* 
* ### About ###
* Allows the setting of categories as headings, displaying the subcategories. 
* You can lock these category heading, to prevent posting.
* Comes with view templates, you can move to your theme.
* 
* ### Sponsor ###
* Special thanks to Grenade for making this happen.
*/

/**
 * Changelog:
 * v0.1.1b:Thu Mar  8 14:19:37 GMT 2012
 * - move CategoryHeadingJson to definition rather then inline javascript
 * v0.1,2b:Tue Jun 26 17:30:43 BST 2012
 * - panel items not included due to not setting view directly. 
 */
$PluginInfo['CategoryHeadings'] = array(
   'Name' => 'Category Headings',
   'Description' => 'Allows the setting of categories as headings, displaying the subcategories. You can lock these category heading, to prevent posting.  Comes with view templates, you can move to your theme.',
   'RequiredApplications' => array('Dashboard' => '>=2.0.18.1'),
   'Version' => '0.1.2b',
   'Author' => 'Paul Thomas',
   'AuthorEmail' => 'dt01pqt_pt@yahoo.com',
   'AuthorUrl' => 'http://www.vanillaforums.org/profile/x00'
);

class CategoryHeadings extends Gdn_Plugin {
	

	
	public function SettingsController_Render_Before($Sender){
		if(strtolower($Sender->RequestMethod)!='managecategories')
			return;
		
		$Sender->AddDefinition('Heading',T('Heading'));
		$Sender->AddDefinition('Lock',T('Lock'));
		
		$Sender->AddJsFile('catheadings.js','plugins/CategoryHeadings');
		$Sender->AddDefinition('CatHeadings',$this->CategoryHeadingJson());
		
		
	}
	
	public function SettingsController_CategoryHeadings_Create($Sender,$Args){
		$Sender->Permission('Garden.Settings.Manage');
		$Id = $Args[1];
		$Col = strtolower($Args[0]);
		$On = $Args[2]?1:0;
		if(!ctype_digit($Id) || $Id<1){
			die(json_encode(FALSE));
		}
		
		if($Col=='headcat'){
			$Column='Heading';
		}else if($Col=='lockcat'){
			$Column='LockCategory';
		}else{
			die(json_encode(FALSE));
		}
		
		Gdn::SQL()->Put('Category',array($Column=>$On),array('CategoryID'=>$Id));
		
		die(json_encode(TRUE));
	}
	
	public function CategoryChildren($CategoryID){
		$CategoryModel = new CategoryModel();
		if(!$CategoryModel->HasChildren($CategoryID))
			return FALSE;
		$Categories = $CategoryModel->GetSubtree($CategoryID);
		$CategoryFull = $CategoryModel->GetFull();
        $CategoryChildren=array();
        $RootDepth = $Categories[0]['Depth'];
        foreach ($Categories As $Category){
			if($Category['Depth']>$RootDepth){
				$Category['Depth']-=$RootDepth;
				$CategoryChildren[$Category['CategoryID']] = (Object)$Category;
			}
		}
		$CategoryChildrenOrdered=array();

		foreach($CategoryFull As $CategoryF){
			if(array_key_exists($CategoryF->CategoryID,$CategoryChildren))
				$CategoryChildrenOrdered[]=$CategoryChildren[$CategoryF->CategoryID];
				
		}	

		return new Gdn_DataSet($CategoryChildrenOrdered);
	}
	
	public function CategoryHeadingJson(){
		$Cats = array();
		$CategoryModel = new CategoryModel();
		$Categories = $CategoryModel->GetFull();
		foreach($Categories As $Cat)
			if($Cat->CategoryID>0)
				$Cats[$Cat->CategoryID]=array('heading'=>$Cat->Heading,'lock'=>$Cat->LockCategory);
			
		return json_encode($Cats);
	}
	
	public function PostController_Render_Before($Sender){
		$Sender->AddJsFile('disablecat.js','plugins/CategoryHeadings');
		$Sender->AddDefinition('CatHeadings',$this->CategoryHeadingJson());
	}
	
	public function DiscussionModel_BeforeSaveDiscussion_Handler($Sender,$Args){
        $CategoryID = $Args['FormPostValues']['CategoryID'];
		$CategoryModel = new CategoryModel();
		$Categories = $CategoryModel->GetFull();
		foreach($Categories As $Category)
			if($Category->CategoryID==$CategoryID && $Category->LockCategory){
				throw PermissionException('Vanilla.Discussions.Add');
			}
			
	}
	
	
	public function CategoriesController_Render_Before($Sender){
		if($Sender->SyndicationMethod !== SYNDICATION_NONE)
			return;
		
        $CategoryID = $Sender->CategoryID;
        if($CategoryID){

			$CategoryChildren = $this->CategoryChildren($CategoryID);
			if(!$CategoryChildren)
				return;
	
			$Sender->SetData('CategoryChildren',$CategoryChildren);
		}
		$ThemeViewLoc = CombinePaths(array(
			PATH_THEMES, $Sender->Theme,'views', 'categoryheadings'
		));
		if(file_exists($ThemeViewLoc.DS.strtolower($Sender->RequestMethod).'.php')){
			$Sender->View=$ThemeViewLoc.DS.strtolower($Sender->RequestMethod).'.php';
		}else{
			$Sender->View=$this->GetView(strtolower($Sender->RequestMethod).'.php');
		}
		
		$Sender->AddJsFile('categories.js','vanilla');
		$Sender->AddJsFile('discussions.js','vanilla');
		$Sender->AddJsFile('options.js','vanilla');
	}
	
    public function Setup() {
        $this->Structure();
    }

    public function Base_BeforeDispatch_Handler($Sender){
        if(C('Plugins.CategoryHeadings.Version')!=$this->PluginInfo['Version'])
            $this->Structure();
    }
    
    public function Structure() {
		
        Gdn::Structure()
            ->Table('Category')
            ->Column('Heading','int(4)',0)
            ->Column('LockCategory','int(4)',0)
            ->Set();
	}

}
