<?php

class PointOfInterest extends DataObject {
	private static $description = 'Represents a point of interest on a map, e.g. railway station';

	private static $belongs_many_many = array('PointsOfInterestLayer' => 'PointsOfInterestLayer');

	private static $db = array(
		'Name' => 'Varchar'
	);

	private static $summary_fields = array('Name');

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab('Root.Main', new TextField('Name', 'Name of the item on the map'));

		$layers = $this->PointsOfInterestLayer();
		$ids = array();
		foreach ($layers->getIterator() as $layer) {
			array_push($ids, $layer->ID);
		}
		$csv = implode(',', $ids);

		if ($this->ShowGuideMarkers && strlen($csv) > 0) {
			$sql = "ID IN (SELECT DISTINCT  PointOfInterestID from ";
			$sql .= "PointsOfInterestLayer_PointsOfInterest WHERE PointsOfInterestLayerID ";
			$sql .= "IN ($csv))";

			$pois = PointOfInterest::get()->where($sql);
			$this->owner->getMapField()->setGuidePoints($pois);
		}

	    return $fields;
	}

	/*
	FIXME - possible to populate layer id when adding a new record?
	public function populateDefaults() {
	    parent::populateDefaults();
	}
	*/
}
