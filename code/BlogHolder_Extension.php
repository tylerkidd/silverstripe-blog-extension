<?php

class BlogHolder_Extension extends DataExtension {

	function updateCMSFields(FieldList $fields) {
		$BlogID = $this->getOwner()->ID;
		$gridConfig = GridFieldConfig_RelationEditor::create();
		//$gridConfig->addComponent(new GridFieldSortableRows('SortOrder'));
		$GridField = new GridField('BlogEntries', 'Blog Entries', DataList::create('BlogEntry')->where("ParentID = $BlogID")->sort("Date DESC") , $gridConfig);
		$fields->addFieldToTab("Root.BlogEntries", $GridField); // add the grid field to a tab in the CMS
	}

	/**
	 * This just prevents BlogEntries from showing up in the SiteTree TreeView
	 **/
	function AllChildrenIncludingDeleted(){
		return false;
	}

}