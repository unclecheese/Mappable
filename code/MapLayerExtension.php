<?php

class MapLayerExtension extends DataExtension {

  static $many_many = array(
      'MapLayers' => 'MapLayer'
   );

  static $belongs_many_many_extraFields = array(
    'MapLayers' => array(
      'SortOrder' => "Int"
    )
  );


  public function updateCMSFields( FieldList $fields ) {
    $gridConfig2 = GridFieldConfig_RelationEditor::create();
    $gridConfig2->getComponentByType( 'GridFieldAddExistingAutocompleter' )->setSearchFields( array( 'Title' ) );
    $gridConfig2->getComponentByType( 'GridFieldPaginator' )->setItemsPerPage( 100 );
    $gridField2 = new GridField( "Map Layers", "Map Layers:", $this->owner->MapLayers(), $gridConfig2 );
    $fields->addFieldToTab( "Root.MapLayers", $gridField2 );
  }
}