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
                            "name": "rowData",
                            "in": "query",
                            "description": "Transform the object in records. NB: This can also be done client-side in JavaScript!",
                            "schema": {
                                "type": "boolean"
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
                        {
                            "name": "rowData",
                            "in": "query",
                            "description": "Transform the object in records. NB: This can also be done client-side in JavaScript!",
                            "schema": {
                                "type": "boolean"
                            }
                        }
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
            }
        }
    }';
}
?>