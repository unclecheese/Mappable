#Multiple Maps on the Same Page
Multiple maps can be added to a page.  One option is to associate maps with a DataObject whose relationship to the parent page is 'has many', as in this example of contact page addresses.
##Example
Often a company will have more than one office location that they wish to display, this is a an example of that use case.  It would probably need expanding in order to show the likes of email address and telephone number,  left as an exercise for the reader.

Firstly, create a parent container page called ContactPage, this has many locations of type ContactPageAddress.
```
<?php
class ContactPage extends DemoPage {
 
  static $has_many = array(
    'Locations' => 'ContactPageAddress'
  );
 
  function getCMSFields() {
    $fields = parent::getCMSFields();
 
    $gridConfig = GridFieldConfig_RelationEditor::create();
    $gridConfig->getComponentByType( 'GridFieldAddExistingAutocompleter' )->setSearchFields( array( 'PostalAddress' ) );
    $gridConfig->getComponentByType( 'GridFieldPaginator' )->setItemsPerPage( 100 );
    $gridField = new GridField( "Locations", "List of Addresses:", $this->Locations(), $gridConfig );
    $fields->addFieldToTab( "Root.Addresses", $gridField );
 
    return $fields;
  }
}
 
class ContactPage_Controller extends Page_Controller {
 
 
}
 
?>
```

The latter contains the actual map for each location, configured as above using extensions.yml

```
<?php
class ContactPageAddress extends DataObject {
	static $db = array(
		'PostalAddress' => 'Text'
	);
 
	static $has_one = array( 'ContactPage' => 'ContactPage' );
 
 
	public static $summary_fields = array(
		'PostalAddress' => 'PostalAddress'
	);
 
 
	function getCMSFields() {
		$fields = new FieldList();
		$fields->push( new TabSet( "Root", $mainTab = new Tab( "Main" ) ) );
		$mainTab->setTitle( _t( 'SiteTree.TABMAIN', "Main" ) );
		$fields->addFieldToTab( "Root.Main", new TextField( 'PostalAddress' ) );
 
		$this->extend( 'updateCMSFields', $fields );
 
		return $fields;
	}
}
?>
```

The template simply loops through the contact page addresses, rendering a map.

```
<h1>$Title</h1>
$BriefDescription
 
<h2>Addresses</h2>
 
<% loop Locations %>
<h3>$PostalAddress</h3>
$BasicMap
<% end_loop %>
 
$Content
```

See http://demo.weboftalent.asia/mappable/multiple-maps-on-the-same-page/ for a working demo.