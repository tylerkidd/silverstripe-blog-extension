<?php

class BlogEntry_Extension extends DataExtension {
	
	static $db = array( 
		'ContentSummary'	=> 'HTMLText',
		'PublishStatus'		=> 'Boolean',
	);
	
	static $summary_fields = array(
		'Title' => 'Title',
		'Author' => 'Author',
		'Date' => 'Date',
		'PublishedStatusFormatted' => 'Status',
	);
	
	function getPublishedStatusFormatted(){
		return $this->getOwner()->PublishedStatus ? 'Published' : 'Draft';
	}
	
	function onBeforeDelete(){
		parent::onBeforeDelete();

		$owner = $this->getOwner();
		if(!isset($_SESSION['skip_write'])){
			$_SESSION['skip_write'] = 1; // a little hacky, but it works...
			$owner->deleteFromStage('Live');
			$owner->flushCache();				
		}else{
			unset($_SESSION['skip_write']);
		}
	}
	
	function onBeforeWrite(){
		parent::onBeforeWrite();
		
		$owner = $this->getOwner();
		if(!$owner->Date){
			$owner->Date = date('Y-m-d H:i:s', strtotime('now'));
		}

	}
			
	function onAfterWrite(){
		parent::onAfterWrite();
		
		$owner = $this->getOwner();

		if(!$owner->Date){
			$owner->Date = date('Y-m-d H:i:s', strtotime('now'));
		}

		if(!isset($_SESSION['skip_write'])){
			$_SESSION['skip_write'] = 1; // a little hacky, but it works...

			if($owner->PublishStatus == 1){
				$owner->PublishStatus = 0;
				$owner->publish('Stage', 'Live');
				
			}else{
				$owner->deleteFromStage('Live');
				$owner->flushCache();
			}
			
		}else{
			unset($_SESSION['skip_write']);
		}
	}

	function updateCMSFields(FieldList $fields) {
		$owner = $this->getOwner();
		
		
		$fields->insertBefore(new HTMLEditorField("ContentSummary","Summary"),'Content');
		$fields->insertBefore(new DropdownField("ParentID","Blog", DataList::create('BlogTree')->map('ID','Title')),'Content');
		$fields->insertBefore(new DropdownField("PublishStatus","Status", array(0 => 'Draft', 1 => 'Published')),'Content');

	}

}