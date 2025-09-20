# The Solar System OpenData

An open Rest API for querying all Solar System data.

https://api.le-systeme-solaire.net

# Authorization

You need to have an API key to use that open Rest API.

You just need to have an active email.
Ask an API key on `https://api.le-systeme-solaire.net/generatekey.html`

Add key on a bearer token in your header Authorization description :
`Authorization: Bearer <API_KEY_GUID>`

# Features

## Main bodies

All planets and their moons, all dwarfs planets and the main asteroids.

## Physical characteristics

Dimensions, mass, flattening, gravity, inclination and temperature.

## Orbital parameters

Semimajor axis, perihelion, aphelion, eccentricity, orbital period and orbital speed.

## History

Discovery circumstances, discoverer(s), year discovered and provisional designation.

## Family

For every body, all its satellites and the ability to navigate between satellites and the star.

## Known objects count

Known count of each object type : planets, moons, asteroids, comets.

## Positions of objects

Calculation of the astronomical positions of the Sun, Moon, and planets.

Right Ascension (HMS), Declination (DMS), Azimuth (DMS), Altitude (DMS)

Algorithms by Paul Schlyter, advanced corrections. Planetary perturbations. Topocentric corrections.

# Documentation

How to use the API?

## All bodies in one request

An URL returns all bodies in the database with all data:
`https://api.le-systeme-solaire.net/rest/bodies/`

## Boby by body

An URL returns all data of one body:
`https://api.le-systeme-solaire.net/rest/bodies/{id}`

## All known count

An URL returns all known count for each object type :
`https://api.le-systeme-solaire.net/rest/knowncount/`

## Known count, object type by object type

An URL returns known count for the object type :
`https://api.le-systeme-solaire.net/rest/knowncount/{id}`

## Position of object

An URL returns known count for the object type :
`https://api.le-systeme-solaire.net/rest/positions?lon=value&lat=value&elev=value&datatime=value&zon=value`

## API parameters for /bodies

|#| 	Parameter   |	Action                                          |
|-|-----------------|---------------------------------------------------|
|1| 	data        | 	The data you want to retrieve (comma separated).|
|2| 	exclude 	|One or more data you want to exclude (comma separated).|
|3| 	order 	|The sort order data you want to use and the sort direction (comma separated).|
|4| 	page 	|Page number (number>=1) and page size (size>=1 with 20 as default) (comma separated).|
|5| 	rowData 	|Transform the objects in records.|
|6| 	filter[] 	|Filters to be applied. Each filter consists of a data, an operator and a value (comma separated).|
|7| 	satisfy 	|Should all filters match (default).|

## API parameters /knowncount

|#| 	Parameter |	Action|
|-|---------------|-------|
|1| 	rowData 	|Transform the objects in records.|

## API parameters /positions

|#| 	Parameter   |	Action                                          |
|-|-----------------|---------------------------------------------------|
|1| 	name        | 	Name of object.|
|2| 	ra        | 	 	Right Ascension in hour-minute-seconde.|
|3| 	dec        | 	 	Declination in degree-minute-seconde.|
|4| 	az        | Azimuth in degree-minute-seconde.|
|5| 	alt        | 	 	Altitude in degree-minute-seconde.|

# License

Distributed under MIT license. 
