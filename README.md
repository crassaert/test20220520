# Test 20220520

Client API to create new shops and retrieve products regarding some filters.

# Usage

To init project for first time, please launch this command into project directory :

    ./test.sh

# Security
API is secured with a Bearer token header authorization. Please define it within your env with name `BEARER_API_TOKEN`

# Endpoints
## Create shop
### POST /shop/create

    {
	    name: 'test', 	// shop name
	    manager: 11, 	// manager id
	    lat: 23.34322, 	// latitude
	    lng: 23.245533 	// longitude
	}

## Get managers
### GET /manager/list
#### Response

    {
        items: [
            {
                "id": 31,
                "firstName": "Janick",
                "lastName": "Klein"
            },
            ...
        ],
        "pagination": {
            "current_page": 1,
            "has_previous_page": false,
            "has_next_page": false,
            "per_page": 10,
            "total_items": 10,
            "total_pages": 1
        }
    }

## Get shops
### GET /shop/list

| name | type | required | description |
| -- | -- | -- | -- |
| `lat` | float | required | Latitude |
| `lng` | float | required | Longitude |
| `radius` | int | required | Distance (in meters) |
| `page` | int | optional | Result page |


#### Response :
    {
        items:
            [
                {
                    "id": 91,
                    "name": "Marques Zemlak",
                    "lat": "-39.2844670000000000",
                    "lng": "-96.7915560000000000",
                    "postalAddress": "83597 Mattie Stream\nNorth Kallieton, MA 23584-1875",
                    "manager": {
                        "id": 38,
                        "firstName": "Ellis",
                        "lastName": "Greenfelder"
                    }
                },
            ...
        ],
        "pagination": {
            "current_page": 1,
            "has_previous_page": false,
            "has_next_page": false,
            "per_page": 10,
            "total_items": 10,
            "total_pages": 1
        }
    }

## Get products
### GET /product/list
| name | type | required | description | 
| -- | -- | -- | -- |
| `shops` | string | required | Shop ids, separated by comma (eg 11, 23, 45...) |
| `availabilityMin` | int | required | Minimum availability for a product |
| `availabilityMax` | int | required | Maximum availability for a product |

#### Response :

    items: {
        [
            {
                id: 42          // product id
                name: 'test', 	// product name
                manager: 11, 	// manager id
                productAvailabilities: [
                    shop: {
                        id: 14,
                        name: 'Shop name'
                   ),
                    availability: 23
                ]
            } 
        ...
        ],
        "pagination": {
            "current_page": 1,
            "has_previous_page": false,
            "has_next_page": false,
            "per_page": 10,
            "total_items": 10,
            "total_pages": 1
        }
    }

## Set product availability
### POST /product/availability
    [
        {
            "shop": 2,
            "product": 4,
            "availability": 1123
        },
        {
            "shop": 3,
            "product": 5,
            "availability": 13
        }
    ]

#### Response :
    [
        {
            "id": 8220,
            "shop":
                {
                    "id": 2
                },
            "product":
                {
                    "id": 4
                },
            "availability": 1123
        },
        {
            "id": 8221,
            "shop":
                {
                    "id": 3
                },
            "product": 
                {
                    "id": 5
                },
            "availability": 13
        }
    ]
