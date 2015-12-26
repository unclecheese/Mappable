<?php

class MappableDataObjectSetTest extends SapphireTest {

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

	public function testSetMarkerTemplateValues() {
		$instance1 = $this->getInstance();
		$instance1->MapPinEdited = true;
		$instance1->write();

		$instance2 = $this->getInstance();
		$instance2->Lat = 7.12;
		$instance2->Lon = 23.4;
		$instance2->MapPinEdited = true;
		$instance2->write();

		// Mappable list has MappableDataObjectSet enabled by default
		// Items in the list are Mappable via the MapExtension
		$mappableList = new ArrayList();
		$mappableList->push($instance1);
		$mappableList->push($instance2);

		$vals = array('TestKey' => ' TestKeyValMDOS');
		$mappableList->setMarkerTemplateValues($vals);

		$html = $mappableList->getRenderableMap(300, 800, 2)->setDivId('testmap')->forTemplate()->getValue();
		$expected = <<<HTML


<div id="testmap" data-google-map-lang="en"  style="width:300; height: 800;"
 class=" mappable"
data-map
data-centre='{"lat":48.856614,"lng":2.3522219}'
data-zoom=9
data-maptype='road'
data-allowfullscreen='1'
data-clusterergridsize=50,
data-clusterermaxzoom=17,
data-enableautocentrezoom=1
data-enablewindowzoom=false
data-infowindowzoom=13
data-mapmarkers='[{"latitude":13.8188931,"longitude":100.5005558,"html":"MEMBER: Test User TestKeyValMDOS","category":"default","icon":false},{"latitude":7.12,"longitude":23.4,"html":"MEMBER: Test User TestKeyValMDOS","category":"default","icon":false}]'
data-defaulthidemarker=false
data-lines='[]'
data-kmlfiles='[]'
data-mapstyles='[]'
data-useclusterer=false
>
</div>

HTML;
		$this->assertEquals($expected, $html);
	}



	private function getInstance() {
		$instance = new Member();
		$instance->Lat = 13.8188931;
		$instance->Lon = 100.5005558;
		$instance->FirstName = 'Test';
		$instance->Surname = 'User';
		return $instance;
	}

}
