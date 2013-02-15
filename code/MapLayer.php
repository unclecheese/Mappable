<?php

class MapLayer extends DataObject {  


  static $db = array(
  'Title' => 'Varchar(255)'
  );


  static $has_one = array(
        'KmlFile' => 'File'
  );



  

/*

   static $belongs_many_many = array(
      'Articles' => 'Article',
      'FlickrSetPages' => 'FlickrSetPage'
   );
*/





   function getCMSFields_forPopup() {
        $fields = new FieldSet();
         
        $fields->push( new TextField( 'Title' ) );
        $fields->push( new FileIFrameField( 'KmlFile') );
         
        return $fields;
    }

}


?>