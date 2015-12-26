<?php

class MapLayerTest extends SapphireTest
{
	public function testCMSFields() {
		$layer = new MapLayer();
		$fields = $layer->getCMSFields();
		$tab = $fields->findOrMakeTab('Root.Main');
		$fields = $tab->FieldList();
		$names = array();
		foreach ($fields as $field) {
			$names[] = $field->getName();
		}
		$expected = array('Title', 'KmlFile');
		$this->assertEquals($expected, $names);
	}
}
