class WidgetCustomGoogleMaps extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        console.log('this is external componet');
        console.log(this.isEdit);
        if (this.isEdit) {
            onGoogleMapInit();
            elementor.hooks.addAction( 'panel/open_editor/widget/height', function( panel, model, view ) {
                alert( 'Some Message' );
                var $element = view.$el.find( '.elementor-selector' );
             
                if ( $element.length ) {
                    $element.click( function() {
                      alert( 'Some Message' );
                    } );
                }
             } );
             
        }
    }
 
    getDefaultElements() {}
 
    bindEvents() {}
 }


 jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( WidgetCustomGoogleMaps, {
            $element,
        } );
    };
   
    elementorFrontend.hooks.addAction( 'frontend/element_ready/custom_google_maps.default', addHandler );

 } );
 
var maps = [];
var markers = [];
function onGoogleMapInit() {
    var haightAshbury = {lat: 37.769, lng: -122.446};
    var mapElements = document.getElementsByClassName('dragon-map');
    console.log(mapElements);
    if (mapElements) {
        for(var idx = 0 ; idx < mapElements.length; idx ++) {
            var map = new google.maps.Map(mapElements[idx], {
                zoom: 12,
                center: haightAshbury,
                mapTypeId: 'terrain'
            });
            map.addListener('click', function(event) {
                addMarker(event.latLng, map);
            });
        
            // Adds a marker at the center of the map.
            addMarker(haightAshbury, map);
            maps.push(map);
        };
    }
}

function addMarker(location, map) {
	var marker = new google.maps.Marker({
	  position: location,
	  map: map
	});
	markers.push(marker);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
	  markers[i].setMap(map);
	}
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
	setMapOnAll(null);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
	clearMarkers();
	markers = [];
}
