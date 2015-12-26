<?php

class MapFieldTest extends SapphireTest {

	public function testFieldWithTitle() {
		//$headingLevel = 2, $allowHTML = false, $form = nul
		$field = new MapField('NameOfField', 'TitleOfField');
		$expected = '<div class="editableMap"><div class="middleColumn" id="Nam'
				  . 'eOfField" style="width:100%;height:300px;margin:5px 0px 5p'
				  . 'x 5px;position:relative;"></div></div>';
		$this->assertEquals($expected, $field->Field());
	}

	public function testFieldWithoutTitle() {
		//$headingLevel = 2, $allowHTML = false, $form = nul
		$field = new MapField('NameOfField');
		$expected = '<div class="editableMap"><div class="middleColumn" id="Nam'
				  . 'eOfField" style="width:100%;height:300px;margin:5px 0px 5p'
				  . 'x 5px;position:relative;"></div></div>';
		$this->assertEquals($expected, $field->Field());
	}

}
