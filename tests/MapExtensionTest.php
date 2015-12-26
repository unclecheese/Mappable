<?php

class MapExtensionTest extends SapphireTest {
	protected static $fixture_file = 'mappable/tests/mapextensions.yml';

	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension', 'MapLayerExtension')
		);
		parent::setupOnce();
	}


	public function testUpdateCMSFields() {

	}


	public function testGetMappableLatitude() {
		$instance = $this->getInstance();
		$instance->Lat = 42.1;
		$instance->write();
		$this->assertEquals(
			42.1,
			$instance->getMappableLatitude()
		);
	}


	public function testGetMappableLongitude() {
		$instance = $this->getInstance();
		$instance->Lon = 42.1;
		$this->assertEquals(
			42.1,
			$instance->getMappableLongitude()
		);
	}


	public function testgetMappableMapContent() {

	}


	public function testonBeforeWriteMapPinNotEdited() {
		$instance = $this->getInstance();
		$this->Lat = 0;
		$this->Lon = 0;
		$instance->write();
		$this->assertFalse($instance->MapPinEdited);
	}

	public function testonBeforeWriteMapPinEdited() {
		$instance = $this->getInstance();

		// north
		$this->showMapPinEdited($instance, 10, 0);

		// north west
		$this->showMapPinEdited($instance, 10, -10);

		// west
		$this->showMapPinEdited($instance, 0, -10);

		// south west
		$this->showMapPinEdited($instance, -10, -10);

		// south
		$this->showMapPinEdited($instance, -10, 0);

		// south east
		$this->showMapPinEdited($instance, -10, 10);

		// east
		$this->showMapPinEdited($instance, 0, 10);

		// north east
		$this->showMapPinEdited($instance, 10, 10);

	}


	// FIXME - use an actual file here
	public function testGetMappableMapPin() {
		$instance = $this->getInstance();

		// no icon, so return false
		$this->assertFalse($instance->getMappableMapPin());

		// add a default map icon
		$this->addMapPinToInstance($instance);

		$pin1 = $instance->getMappableMapPin();
		$this->assertStringEndsWith('assets/mapicontest.png', $pin1);

		// Test the cached pin - this is the case of a layer providing a cached pin
		// so that repeated calls are not made to load the same icon
		$iconURL =
			'https://cdn3.iconfinder.com/data/icons/iconic-1/32/map_pin_fill-512.png';

		// Set mappable as having no icon, use the cached one instead
		// This simulates using a common icon to avoid DB queries
		$instance->MapPinIconID = 0;
		$instance->write();
		$instance->CachedMapPinURL = $iconURL;
		$pin2 = $instance->getMappableMapPin();
		$this->assertEquals($iconURL, $pin2);
	}





	public function testHasGeoWest() {
		$instance = $this->getInstance();
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoEast() {
		$instance = $this->getInstance();
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNorth() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNorthWest() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoNortEast() {
		$instance = $this->getInstance();
		$instance->Lat = 20;
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouth() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouthWest() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$instance->Lon = -20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoSouthEast() {
		$instance = $this->getInstance();
		$instance->Lat = -20;
		$instance->Lon = 20;
		$this->assertTrue($instance->HasGeo());
	}

	public function testHasGeoOrigin() {
		$instance = $this->getInstance();
		$instance->Lat = 0;
		$instance->Lon = 0;
		$this->assertFalse($instance->HasGeo());
	}

	public function testHasGeoOriginMapLayerExtension() {
		$instance = $this->getInstance();

		// assert that origin has no geo (until layers added)
		$instance->Lat = 0;
		$instance->Lon = 0;
		$this->assertFalse($instance->HasGeo());

		$this->addLayerToInstance($instance);

		// assert has geo even when at the origin of 0,0
		$this->assertTrue($instance->HasGeo());
	}

	public function testBasicMap() {
		$instance = $this->getInstance();
		$instance->Lat = 37.1;
		$instance->Lon = 28;
		$instance->Zoom = 12;
		$instance->MapPinEdited = true;
		$html = $instance->BasicMap()->forTemplate();

		$expected = "data-centre='{\"lat\":37.1,\"lng\":28}'";
		$this->assertContains($expected, $html);
		$expected = "data-mapmarkers='[{\"latitude\":37.1,\"longitude\":28,\"html\":\"MEMBER: \",\"category\":\"default\",\"icon\":false}]'";
		$this->assertContains($expected, $html);

		// This is only set automatically with layers
		$expected = 'data-enableautocentrezoom=false';
		$this->assertContains($expected, $html);
	}

	public function testBasicMapWithLayer() {
		$instance = $this->getInstance();
		$instance->Lat = 37.1;
		$instance->Lon = 28;
		$instance->Zoom = 12;
		$instance->MapPinEdited = true;
		$instance->write();
		$this->addLayerToInstance($instance);
		$html = $instance->BasicMap()->forTemplate();

		$expected = "data-centre='{\"lat\":37.1,\"lng\":28}'";
		$this->assertContains($expected, $html);
		$expected = "data-mapmarkers='[{\"latitude\":37.1,\"longitude\":28,\"html\":\"MEMBER: \",\"category\":\"default\",\"icon\":false}]'";
		$this->assertContains($expected, $html);

		// This is only set automatically with layers
		$expected = 'data-enableautocentrezoom=1';
		$this->assertContains($expected, $html);
	}


	public function testGetMapField() {
		$instance = $this->getInstance();
		$this->Lat = 37.1;
		$this->Lon = 28;
		$this->Zoom = 12;
		$mapField  = $instance->getMapField();
		$this->assertInstanceOf('LatLongField', $mapField);
	}


	public function testUseCompressedAssets() {
		$original = Config::inst()->get('Mappable', 'use_compressed_assets');

		$instance = $this->getInstance();
		Config::inst()->update('Mappable', 'use_compressed_assets', false);
		$this->assertFalse($instance->UseCompressedAssets());

		$instance = $this->getInstance();
		Config::inst()->update('Mappable', 'use_compressed_assets', true);
		$this->assertTrue($instance->UseCompressedAssets());

		Config::inst()->update('Mappable', 'use_compressed_assets', $original);
	}


	private function getInstance() {
		$instance = new Member();
		return $instance;
	}

	/*
	Add a map layer to an existing instance
	 */
	private function addLayerToInstance(&$instance) {
		// Create a layer
		$kmlFile = new File(array('Name' => 'example.kml', 'Filename' => 'mappable/tests/kml/example.kml'));
		$layer = new MapLayer();
		$layer->Title = 'Example KML Layer';
		$layer->KmlFile = $kmlFile;
		$layer->write();

		// add the layer to the Member object
		$layers = $instance->MapLayers();
		$layers->add($layer);
	}

	private function addMapPinToInstance(&$instance) {
		// Create a pin
		$copied = copy('mappable/tests/images/mapicontest.png', 'assets/mapicontest.png');
		$this->assertTrue($copied);
		$imageFile = new Image(array('Name' => 'mapicontest.png', 'Filename' => 'assets/mapicontest.png'));
		$imageFile->write();
		$instance->MapPinIconID = $imageFile->ID;
		$instance->write();
	}

	/**
	 * @param integer $lat
	 * @param integer $lon
	 */
	private function showMapPinEdited(&$instance, $lat, $lon) {
		$instance->Lat = $lat;
		$instance->Long = $lon;
		$instance->write();
		$this->assertTrue($instance->MapPinEdited);
	}



}
