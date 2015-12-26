<?php

class MapMarkerSetsExtensionTest extends SapphireTest {

	public function setUpOnce() {
		$this->requiredExtensions = array(
			'Member' => array('MapExtension', 'MapMarkerSetsExtension')
		);
		parent::setupOnce();
	}

	public function testCMSFields() {
		$instance = new Member();
		$fields = $instance->getCMSFields();
		$tab = $fields->findOrMakeTab('Root.MapMarkerSets');
		$fields = $tab->FieldList();
		$names = array();
		foreach ($fields as $field) {
			$names[] = $field->getName();
		}
		$expected = array('MapMarkerSets');
		$this->assertEquals($expected, $names);
	}

}
