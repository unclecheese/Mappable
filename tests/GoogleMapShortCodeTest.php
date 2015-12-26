<?php

/**
* Testing YouTubeShortCodeHandler
*/
class GoogleMapShortCodeTest extends SapphireTest
{
	protected static $fixture_file = 'mappable/tests/shortcodes.yml';

	public function testRoadMap() {
		GoogleMapShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'RoadMap');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<<TEXT
Some text

<div class="googlemapcontainer">
<div id="google_sc_map_1" class="map googlemap" data-shortcode-map="" data-latitude="13.7402946" data-longitude="100.5525439" data-zoom="14" data-maptype="road" data-allowfullscreen="1"></div>
<p class="caption">Roads in Central Bangkok</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}


	public function testAerialMap() {
		GoogleMapShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'AerialMap');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<<TEXT
Some text

<div class="googlemapcontainer">
<div id="google_sc_map_1" class="map googlemap" data-shortcode-map="" data-latitude="13.815483" data-longitude="100.5447213" data-zoom="20" data-maptype="aerial" data-allowfullscreen="1"></div>
<p class="caption">Bang Sue Train Depot, Thailand</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}


	public function testHybridMap() {
		GoogleMapShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'HybridMap');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<<TEXT
Some text

<div class="googlemapcontainer">
<div id="google_sc_map_1" class="map googlemap" data-shortcode-map="" data-latitude="13.8309545" data-longitude="100.5577219" data-zoom="18" data-maptype="hybrid" data-allowfullscreen="1"></div>
<p class="caption">Junction in Bangkok, Thailand</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}


	public function testTerrainmap() {
		GoogleMapShortCodeHandler::resetCounter();
		$page = $this->objFromFixture('Page', 'TerrainMap');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$expected = <<<TEXT
Some text

<div class="googlemapcontainer">
<div id="google_sc_map_1" class="map googlemap" data-shortcode-map="" data-latitude="18.8032393" data-longitude="98.9166518" data-zoom="14" data-maptype="terrain" data-allowfullscreen="1"></div>
<p class="caption">Mountains west of Chiang Mai</p>
</div>

TEXT;
		$this->assertEquals($expected, $html);
	}


	public function testNoLongitude() {
		$page = $this->objFromFixture('Page', 'MapWithNoLongitude');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$this->assertEquals('Some text', $html);
	}


	public function testNoLatitude() {
		$page = $this->objFromFixture('Page', 'MapWithNoLatitude');
		$html = ShortcodeParser::get_active()->parse($page->Content);
		$this->assertEquals('Some text', $html);
	}


}
