class WidgetCustomGoogleMaps extends elementorModules.frontend.handlers.Base {   
    getDefaultSettings() {
		return {
			selectors: {
				map: '.dragon-map',
			},
		};
	}
    
    setMap(map) {
        this.googleMap = map;
    }

    getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		const elements = {
			$map: this.$element.find( selectors.map ),
		};

		return elements;
	}
    
    clearMarker() {
        this.markers.forEach(function(marker) {
            marker.setMap(null);
        }) 

        this.markers = [];
    }

    bindEvents() {
        if (this.isEdit) {
            this.googleMap = null;
            this.markers = [];

            var haightAshbury = {lat: 37.769, lng: -122.446};
            const map = onGoogleMapInit(this.elements.$map, haightAshbury); 
            this.markers.push(addMarker(haightAshbury, map));

            map.addListener('click', function(event) {
                this.clearMarker();
                this.markers.push(addMarker(event.latLng, map));
            }.bind(this));

            this.setMap(map);
        }
    }

    updateElementHeight(value) {
        this.elements.$map.css('height', value +'px');
    }

    onElementChange(propertyName, controlView, elementView) {
        if (propertyName === 'height') {
            this.updateElementHeight(controlView.options.elementSettingsModel.changed.height.size);
        }
    }

    onInit(argv) {
        super.onInit(argv);
        debugger
    }
    
 }


 jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( WidgetCustomGoogleMaps, {
            $element,
        } );
    };
    elementorFrontend.hooks.addAction( 'frontend/element_ready/custom_google_maps.default', addHandler ); 

 } );

 function onGoogleMapInit(el, haightAshbury) {    
    if (el) {
        var map = new google.maps.Map(el[0], {
            zoom: 12,
            center: haightAshbury,
            mapTypeId: 'terrain'
        });
        return map;
    };
    
}

function addMarker(location, map) {
	var marker = new google.maps.Marker({
	  position: location,
	  map: map
    });
    
    return marker;
}






