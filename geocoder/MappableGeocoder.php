<?php

interface MappableGeocoder {

	/**
	 * Get locations given a search string
	 * @param  string $searchString place name to search for, e.g. 'Bangkok'
	 * @return array  List of places matching the search term
	 */
	public function getLocations($searchString);
}
