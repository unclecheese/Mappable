<?php

class MapAPITest extends SapphireTest {

	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension')
		);
		parent::setupOnce();
	}

	public function setUp() {
		MapUtil::reset();
		parent::setUp();
	}

	public function testSetClusterer() {
		$map = $this->getMap();
		$map->setClusterer(true);
		$html = $map->forTemplate();
		$this->assertContains('data-clusterergridsize=50', $html);
		$this->assertContains('data-clusterermaxzoom=17', $html);
		$this->assertContains('data-useclusterer=1', $html);

		$map = $this->getMap();
		$map->setClusterer(true, 60, 14);
		$html = $map->forTemplate();
		$this->assertContains('data-clusterergridsize=60', $html);
		$this->assertContains('data-clusterermaxzoom=14', $html);
		$this->assertContains('data-useclusterer=1', $html);

		$map = $this->getMap();
		$map->setClusterer(false);
		$html = $map->forTemplate();
		$this->assertContains('data-useclusterer=false', $html);
		$this->assertContains('data-clusterergridsize=50', $html);
		$this->assertContains('data-clusterermaxzoom=17', $html);
	}

	/*
	Toggle as to whether or not to include a style= attribute with width/height
	 */
	public function testSetShowInlineMapDivStyle() {
		$map = $this->getMap();
		$map->setShowInlineMapDivStyle(true);
		$html = $map->forTemplate();
		$expected = 'style="width:100%; height: 400px;"';
		$this->assertContains($expected, $html);

		$map->setShowInlineMapDivStyle(false);
		$html = $map->forTemplate();
		$this->assertNotContains($expected, $html);
	}

	public function testSetAdditionalCSSClasses() {
		$map = $this->getMap();
		$map->setAdditionalCSSClasses('bigMap shadowMap');
		$html = $map->forTemplate();
		$expected = 'class="bigMap shadowMap mappable"';
		$this->assertContains($expected, $html);
		$map->setAdditionalCSSClasses('bigMap shadowMap');
	}


	public function testSetMapStyle() {
		$style = <<<STYLE
[{
	"featureType": "landscape",
	"stylers": [{
		"hue": "#FFBB00"
	}, {
		"saturation": 43.400000000000006
	}, {
		"lightness": 37.599999999999994
	}, {
		"gamma": 1
	}]
}]
STYLE;
		$map = $this->getMap();
		$map->setMapStyle($style);
		$html = $map->forTemplate()->getValue();
		$expected = <<<HTML


<div id="google_map_1" data-google-map-lang="en"  style="width:100%; height: 400px;"
 class=" mappable"
data-map
data-centre='{"lat":48.856614,"lng":2.3522219}'
data-zoom=9
data-maptype='road'
data-allowfullscreen='1'
data-clusterergridsize=50,
data-clusterermaxzoom=17,
data-enableautocentrezoom=false
data-enablewindowzoom=false
data-infowindowzoom=13
data-mapmarkers='[]'
data-defaulthidemarker=false
data-lines='[]'
data-kmlfiles='[]'
data-mapstyles='[{
	"featureType": "landscape",
	"stylers": [{
		"hue": "#FFBB00"
	}, {
		"saturation": 43.400000000000006
	}, {
		"lightness": 37.599999999999994
	}, {
		"gamma": 1
	}]
}]'
data-useclusterer=false
>
</div>

HTML;
		$this->assertEquals($expected, $html);
		$map->setMapStyle(null);
	}

	public function testSetDivId() {
		$map = $this->getMap();
		$map->setDivId('mymapid');
		$html = $map->forTemplate();
		$expected = '<div id="mymapid" data-google-map-lang="en"  style=';
		$this->assertContains($expected, $html);
	}

	public function testSetSize() {
		$map = $this->getMap();
		$map->setSize('432px', '1234px');
		$html = $map->forTemplate();
		$this->assertContains('style="width:432px; height: 1234px;"', $html);
	}

	public function testSetLang() {
		Config::inst()->update('Mappable', 'language', 'fr');
		$map = $this->getMap();
		$html = $map->forTemplate();
		$this->assertContains(
			'<div id="google_map_1" data-google-map-lang="fr" ',
			$html
		);
	}


	public function testSetZoom() {
		$map = $this->getMap();
		$map->setZoom(4);
		$html = $map->forTemplate();
		$this->assertContains('data-zoom=4', $html);
		$map->setZoom(12);
		$html = $map->forTemplate();
		$this->assertContains('data-zoom=12', $html);
	}

	public function testSetInfoWindowZoom() {
		$map = $this->getMap();
		$map->setInfoWindowZoom(4);
		$html = $map->forTemplate();
		$this->assertContains('data-infowindowzoom=4', $html);
		$map->setInfoWindowZoom(12);
		$html = $map->forTemplate();
		$this->assertContains('data-infowindowzoom=12', $html);

	}

	public function testSetEnableWindowZoom() {
		$map = $this->getMap();
		$map->setEnableWindowZoom(false);
		$html = $map->forTemplate();
		$this->assertContains('data-enablewindowzoom=false', $html);
		$map->setEnableWindowZoom(true);
		$html = $map->forTemplate();
		$this->assertContains('data-enablewindowzoom=1', $html);
	}

	public function testSetEnableAutomaticCenterZoom() {
		$map = $this->getMap();
		$map->setEnableAutomaticCenterZoom(true);
		$html = $map->forTemplate();
		$this->assertContains('data-enableautocentrezoom=1', $html);
	}

	public function testSetNoLocation() {
		$map = $this->getMap();
		$html = $map->forTemplate();
		$this->assertContains(
			'data-centre=\'{"lat":48.856614,"lng":2.3522219}\'',
			$html
		);
	}

	/**
	 * setCentre is mis-named, as the method expects text for a geocoder
	 */
	public function testSetCenter() {
		$map = $this->getMap();
		$map->setCenter('Klong Tan, Bangkok, Thailand');
		$html = $map->forTemplate();

		//coordinates of Klong Tan in Bangkok
		$expected = 'data-centre=\'{"lat":13.7243075,"lng":100.5718086}';
		$this->assertContains($expected, $html);
		$map->setCenter('Paris, France');
	}


	public function testSetLatLongCenter() {
		$map = $this->getMap();
		$llc = array('lat' => -23.714, 'lng' => 47.419);
		$map->setLatLongCenter($llc);
		$html = $map->forTemplate();
		$expected = "data-centre='{\"lat\":-23.714,\"lng\":47.419}'";
		$this->assertContains($expected, $html);

		// now test error conditions
		try {
			$map->setLatLongCenter('This is not a coordinate');
			$this->fail('Should not be able to set coordinate as text');
		} catch (InvalidArgumentException $e) {
			$message = $e->getMessage();
			$this->assertEquals(
				'Center must be an associative array containing lat,lng',
				$message
			);
		}

		try {
			$badKeys = array('lat' => 47.2, 'wibble' => 76.10);
			$map->setLatLongCenter($badKeys);
			$this->fail('Should not be able to set coordinate as text');
		} catch (InvalidArgumentException $e) {
			$message = $e->getMessage();

			$this->assertEquals(
				'Keys provided must be lat, lng',
				$message
			);
		}


	}


	public function testSetMapType() {
		$map = $this->getMap();

		$mapTypes = array(
			'road' => 'road',
			'satellite' => 'satellite',
			'hybrid' => 'hybrid',
			'terrain' => 'terrain',
			'google.maps.MapTypeId.ROADMAP' => 'road',
			'google.maps.MapTypeId.SATELLITE' => 'satellite',
			'google.maps.MapTypeId.G_HYBRID_MAP' => 'hybrid',
			'google.maps.MapTypeId.G_PHYSICAL_MAP' => 'terrain',
			'custom_layer' => 'custom_layer'
		);

		foreach (array_keys($mapTypes) as $mapType) {
			$map->setMapType($mapType);
			$expected = "data-maptype='".$mapTypes[$mapType]."'";
			$html = $map->forTemplate();
			$this->assertContains($expected, $html);
		}
	}


	public function testSetAllowFullScreen() {
		$map = $this->getMap();
		$map->setAllowFullScreen(false);
		$html = $map->forTemplate();

		$this->assertContains("data-allowfullscreen='false'", $html);

		$map->setAllowFullScreen(true);
		$html = $map->forTemplate();
		$this->assertContains("data-allowfullscreen='1'", $html);

		// deal with the null calse
		$map->setAllowFullScreen(null);
		$html = $map->forTemplate();
		$expected = Config::inst()->get('Mappable', 'allow_full_screen');
		$this->assertContains("data-allowfullscreen='{$expected}'", $html);
	}

	public function testMapWithMarkers() {
		$config = Config::inst();

		$map = $this->getMapMultipleItems();
		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":23,"longitude":78,"html":"'
				  . 'MEMBER: Fred Bloggs","category":"default","icon":false},{"latitude'
				  . '":-12,"longitude":42.1,"html":"MEMBER: Kane Williamson","category"'
				  . ':"default","icon":false}]\'';
		$this->assertContains($expected, $html);
	}


	public function testMapWithMarkersDifferentCategory() {
		$this->markTestSkipped('TODO');
	}


	public function testSetDefaultHideMarker() {
		$map = $this->getMapMultipleItems();
		$map->setDefaultHideMarker(false);
		$html = $map->forTemplate();
		$this->assertContains(
			'data-defaulthidemarker=false',
			$html
		);

		$map = $this->getMapMultipleItems();
		$map->setDefaultHideMarker(true);
		$html = $map->forTemplate();
		$this->assertContains(
			'data-defaulthidemarker=1',
			$html
		);
	}

	public function testGetContent() {
		$map = $this->getMap();
		$filepath = 'file://'.Director::baseFolder()
				  . '/mappable/tests/kml/example.kml';
		$content = $map->getContent($filepath);
		$textHash = hash('ripemd160', $content);
		$fileHash = hash_file('ripemd160', $filepath);
		$this->assertEquals($fileHash, $textHash);
	}

	public function testGeocoding() {
		$map = $this->getMap();
		$location = $map->geocoding("Nonthaburi, Thailand");
		$expected = array(
			'lat' => 13.8621125,
			'lon' => 100.5143528,
    		'geocoded' => true
		);
		$this->assertEquals($expected, $location);
	}

	public function testGeocodingNoResultsFound() {
		$map = $this->getMap();
		$location = $map->geocoding("aasdfsafsfdsfasdf");
		$expected = array();
		$this->assertEquals($expected, $location);
	}

	public function testAddMarkerByAddress() {
		//$address, $content = '', $category = '', $icon = ''
		$map = $this->getMap();
		$map->addMarkerByAddress(
			'Koh Kred, Nonthaburi, Thailand',
			'Small island in the Chao Phraya river',
			'testing',
			'http://www.test.com/icon.png'
		);
		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":13.9114455,"longitude":100.4761897,"html":"Small island in the Chao Phraya river","category":"testing","icon":"http://www.test.com/icon.png"}]\'';
		$this->assertContains($expected, $html);
	}


	public function testAddArrayMarkerByCoords() {
		$map = $this->getMap();

		$markerArray = array();
		$marker1 = array(48.2, 27, 'Description marker 1', 'Marker Test', '');
		$marker2 = array(-12.2, 47, 'Description marker 2', 'Marker Test', '');

		array_push($markerArray, $marker1);
		array_push($markerArray, $marker2);

		$map->addArrayMarkerByCoords($markerArray);
		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":48.2,"longitude":27,"html":"Description marker 1","category":"","icon":""},{"latitude":-12.2,"longitude":47,"html":"Description marker 2","category":"","icon":""}]\'';
		$this->assertContains($expected, $html);
	}

	public function testAddMarkerByCoords() {
		$map = $this->getMap();
		$map->addMarkerByCoords(
			13.91,
			100.47,
			'Description of marker',
			'testing',
			'http://www.test.com/icon.png'
		);
		$html = $map->forTemplate();
		$expected =
		'data-mapmarkers=\'[{"latitude":13.91,"longitude":100.47,"html":"Description of marker","category":"testing","icon":"http://www.test.com/icon.png"}]';

		$this->assertContains($expected, $html);
	}


	public function testAddMarkerAsObject() {
		$map = $this->getMap();
		$member = new Member();
		$member->FirstName = 'Test';
		$member->Surname = 'User';
		$member->Lat = 24.2;
		$member->Lon = -40;
		$member->write();
		$params = array();
		$map->addMarkerAsObject(
			$member,
			$params
		);

		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":24.2,"longitude":-40,"html":"MEMBER: Test User","category":"default","icon":false}]\'';
		$this->assertContains($expected, $html);
	}


	public function testAddMarkerThatIsMappableAsObject() {
		$map = $this->getMap();
		$member = new MappableExampleClass();
		//$member->write();
		$params = array();
		$map->addMarkerAsObject(
			$member,
			$params
		);

		$html = $map->forTemplate();
		$expected = 'data-mapmarkers=\'[{"latitude":13.4,"longitude":100.7,"html":"example content","category":"default","icon":null}]\'';
		$this->assertContains($expected, $html);
	}


	public function testConnectPoints() {
		$members = $this->getGeolocatedMembers();
		$member1 = $members->pop();
		$member2 = $members->pop();
		$map = $this->getMap();
		$map->connectPoints($member1, $member2);
		$html = $map->forTemplate();
		$expected = 'data-lines=\'[{"lat1":-12,"lon1":42.1,"lat2":23,"lon2":78,"color":"#FF3300"}]\'';
		$this->assertContains($expected, $html);
	}


	public function testAddKML() {
		$map = $this->getMap();
		$map->addKml('http://www.test.com/route1.kml');
		$map->addKml('http://www.test.com/route2.kml');
		$html = $map->forTemplate();
		$expected = 'data-kmlfiles=\'["http://www.test.com/route1.kml","http://www.test.com/route2.kml"]\'';
		$this->assertContains($expected, $html);
	}


	public function testAddLine() {
		$map = $this->getMap();
		$map->addLine(
			array(13, 101),
			array(13.2, 101.4),
			'#F32'
		);

		$map->addLine(
			array(13.2, 101.4),
			array(14.2, 99.8)
		);

		$html = $map->forTemplate();
		$expected = 'data-lines=\'[{"lat1":13,"lon1":101,"lat2":13.2,"lon2":101.4,"color":"#F32"},{"lat1":13.2,"lon1":101.4,"lat2":14.2,"lon2":99.8,"color":"#FF3300"}]\'';
		$this->assertContains($expected, $html);
	}

	/**
	 * This tests out a specific case of passing null for template values
	 */
	public function testProcessTemplate() {
		$map = $this->getMap();
		$html = $map->processTemplateHTML('Map', null);
		$expected = <<<HTML


<div id=""
data-map
data-centre=''
data-zoom=
data-maptype=''
data-allowfullscreen=''
data-clusterergridsize=,
data-clusterermaxzoom=,
data-enableautocentrezoom=
data-enablewindowzoom=
data-infowindowzoom=
data-mapmarkers=''
data-defaulthidemarker=
data-lines=''
data-kmlfiles=''
data-mapstyles=''
data-useclusterer=
>
</div>

HTML;
		$this->assertEquals($expected, $html->getValue());
	}

	private function getMap() {
		$instance = new Member();
		return $instance->getRenderableMap();
	}

	private function getMapMultipleItems() {
		$members = $this->getGeolocatedMembers();
		return $members->getRenderableMap();
	}

	private function getGeolocatedMembers() {
		$members = new ArrayList();

		$member1 = new Member();
		$member1->Lat = 23;
		$member1->Lon = 78;
		$member1->MapPinEdited = true;
		$member1->FirstName = 'Fred';
		$member1->Surname = 'Bloggs';
		$member1->write();
		$members->push($member1);

		$member2 = new Member();
		$member2->Lat = -12;
		$member2->Lon = 42.1;
		$member2->MapPinEdited = true;
		$member2->FirstName = 'Kane';
		$member2->Surname = 'Williamson';
		$member2->write();
		$members->push($member2);

		return $members;
	}

}



// basic implementation of Mappable interface for some of the tests
class MappableExampleClass extends ViewableData implements TestOnly, Mappable {

	public function getMappableLatitude() {
		return 13.4;
	}

	public function getMappableLongitude() {
		return 100.7;
	}

	public function getMappableMapPin() {
		return null;
	}

	public function getMappableMapContent() {
		return 'example content';
	}
}
