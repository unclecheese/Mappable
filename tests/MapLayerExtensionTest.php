<?php

class MapLayerExtensionTest extends SapphireTest {

	public function setUp() {
		// add MapExtension and MapLayerExtension extension to Member
		Member::add_extension('MapExtension');
		Member::add_extension('MapLayerExtension');
		parent::setUp();
	}

	// check for addition of extra CMS fields
	public function testCMSFields() {
		$instance = new Member();
		$fields = $instance->getCMSFields();
		$tab = $fields->findOrMakeTab('Root.MapLayers');
		$fields = $tab->FieldList();
		$names = array();
		foreach ($fields as $field) {
			$names[] = $field->getName();
		}
		$expected = array('Map Layers');
		$this->assertEquals($expected, $names);
	}

}
