<?php

class MapUtilTest extends SapphireTest {

	/*
	Other tests:
	1) List, ArrayList, DataList, null for get_map
	2) Negative and zero map sizes
	3) Invalid map type


	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension')
		);
		parent::setupOnce();
	}
*/
	public function setUp() {
		MapUtil::reset();
		parent::setUp();
	}

	public function test_set_api_key_string() {
		MapUtil::set_api_key('PRETENDAPIKEY');
		$html = $this->htmlForMap();
		$this->assertContains(
			' data-google-map-key="PRETENDAPIKEY" data-google-map-lang="en"',
			$html
		);

		$html = $this->htmlForMap();
		$this->assertNotContains(
			' data-google-map-key="PRETENDAPIKEY" data-google-map-lang="en"',
			$html
		);
	}

	public function test_set_api_key_host_array() {
		$url = Director::absoluteBaseURL();
		// remove http and https
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$parts = explode('/', $url);
		$host = $parts[0];
		$key = array($host => 'PRETENDAPIKEY');
		MapUtil::set_api_key($key);
		$html = $this->htmlForMap();
		$this->assertContains(
			' data-google-map-key="PRETENDAPIKEY" data-google-map-lang="en"',
			$html
		);

		$html = $this->htmlForMap();
		$this->assertNotContains(
			' data-google-map-key="PRETENDAPIKEY" data-google-map-lang="en"',
			$html
		);
	}

	public function test_set_map_size() {
		MapUtil::set_map_size('890px', '24em');
		$html = $this->htmlForMap();
		$this->assertContains(' style="width:890px; height: 24em;"', $html);
	}

	public function testSanitizeEmptyString() {
		$this->assertEquals(
			'',
			MapUtil::sanitize('')
		);
	}

	public function testSanitizeAlreadySanitized() {
		$this->assertEquals(
			'This is already sanitized',
			MapUtil::sanitize('This is already sanitized')
		);
	}

	public function testSanitizeSlashN() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\n to be sanitized")
		);
	}

	public function testSanitizeSlashT() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\t to be sanitized")
		);
	}

	public function testSanitizeSlashR() {
		$this->assertEquals(
			'String to be sanitized',
			MapUtil::sanitize("String\r to be sanitized")
		);
	}

	/**
	 * A single marker for the Member should appear in the UJS map data
	 */
	public function testSingularMappableItemMarkerUJSExists() {
		Member::add_extension('MapExtension');
		$member = new Member();
		$member->Lat = 12.847;
		$member->Lon = 29.24;

		// because we are not writing, set this manually
		$member->MapPinEdited = true;
		$list = new ArrayList();
		$list->push($member);
		$map = MapUtil::get_map($list, array());
		$html = $map->forTemplate();
		$markerExpected = 'data-mapmarkers=\'[{"latitude":12.847,"longitude":29.24,"html":"MEMBER: ","category":"default","icon":false}]\'';
		$this->assertContains($markerExpected, $html);
		Member::remove_extension('MapExtension');
	}

	private function htmlForMap() {
		$map = MapUtil::get_map(new ArrayList(), array());
		$html = $map->forTemplate();
		return $html;
	}

	public function test_set_center() {
		MapUtil::set_center('Klong Tan, Bangkok, Thailand');
		$html = $this->htmlForMap();
		//coordinates of Klong Tan in Bangkok
		$expected = 'data-centre=\'{"lat":13.7243075,"lng":100.5718086}';
		$this->assertContains($expected, $html);
	}

	 public function test_set_map_type() {
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
			MapUtil::set_map_type($mapType);
			$expected = "data-maptype='".$mapTypes[$mapType]."'";
			$html = $this->htmlForMap();
			$this->assertContains($expected, $html);
		}
	}
}
