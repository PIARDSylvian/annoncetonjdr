import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

/*
 * annoncetonjdr V3
 */
$(function() {
	function initmap() {
		let zoom = 16;

		if(party.length > 1) {
			zoom = 5;
		}

		var map = L.map('map').setView([party[0].lat, party[0].lng], zoom);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			minZoom: 2
 
		}).addTo(map);

		$.each(party, function(i, item) {
			let popup = item.address + '<br>';
			if(item.parties && item.parties.length > 0) {
				popup += '<br>parties :'
				$.each( item.parties, function(idx, part){
						popup +='<br><a href="' + part.url + '">' + part.partyName + '</a>';
				});
			};
			if(item.events && item.events.length > 0) {
				popup += '<br>events :'
				$.each( item.events, function(idx, event){
						popup += '<br><a href="' + event.url + '">' + event.name + '</a>';
				});
			};
			if(item.association) {
				popup += '<br>association :'
				popup += '<br><a href="' + item.association.url + '">' + item.association.name + '</a>';
			};
			L.marker([item.lat, item.lng]).addTo(map)
			.bindPopup(popup);
		});
	}

	initmap();
});
