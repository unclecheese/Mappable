<?php

class NearestPOIPage extends Page {
	public static $has_one = array('PointsOfInterestLayer' => 'PointsOfInterestLayer');

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $field = DropdownField::create('PointsOfInterestLayerID', 'PointsOfInterestLayer',
	    	PointsOfInterestLayer::get()->map('ID', 'Title'))
                ->setEmptyString('-- Select one --');
        $fields->addFieldToTab('Root.Layer', $field);

	    return $fields;
	}
}

class NearestPOIPage_Controller extends Page_Controller {
	private static $allowed_actions = array('find');

	/*
	For a given point of interest layer, find the 25 nearest places to provided location
	 */
	public function find() {
		$params = $this->request->getVars();
		$latitude = $params['lat'];
		$longitude = $params['lng'];
		$sql = "SELECT DISTINCT poi.ID,Name,Lat,Lon, ( 6371 * acos( cos( radians({$latitude}) ) * cos( radians( Lat ) )
* cos( radians( Lon ) - radians({$longitude}) ) + sin( radians({$latitude}) ) * sin(radians(Lat)) ) ) AS distance
FROM PointOfInterest poi
INNER JOIN PointsOfInterestLayer_PointsOfInterest poilpoi
ON poilpoi.PointOfInterestID = poi.ID
WHERE PointsOfInterestLayerID={$this->PointsOfInterestLayerID}
HAVING distance < 25
ORDER BY distance
LIMIT 0 , 20;";

		$records = DB::query($sql);
		$result = new ArrayList();
		foreach ($records as $record) {
			$dob = new DataObject();
			//number_format((float)$number, 2, '.', '');
			$dob->Latitude = number_format((float)$record['Lat'],3,'.','');
			$dob->Longitude = number_format((float)$record['Lon'],3,'.','');
			$dob->Name = $record['Name'];
			$dob->Distance = number_format((float)$record['distance'],2,'.','');
			$mapurl = "http://maps.google.com?q={$dob->Latitude},{$dob->Longitude}";
			$dob->MapURL = $mapurl;
			$dirurl = "http://maps.google.com?saddr={$latitude},{$longitude}&daddr={$dob->Latitude},{$dob->Longitude}";
			$dob->DirURL = $dirurl;
			$result->push($dob);
		}

		$vars = new ArrayData(array('Nearest' => $result, 'Action' => $this->Action));
		$this->Nearest = $result;
		return array();
	}
}
