<?php

class LatLongFieldTest extends SapphireTest
{
    public function testConstructValid()
    {
        $mapField = new LatLongField(
            array(
            new TextField('Lat', 'Latitude'),
            new TextField('Lon', 'Longitude'),
            new TextField('ZoomLevel', 'Zoom'),
            ),
            array('Address')
        );
    }

    public function testConstructOneFieldInvalid()
    {
        try {
            $mapField = new LatLongField(
                array(
                    new TextField('Lat', 'Latitude'),
                )
            );
            $this->fail('Creation of lat long field should have failed');
        } catch (Exception $e) {
            $expected = 'LatLongField argument 1 must be an array containing at'
                      .' least two FormField objects for Lat/Long values, resp'
                      .'ectively.';
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    public function testConstructTwoFieldsValid()
    {
        $mapField = new LatLongField(
            array(
                new TextField('Lat', 'Latitude'),
                new TextField('Lon', 'Longitude'),
            )
        );

        $html = $mapField->FieldHolder();
        $this->assertContains(
            '<label class="fieldholder-small-label" for="Lat">Latitude</label>',
            $html
        );
        $this->assertContains(
            '<input type="text" name="Lat" class="text hide" id="Lat" />',
            $html
        );
        $this->assertContains(
            '<label class="fieldholder-small-label" for="Lon">Longitude</label>',
            $html
        );
        $this->assertContains(
            '<input type="text" name="Lon" class="text hide" id="Lon" />',
            $html
        );
    }

    public function testConstructThreeFieldsValid()
    {
        $mapField = new LatLongField(
            array(
                new TextField('Lat', 'Latitude'),
                new TextField('Lon', 'Longitude'),
                new TextField('ZoomLevel', 'Zoom'),
            )
        );

        $html = $mapField->FieldHolder();
        $this->assertContains(
            '<label class="fieldholder-small-label" for="Lat">Latitude</label>',
            $html
        );
        $this->assertContains(
            '<input type="text" name="Lat" class="text hide" id="Lat" />',
            $html
        );
        $this->assertContains(
            '<label class="fieldholder-small-label" for="Lon">Longitude</label>',
            $html
        );
        $this->assertContains(
            '<input type="text" name="Lon" class="text hide" id="Lon" />',
            $html
        );
        $this->assertContains(
            '<label class="fieldholder-small-label" for="ZoomLevel">Zoom</label>',
            $html
        );
        $this->assertContains(
            '<input type="text" name="ZoomLevel" class="text hide" id="ZoomLevel" />',
            $html
        );
    }

    public function testGeocode()
    {
        $this->markTestSkipped('TODO');
    }

    public function testSetGuidePoints()
    {
        $mapField = new LatLongField(
            array(
                new TextField('Lat', 'Latitude'),
                new TextField('Lon', 'Longitude'),
                new TextField('ZoomLevel', 'Zoom'),
            )
        );
        $guidePoints = array(
            array('latitude' => 42, 'longitude' => '113.1'),
            array('latitude' => 14.9, 'longitude' => '113.2'),
            array('latitude' => 42.3, 'longitude' => '113.4'),
        );
        $mapField->setGuidePoints($guidePoints);

        $html = $mapField->FieldHolder();
        $expected = 'data-GuidePoints="[{&quot;latitude&quot;:42,&quot;longitude&quot;:&quot;113.1&'
                  .'quot;},{&quot;latitude&quot;:14.9,&quot;longitude&quot;:&quot;113.2&quot;},{&q'
                  .'uot;latitude&quot;:42.3,&quot;longitude&quot;:&quot;113.4&quot;}]"';

        $this->assertContains($expected, $html);
    }
}
