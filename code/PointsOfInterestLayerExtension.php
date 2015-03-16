<?php

class PointsOfInterestLayerExtension extends DataExtension {

	static $many_many = array(
			'PointsOfInterestLayers' => 'PointsOfInterestLayer'
	);

	static $belongs_many_many_extraFields = array(
			'PointsOfInterestLayers' => array(
				'SortOrder' => "Int"
			)
	);


	public function updateCMSFields(FieldList $fields) {
		$gridConfig2 = GridFieldConfig_RelationEditor::create();
		$gridConfig2->getComponentByType(
			'GridFieldAddExistingAutocompleter')->
			setSearchFields(array('Name')
		);
		$gridConfig2->getComponentByType('GridFieldPaginator')->setItemsPerPage(100);
		$gridField2 = new GridField("POI Layers", "POI Layers:",
			$this->owner->PointsOfInterestLayers(),
			$gridConfig2
		);
		$fields->addFieldToTab("Root.MapLayers", $gridField2);
	}

}
