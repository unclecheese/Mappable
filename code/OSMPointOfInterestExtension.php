<?php 

class OSMPointOfInterestExtension extends DataExtension {
	private static $db = array(
		// No Big Int support yet so use big decimal
		'OpenStreetMapID' => 'Decimal(20,0)'
	);

	// given millions of possible POIs an index is handy
	private static $indexes = array(
        'OpenStreetMapID' => true
    );
}

