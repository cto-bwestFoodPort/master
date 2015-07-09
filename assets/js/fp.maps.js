var geocoder;

function initialize() {
    geocoder = new google.maps.Geocoder();
}

function codeAddress(address, callback) {
    addressObj = {
        num: "",
        route: "",
        city: "",
        state: "",
        country: "",
        zip: ""
    }
    var maps_data = [];
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        var defined = new Array();
        var required = ["street_num", "street", "city", "state", "zip"];
        if (status == google.maps.GeocoderStatus.OK) {
            $.each(results[0].address_components, function(i, address_component) {
                switch (address_component.types[0]) {
                    case 'route':
                        {
                            addressObj.route = address_component.long_name;
                            defined.push('street');
                            break;
                        }
                    case 'locality':
                        {
                            addressObj.city = address_component.long_name;
                            defined.push('city');
                            break;
                        }
                    case 'country':
                        {
                            addressObj.country = address_component.long_name;
                            break;
                        }
                    case 'postal_code':
                        {
                            addressObj.zip = address_component.long_name;
                            defined.push('zip');
                            break;
                        }
                    case 'street_number':
                        {
                            addressObj.num = address_component.long_name;
                            defined.push('street_num');
                            break;
                        }
                    case 'administrative_area_level_1':
                        {
                            addressObj.state = address_component.long_name;
                            defined.push('state');
                            break;
                        }
                }
            });

            if($(defined).not(required).length === 0 && $(required).not(defined).length === 0)
            {
                $('#default_addr').val(addressObj.num + " " + addressObj.route + " " + addressObj.city + ", " + addressObj.state + " " + addressObj.zip).attr('data-city', addressObj.city);
                $('#default_addr').data('zip', addressObj.zip);
                $('#default_addr').attr('data-zip', $('#default_addr').data('zip'));
                $('#default_addr').data('city', addressObj.city);
                $('#default_addr').data('num', addressObj.num);
                $('#default_addr').data('street', addressObj.route);
                $('#default_addr').data('state', addressObj.state);

                (callback !== undefined) ? callback.call() : "";//Do Nothing;

                
            }else
            {
                defined.length = 0;
                alert("Please enter a full and proper address...");
            }
        } else {
            alert("Displaying results failed: Please be sure you entered a correct address!");
        }
    });
}
function getDistance(options, $element) {
    settings = {
        'mode': 'DRIVING',
        'callbackAction': 'secret'
    };

    $.extend(settings, options);

    var origin1 = settings.fromAddr;
    var destinationA = settings.toAddr;

    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [origin1],
        destinations: [destinationA],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.IMPERIAL,
    }, function(response, status) {
        if (status === "OK") {
            if (settings.callbackAction !== "secret") {
            	var origins = response.originAddresses;
                for (var i = 0; i < origins.length; i++) {
                    var results = response.rows[i].elements;
                    for (var j = 0; j < results.length; j++) {
                        var element = results[j];
                        var distance = element.distance.text;
                        var duration = element.duration.text;
                        var from = origins[i];
                        
                        var distanceRules = getDistanceRules(distance);
                        if($(distanceRules).text() === " (*Out of range for the address entered)"){
                            $element.parents('.well').first().remove();
                        }
                        $element.text(distance).append(distanceRules);
                        if($('.rest_disp .well').length == 0){
                            $('.rest_disp').text("Sorry, we have not yet reached your area, keep checking back though. We are expanding daily.")
                        }
                    }
                }
            }
        }
    });
}

function getDistanceRules(distance){
    if(typeof(distance) !== "string"){
        throw new Error("getDistanceRules expects parameter 1 to be a string, "+typeof(distance)+" given.");
    }else
    {
        distance = distance.replace(/,/g, "");
        if(parseFloat(distance) > 3.0)
        {
            if(parseFloat(distance) > 15.0){
                return $('<span>', {style: "color:red;", text:" (*Out of range for the address entered)"})
            }else
            {
                return $('<span>', {style: "color:red;", text:" (*Involves a mileage surcharge)"});
            }
        }
    }
}