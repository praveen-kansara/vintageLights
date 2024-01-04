var center_lat = $("#center_latitude").val();
var center_long = $("#center_longitude").val();
mapboxgl.accessToken = "pk.eyJ1IjoiYXdicmF1bjIwMjAiLCJhIjoiY2toNGpqbms4MDZlazJybWxtc3loaHR0OSJ9.XBL6IbyG" +
        "We5X20cwGtkWxg";
var map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/light-v10",
    center: [
        center_long, center_lat
    ],
    zoom: 11
});
map.addControl(new mapboxgl.NavigationControl);
var data_obj = [];
function flyToStore(e) {
    map.flyTo({center: e.geometry.coordinates, zoom: 15})
}
function createPopUp(e) {
    var a = document.getElementsByClassName("mapboxgl-popup");
    a[0] && a[0].remove();
    new mapboxgl
        .Popup({
        closeOnClick: !1,
        maxWidth: "300px",
        anchor: "bottom"
    })
        .setLngLat(e.geometry.coordinates)
        .setHTML('<div class="map-popup"><div class="img-box"><img src="' + e.properties.image_path + '" /></div><h2>' + e.properties.name + '</h2><p class="property-desc">' + e.properties.description + '</p><div class="property-list-btn text-center"><a href="' + e.properties.flyer_path + '" class="btn-black" target="_blank">Download Flyer</a> <a href="' + e.properties.uri + '" class="btn-black-border">View Property</a></div></div>')
        .addTo(map)
}
map.on("load", () => {
    var e = $("#property_location").val();
    $.ajax({
        url: "./?q=Ajax/PropertyMapDetails",
        type: "POST",
        data: {
            location: e
        },
        dataType: "text",
        success: function (e) {
            data_obj = $.parseJSON(e),
            map.addSource("property", {
                type: "geojson",
                data: e
            }),
            data_obj
                .features
                .forEach(function (e, a) {
                    var t = document.createElement("div");
                    t.id = "marker-" + e.properties.id,
                    t.className = "marker";
                    new mapboxgl
                        .Marker(t)
                        .setLngLat(e.geometry.coordinates)
                        .addTo(map);
                    t.addEventListener("click", function (a) {
                        flyToStore(e),
                        createPopUp(e),
                        a.stopPropagation()
                    })
                })
        }
    })
}),
map.on("click", function (e) {
    var a = document.getElementsByClassName("mapboxgl-popup");
    a[0] && a[0].remove()
}),
$(".property-block").on("click", function (e) {
    for (var a = 0; a < data_obj.features.length; a++) 
        if (this.id === data_obj.features[a].properties.id) {
            var t = data_obj.features[a];
            flyToStore(t),
            createPopUp(t)
        }
    }),
$(document).ready(function () {
    new hcSticky(".map-section", {
        stickTo: "#map-sticky",
        responsive: {
            1024: {
                disable: !0
            }
        }
    })
});