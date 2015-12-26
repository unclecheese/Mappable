<?php

class GoogleStreetViewShortCodeTest extends SapphireTest
{
	protected static $fixture_file = 'mappable/tests/shortcodes.yml';

	public function testRoadMap() {
		GoogleStreetViewShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'StreetView');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<<TEXT
Some text

<div class="streetviewcontainer">
<div id="google_streetview_1" class="streetview googlestreetview" data-streetview="" data-latitude="13.811841" data-longitude="100.527309" data-zoom="1" data-pitch="-10" data-heading="162.43"></div>
<p class="caption">Canal south from Pracha Rat 1 Soi 28</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}

	public function testNoLongitude() {
		GoogleStreetViewShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'StreetViewNoLongitude');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$this->assertEquals('Some text', $html);
	}

	public function testNoLatitude() {
		GoogleStreetViewShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'StreetViewNoLatitude');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$this->assertEquals('Some text', $html);
	}

	public function testNoHeading() {
		GoogleStreetViewShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'StreetViewNoHeading');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$this->assertEquals('Some text', $html);
	}

	public function testZoom() {
		GoogleStreetViewShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'StreetViewWithZoom');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<< TEXT
Some text

<div class="streetviewcontainer">
<div id="google_streetview_1" class="streetview googlestreetview" data-streetview="" data-latitude="13.811841" data-longitude="100.527309" data-zoom="12" data-pitch="-10" data-heading="162.43"></div>
<p class="caption">Canal south from Pracha Rat 1 Soi 28</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}
}
