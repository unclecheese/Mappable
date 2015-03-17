#OpenStreetMap
##Importing OSM Data
The import process is documented numerously on the internet, a good one for Ubuntu is
https://switch2osm.org/serving-tiles/manually-building-a-tile-server-14-04/ - once set up an import
of the OSM data for Thailand took around 20 minutes.

Snapshots of OSM data are available from http://download.geofabrik.de/

##Extracting Points of Interest
The process of extracting points of interest is as follows:
* Select the points you are interested in from the PostGIS database into which the OSM data was
imported.  Use SQL and output to a file.
* Parse the result output using a language of your choice to create SQL that can be imported into
SilverStripe as points of interest.
* Import the resultant SQL into your SilverStripe database.

## Worked Example - Thai Railway Stations
###Extract Data from PostGIS OSM Database
Create a file of arbitrary name, e.g. osm.sql, with the following query, which extracts OSM id,
name, latitude, longitude and tags for nodes:
```sql
select osm_id,name, amenity,
ST_Y((ST_Transform (way, 4326))) as Latitude,
ST_X((ST_Transform (way, 4326))) as Longitude,
tags
from planet_osm_nodes N
inner join planet_osm_point P
on N.id = P.osm_id
;
```

At the command line execute this query and grep for railway stations:
```bash
psql gis < osm.sql | grep -i railway > trains.osm
```
Note that authentication to the Postgres database may differ.

Analysis of the output shows that further grepping is require in order to extract just stations.
```
.77085717602143 | 100.437159389841 | {railway,level_crossing}
 3286015332 | Bang Klam                                                                                               |                            | 7.08769743171173 | 100.415422944743 | {railway,station,name,"Bang Klam"}
 3287950085 | Death Railway viaduct                                                                                   |                            | 14.1534710197064 | 99.1098331602021 | {tourism,attraction,name,"Death Railway viaduct"}
 3288538284 |                                                                                                         |                            | 9.14452066569457 | 99.1675465934402 | {railway,level_crossing}
 3288549665 |                                                                                                         
```
Execute the following to get just the stations:
 ```
grep -i station trains.osm > stations.osm
 ```
###Create Points of Interest Layer
In the SilverStripe model admin interface create a new PointsOfInterestLayer to contain the railway
stations. Note that a common
icon for the layer can be added here, if none is provided the standard Google Map pin is used.

![Adding a new points of interest layer]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-create-railway-layer.png?raw=true
"Adding a new points of interest layer")

Obtain the database ID, in this case the value 3.  This is required for scripting purposes.

```sql
mysql> select * from PointsOfInterestLayer;
+----+-----------------------+---------------------+---------------------+------------------------------+---------------+
| ID | ClassName             | Created             | LastEdited          | Name                         | DefaultIconID |
+----+-----------------------+---------------------+---------------------+------------------------------+---------------+
|  1 | PointsOfInterestLayer | 2015-01-13 15:15:27 | 2015-01-13 16:26:01 | BTS Stations                 |           123 |
|  2 | PointsOfInterestLayer | 2015-03-06 15:57:28 | 2015-03-10 17:38:25 | Seven Elevens in Thailand    |           126 |
|  3 | PointsOfInterestLayer | 2015-03-13 11:26:16 | 2015-03-13 11:26:16 | Railway Stations in Thailand |           123 |
+----+-----------------------+---------------------+---------------------+------------------------------+---------------+
```

###Convert Extracted Data to SQL for Import Into SilverStripe
The following is an example Ruby script to extract the English name from the tags field, if one is
defined, and output
SQL that can be imported directly into the SilverStripe database for the site in question.  It was
saved as parse_osm.rb, the name is of course arbitrary.

```ruby
filename = ARGV[0]
layerid = ARGV[1]
ctr = 0

puts "/* Extracting from #{filename} */"
puts "begin;"
File.open(filename) do |file|
	file.each {|line| \
		ctr = ctr + 1
		if ctr < 3
			next
		end
		splits = line.split('|')
		if (splits.length == 6)
			puts
			osm_id = splits[0]
			name = splits[1]
			lat = splits[3]
			lon = splits[4].strip

			#Tags are a comma separated list of key value pairs
			tags = {}
			tagtxt = splits[5].strip
			tagtxt[0] = ''
			tagtxt[-1] = ''
			tagcols = tagtxt.split ','
			tagname = 'UNDEFINED'
			while tagcols.length > 0
				value = tagcols.pop
				key = tagcols.pop
				tags[key] = value  
			end


			if tags['name:en']
				tagname = tags['name:en']
			else
				tagname = tags['name']
			end

			if tagname == nil
				tagname = "UNDEFINED"
			end

			# Remove quotation marks
			if tagname[0] == '"'
				tagname[0]=''
			end

			if tagname[-1] == '"'
				tagname[-1]=''
			end

			tagname.strip!

			if tagname != 'UNDEFINED'
				sql = "INSERT INTO PointOfInterest(OpenStreetMapID,Name,Lat,Lon,ZoomLevel,Created,LastEdited,MapPinEdited) VALUES (#{osm_id},'#{tagname}',#{lat},#{lon},16,now(),now(),true);"
				puts sql
				sql = "INSERT INTO  PointsOfInterestLayer_PointsOfInterest(PointsOfInterestLayerID,PointOfInterestID) VALUES(#{layerid}, LAST_INSERT_ID());"
				puts sql
			end
			
		end
	}
end

puts "commit;"
```

To use this script the format is
```
ruby parse_osm.rb <postgresql result file> <SilverStripe layer id>
```
so in the case of the example above
```
ruby parse_osm.rb stations.osm 3
```

Output is many rows of SQL like this, the first line of each pair being the creation of the point
of interest and the second associating it with the point of interest layer representing the
stations.

```sql
INSERT INTO PointOfInterest(OpenStreetMapID,Name,Lat,Lon,ZoomLevel,Created,LastEdited,MapPinEdited)
VALUES (  236480470 ,'Khlong Phutsa', 14.1860507762646 ,100.578314006055,16,now(),now(),true);
INSERT INTO  PointsOfInterestLayer_PointsOfInterest(PointsOfInterestLayerID,PointOfInterestID)
VALUES(3, LAST_INSERT_ID());

INSERT INTO PointOfInterest(OpenStreetMapID,Name,Lat,Lon,ZoomLevel,Created,LastEdited,MapPinEdited)
VALUES (  237445803 ,'Railway station Ayutthaya', 14.3567211927894 ,100.58319491232,16,now(),now(),true);
INSERT INTO  PointsOfInterestLayer_PointsOfInterest(PointsOfInterestLayerID,PointOfInterestID)
VALUES(3, LAST_INSERT_ID());

```
Note that the method LAST_INSERT_ID() is MySQL centric.  If your SilverStripe database is hosted
using PostgreSQL then change this to 'currval()'.

An improvement to this script would be the addition of escaping quotes but it wasn't a necessity
for the data being loaded.

### Add Points Of Interest Layer to an Existing Page
Add the new layer in the 'Map Layers' tab for any page using the PointsOfInterestLayerExtension
extension.  Type the word 'Railway' into the search box to the right of 'Points of Interest Layer'.
After a couple of seconds select 'Railway Stations of Thailand' and click 'Link Existing'.
Save and publish the page.
* During the process of adding the railway stations layer 
![Adding railway layer]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-adding-railway-layer.png?raw=true 
"Adding railway layer")
* After the railway stations layer was added
![Added railway layer]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-adding-railway-layer-2.png?raw=true
"Added railway layer")

###View Data in SilverStripe
The imported railway stations can now be seen and edited in the model admin interface.
* List of railway stations. ![Railway Stations as POIs in Model Admin]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-imported-railway-stations.png?raw=true
"Railway Stations as POIs in Model Admin")
* Editing the entry for Bankrut Railway Station (1/2). ![Editing a single station]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-editing-bankrut.png?raw=true
"Editing a single station (1/2)")
* Editing the entry for Bankrut Railway Station (2/2). ![Editing a single station]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-single-station-location.png?raw=true
"Editing a single station (2/2)")


###Public Rendered View
####Map of Thailand with Railway Stations POIs Marked as Clusters
* The entire country ![Railway Stations in Thailand]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-clustered-stations.png?raw=true
"Railway Stations in Thailand")
* Zoomed in to show individual stations ![Railway Stations in Thailand - Chachaengsao Area]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/poi-clustered-stations-zoomin.png?raw=true
"Railway Stations in Thailand - Chachaengsao Area")
