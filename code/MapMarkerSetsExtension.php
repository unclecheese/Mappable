<?php

class MapMarkerSetsExtension extends DataExtension {

	static $many_many = array(
		'MapMarkerSets' => 'MapMarkerSet'
	);


	static $belongs_many_many_extraFields = array(
		'MapMarkerSets' => array(
			'SortOrder' => "Int"
		)
	);


	public function updateCMSFields(FieldList $fields) {
		$gridConfig2 = GridFieldConfig_RelationEditor::create();
		$gridConfig2->getComponentByType(
			'GridFieldAddExistingAutocompleter')->setSearchFields(array('Title')
		);
		$gridConfig2->getComponentByType('GridFieldPaginator')->setItemsPerPage(100);

		$gridField2 = new GridField("MapMarkerSets",
			"MapMarker Sets",
			$this->owner->MapMarkerSets(),
			$gridConfig2
		);
		$fields->addFieldToTab("Root.MapMarkerSets", $gridField2);
	}

}
