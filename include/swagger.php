<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
function swagger() {
    header('Content-Type: application/json; charset=utf-8');
    echo'{
        "openapi": "3.1.0",
        "info": {
            "title": "Solar System openData",
            "description": "API to get all data about all solar system objects",
            "version": "'.$GLOBALS['API_VERSION'].'"
        },
        "servers": [
            {
                "url": "https://'.$_SERVER['HTTP_HOST'].'/rest"
            }
        ],
        "components": {
            "securitySchemes": {
                "bearerAuth": {
                    "type": "http",
                    "scheme": "bearer",
                    "bearerFormat": "UUID"
                }
            }
        },
        "tags": [
            {
                "name": "bodies",
                "description": "Object with all data about the concerned body : orbitals, physicals and atmosphere"
            },
            {
                "name": "knowncount",
                "description": "Count of known objects"
            }
        ],
        "paths": {
            "/bodies": {
                "get": {
                    "tags": [
                        "bodies"
                    ],
                    "security": [
                        {
                            "bearerAuth": []
                        }
                    ],
                    "summary": "List",
                    "parameters": [
                        {
                            "name": "data",
                            "in": "query",
                            "description": "The data you want to retrieve (comma separated). Example: id,semimajorAxis,isPlanet.",
                            "schema": {
                                "type": "string"
                            }
                        },
                        {
                            "name": "exclude",
                            "in": "query",
                            "description": "One or more data you want to exclude (comma separated). Example: id,isPlanet.",
                            "schema": {
                                "type": "string"
                            }
                        },
                        {
                            "name": "order",
                            "in": "query",
                            "description": "A data you want to sort on and the sort direction (comma separated). Example: id,desc. Only one data is authorized.",
                            "schema": {
                                "type": "string"
                            }
                        },
                        {
                            "name": "page",
                            "in": "query",
                            "description": "Page number (number>=1) and page size (size>=1 and 20 by default) (comma separated). NB: You cannot use \"page\" without \"order\"! Example: 1,10.",
                            "schema": {
                                "type": "string"
                            }
                        },
                        {
                            "name": "filter[]",
                            "in": "query",
                            "description": "Filters to be applied. Each filter consists of a data, an operator and a value (comma separated). Example: id,eq,mars. Accepted operators are : cs (like) - sw (start with) - ew (end with) - eq (equal) - lt (less than) - le (less or equal than) - ge (greater or equal than) - gt (greater than) - bt (between). And all opposites operators : ncs - nsw - new - neq - nlt - nle - nge - ngt - nbt. Note : if anyone filter is invalid, all filters will be ignore.",
                            "style": "form",
                            "explode": true,
                            "schema": {
                                "type": "array",
                                "items": {
                                    "type": "string"
                                }
                            }
                        },
                        {
                            "name": "satisfy",
                            "in": "query",
                            "description": "Should all filters match (default)? Or any?",
                            "schema": {
                                "type": "string",
                                "enum": [
                                    "any"
                                ]
                            }
                        }
                    ],
                    "responses": {
                        "200": {
                            "description": "An array of bodies",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "bodies": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "id": {
                                                            "type": "string"
                                                        },
                                                        "name": {
                                                            "type": "string"
                                                        },
                                                        "englishName": {
                                                            "type": "string"
                                                        },
                                                        "isPlanet": {
                                                            "type": "boolean"
                                                        },
                                                        "moons": {
                                                            "type": "array",
                                                            "items": {
                                                                "type": "object",
                                                                "properties": {
                                                                    "moon": {
                                                                        "type": "string"
                                                                    },
                                                                    "rel": {
                                                                        "type": "string"
                                                                    }
                                                                }
                                                            }
                                                        },
                                                        "semimajorAxis": {
                                                            "type": "number"
                                                        },
                                                        "perihelion": {
                                                            "type": "number"
                                                        },
                                                        "aphelion": {
                                                            "type": "number"
                                                        },
                                                        "eccentricity": {
                                                            "type": "number"
                                                        },
                                                        "inclination": {
                                                            "type": "number"
                                                        },
                                                        "mass": {
                                                            "type": "object",
                                                            "properties": {
                                                                "massValue": {
                                                                    "type": "number"
                                                                },
                                                                "massExponent": {
                                                                    "type": "integer"
                                                                }
                                                            }
                                                        },
                                                        "vol": {
                                                            "type": "object",
                                                            "properties": {
                                                                "volValue": {
                                                                    "type": "number"
                                                                },
                                                                "volExponent": {
                                                                    "type": "integer"
                                                                }
                                                            }
                                                        },
                                                        "density": {
                                                            "type": "number"
                                                        },
                                                        "gravity": {
                                                            "type": "number"
                                                        },
                                                        "escape": {
                                                            "type": "number"
                                                        },
                                                        "meanRadius": {
                                                            "type": "number"
                                                        },
                                                        "equaRadius": {
                                                            "type": "number"
                                                        },
                                                        "polarRadius": {
                                                            "type": "number"
                                                        },
                                                        "flattening": {
                                                            "type": "number"
                                                        },
                                                        "dimension": {
                                                            "type": "string"
                                                        },
                                                        "sideralOrbit": {
                                                            "type": "number"
                                                        },
                                                        "sideralRotation": {
                                                            "type": "number"
                                                        },
                                                        "aroundPlanet": {
                                                            "type": "object",
                                                            "properties": {
                                                                "planet": {
                                                                    "type": "string"
                                                                },
                                                                "rel": {
                                                                    "type": "string"
                                                                }
                                                            }
                                                        },
                                                        "discoveredBy": {
                                                            "type": "string"
                                                        },
                                                        "discoveryDate": {
                                                            "type": "string"
                                                        },
                                                        "alternativeName": {
                                                            "type": "string"
                                                        },
                                                        "axialTilt": {
                                                            "type": "number"
                                                        },
                                                        "avgTemp": {
                                                            "type": "number"
                                                        },
                                                        "mainAnomaly": {
                                                            "type": "number"
                                                        },
                                                        "argPeriapsis": {
                                                            "type": "number"
                                                        },
                                                        "longAscNode": {
                                                            "type": "number"
                                                        },
                                                        "bodyType": {
                                                            "type": "string"
                                                        },
                                                        "rel": {
                                                            "type": "string"
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "/bodies/{id}": {
                "get": {
                    "tags": [
                        "bodies"
                    ],
                    "security": [
                        {
                            "bearerAuth": []
                        }
                    ],
                    "summary": "read",
                    "parameters": [
                        {
                            "name": "id",
                            "in": "path",
                            "description": "Identifier for item.",
                            "required": true,
                            "schema": {
                                "type": "string"
                            }
                        }
                    ],
                    "responses": {
                        "200": {
                            "description": "The requested item.",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "id": {
                                                "type": "string"
                                            },
                                            "name": {
                                                "type": "string"
                                            },
                                            "englishName": {
                                                "type": "string"
                                            },
                                            "isPlanet": {
                                                "type": "boolean"
                                            },
                                            "moons": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "moon": {
                                                            "type": "string"
                                                        },
                                                        "rel": {
                                                            "type": "string"
                                                        }
                                                    }
                                                }
                                            },
                                            "semimajorAxis": {
                                                "type": "number"
                                            },
                                            "perihelion": {
                                                "type": "number"
                                            },
                                            "aphelion": {
                                                "type": "number"
                                            },
                                            "eccentricity": {
                                                "type": "number"
                                            },
                                            "inclination": {
                                                "type": "number"
                                            },
                                            "mass": {
                                                "type": "object",
                                                "properties": {
                                                    "massValue": {
                                                        "type": "number"
                                                    },
                                                    "massExponent": {
                                                        "type": "integer"
                                                    }
                                                }
                                            },
                                            "vol": {
                                                "type": "object",
                                                "properties": {
                                                    "volValue": {
                                                        "type": "number"
                                                    },
                                                    "volExponent": {
                                                        "type": "integer"
                                                    }
                                                }
                                            },
                                            "density": {
                                                "type": "number"
                                            },
                                            "gravity": {
                                                "type": "number"
                                            },
                                            "escape": {
                                                "type": "number"
                                            },
                                            "meanRadius": {
                                                "type": "number"
                                            },
                                            "equaRadius": {
                                                "type": "number"
                                            },
                                            "polarRadius": {
                                                "type": "number"
                                            },
                                            "flattening": {
                                                "type": "number"
                                            },
                                            "dimension": {
                                                "type": "string"
                                            },
                                            "sideralOrbit": {
                                                "type": "number"
                                            },
                                            "sideralRotation": {
                                                "type": "number"
                                            },
                                            "aroundPlanet": {
                                                "type": "object",
                                                "properties": {
                                                    "planet": {
                                                        "type": "string"
                                                    },
                                                    "rel": {
                                                        "type": "string"
                                                    }
                                                }
                                            },
                                            "discoveredBy": {
                                                "type": "string"
                                            },
                                            "discoveryDate": {
                                                "type": "string"
                                            },
                                            "alternativeName": {
                                                "type": "string"
                                            },
                                            "axialTilt": {
                                                "type": "number"
                                            },
                                            "avgTemp": {
                                                "type": "number"
                                            },
                                            "mainAnomaly": {
                                                "type": "number"
                                            },
                                            "argPeriapsis": {
                                                "type": "number"
                                            },
                                            "longAscNode": {
                                                "type": "number"
                                            },
                                            "bodyType": {
                                                "type": "string"
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "/knowncount": {
                "get": {
                    "tags": [
                        "knowncount"
                    ],
                    "security": [
                        {
                            "bearerAuth": []
                        }
                    ],
                    "summary": "List",
                    "parameters": [
                    ],
                    "responses": {
                        "200": {
                            "description": "An array of knowncount",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "knowncount": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "id": {
                                                            "type": "string"
                                                        },
                                                        "knownCount": {
                                                            "type": "number"
                                                        },
                                                        "updateDate": {
                                                            "type": "string"
                                                        },
                                                        "rel": {
                                                            "type": "string"
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "/knowncount/{id}": {
                "get": {
                    "tags": [
                        "knowncount"
                    ],
                    "security": [
                        {
                            "bearerAuth": []
                        }
                    ],
                    "summary": "read",
                    "parameters": [
                        {
                            "name": "id",
                            "in": "path",
                            "description": "Identifier for item.",
                            "required": true,
                            "schema": {
                                "type": "string"
                            }
                        }
                    ],
                    "responses": {
                        "200": {
                            "description": "The requested item.",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "id": {
                                                "type": "string"
                                            },
                                            "knownCount": {
                                                "type": "number"
                                            },
                                            "updateDate": {
                                                "type": "string"
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "/positions": {
                "get": {
                    "tags": [
                        "positions"
                    ],
                    "security": [
                        {
                            "bearerAuth": []
                        }
                    ],
                    "summary": "List",
                    "parameters": [
                        {
                            "name":"lat",
                            "in":"query",
                            "description":"Latitude for observer. From -90° to +90°.",
                            "required":true,
                            "schema": {
                                "type":"number"
                            }
                        },
                        {
                            "name":"lon",
                            "in":"query",
                            "description":"Longitude for observer. From -180° to +180°.",
                            "required":true,
                            "schema": {
                                "type":"number"
                            }
                        },
                        {
                            "name":"elev",
                            "in":"query",
                            "description":"Altitude for observer in meter.",
                            "required":true,
                            "schema": {
                                "type":"integer"
                            }
                        },
                        {
                            "name":"datetime",
                            "in":"query",
                            "description":"Date UTC for observer in ISO 8601 Format (yyyy-MM-ddThh:mm:ss)",
                            "required":true,
                            "schema": {
                                "type":"string"
                            }
                        },
                        {
                            "name":"zone",
                            "in":"query",
                            "description":"Time Zone for observer from -12 to +14",
                            "required":true,
                            "schema": {
                                "type":"integer"
                            }
                        }
                    ],
                    "responses": {
                        "200": {
                            "description": "Position of objects",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "positions": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "name": {
                                                            "type": "string"
                                                        },
                                                        "ra": {
                                                            "type": "string"
                                                        },
                                                        "dec": {
                                                            "type": "string"
                                                        },
                                                        "az": {
                                                            "type": "string"
                                                        },
                                                        "alt": {
                                                            "type": "string"
                                                        }
                                                    }
                                                }
                                            },
                                            "location": {
                                                "type": "object",
                                                "properties": {
                                                    "latitude": {
                                                        "type": "number"
                                                    },
                                                    "longitude": {
                                                        "type": "number"
                                                    },
                                                    "altitude": {
                                                        "type": "integer"
                                                    },
                                                    "timezone": {
                                                        "type": "integer"
                                                    }
                                                }
                                            },
                                            "time_info": {
                                                "type": "object",
                                                "properties": {
                                                    "calculated_for_utc": {
                                                        "type": "string"
                                                    },
                                                    "local_time_display": {
                                                        "type": "string"
                                                    },
                                                    "universal_time_ut": {
                                                        "type": "string"
                                                    },
                                                    "universal_time_decimal": {
                                                        "type": "number"
                                                    },
                                                    "julian_day": {
                                                        "type": "number"
                                                    },
                                                    "day_number_j2000": {
                                                        "type": "number"
                                                    },
                                                    "greenwich_sidereal_time": {
                                                        "type": "string"
                                                    },
                                                    "local_sidereal_time": {
                                                        "type": "string"
                                                    },
                                                    "gst_decimal": {
                                                        "type": "number"
                                                    },
                                                    "lst_decimal": {
                                                        "type": "number"
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }';
}
?>