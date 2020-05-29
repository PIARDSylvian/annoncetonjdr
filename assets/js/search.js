import React from 'react';
import ReactDOM from 'react-dom';
import { renderToStaticMarkup } from "react-dom/server";
import { divIcon } from 'leaflet';
import { Map, Marker, Popup, TileLayer } from 'react-leaflet';
import Control from 'react-leaflet-control';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';

/*
 * annoncetonjdr V3
 */
$(function() {

	/**
	 * OpenStreetMap 
	 */
	class OpenStreetMap extends React.Component {

		/**
		 * icon
		 */
		selectAndCreateIcon(type) {
			let iconClass;
			let color = 'blue';
			let shrink = 'shrink-10';
			let up = 'up-3';
			switch(type) {
				case 'start':
					iconClass = 'fa-map-pin';
					color = 'red';
					shrink = 'shrink-8';
					up = 'up-2';
					break;
				case 'parties':
					iconClass = 'fa-dice';
					break;
				case 'events':
					iconClass = 'fa-calendar-alt';
					break;
				case 'association':
					iconClass = 'fa-users';
					break;
				default:
					up = 'up-2';
					iconClass = 'fa-circle';
			}
			const iconMarkup = renderToStaticMarkup(
				<div className="fa-3x">
					<span className="fa-layers fa-fw">
						<i className={`fas fa-map-marker ${color}`}></i>
						<i className={`fa-inverse fas ${iconClass}`} data-fa-transform={`${shrink} ${up}`}></i>
					</span>
				</div>
			);

			return divIcon({ html: iconMarkup, popupAnchor: [30, -10], iconAnchor: [0, 20] });
		}
		
		render() {
			const center = [this.props.items[0].lat, this.props.items[0].lng];
			const markers = [];
			const maxDistance = Math.round(this.props.items[this.props.items.length-1].distance);
			let zoom = 16;

			if (maxDistance > 2000) { zoom = 4 } else
			if (maxDistance > 1000) { zoom = 5 } else
			if (maxDistance > 500) { zoom = 6 } else
			if (maxDistance > 200) { zoom = 7 } else
			if (maxDistance > 20) { zoom = 9 } else
			if (maxDistance > 10) { zoom = 11 }

			for (const [index, value] of this.props.items.entries()) {
				let customMarkerIcon;

				if (index == 0) {
					customMarkerIcon = this.selectAndCreateIcon('start');
				} else if (value.parties.length > 0 && value.events.length == 0 && value.association == null) {
					customMarkerIcon = this.selectAndCreateIcon('parties');
				} else if (value.events.length > 0 && value.parties.length == 0 && value.association == null) {
					customMarkerIcon = this.selectAndCreateIcon('events');
				} else if (value.association != null && value.parties.length == 0 && value.events.length == 0) {
					customMarkerIcon = this.selectAndCreateIcon('association');
				} else {
					customMarkerIcon = this.selectAndCreateIcon();
				}

				markers.push(
					<Marker key={index} position={[value.lat,value.lng]} icon={customMarkerIcon}>
						<Popup>
							<PopupContent items={value}/>
						</Popup>
					</Marker>
				);
			}

			return (
				<Map ref='map' center={center} zoom={zoom}>
					<TileLayer
						attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
						url='https://{s}.tile.osm.org/{z}/{x}/{y}.png'
					/>
					{markers}
					<Control position="topright" >
						<button className="btn btn-light btn-outline-dark" onClick={ () => {this.refs.map.leafletElement.setView(center, zoom)} }> centrer </button>
					</Control>
				</Map>
			);
		}
	}

	let countPopup=0;
	/**
	 * PopupContent
	 */
	class PopupContent extends React.Component {
		
		parties(parties) {
			let output = [];
			
			if(parties.length === 0) { return };
			
			for (const [index, value] of parties.entries()) {
				output.push(<span key={countPopup} ><a href={ROUTES.APP_PARTY_SHOW + value.id}>{value.partyName}</a><br /></span>);
				countPopup++;
			}

			return <span><br />Parties :<br /><br />{output}</span>;
		};

		events(events) {
			let output = [];

			if(events.length === 0) { return };

			for (const [index, value] of events.entries()) {
				output.push(<span key={countPopup} ><a href={ROUTES.APP_EVENT_SHOW + value.id}>{value.name}</a><br /></span>);
				countPopup++;
			}

			return <span><br />Events :<br /><br />{output}</span>;
		};

		association(association) {

			if(!association) { return };
			
			let output = <span key={countPopup} ><a href={ROUTES.APP_ASSOCIATION_SHOW + association.id}>{association.name}</a></span>;
			countPopup++;
			return <span><br />Assoc :<br /><br />{output}</span>;
		}

		render() {
			return (
				<p>
					<strong>{this.props.items.address}</strong><br />
					{this.parties(this.props.items.parties)}
					{this.events(this.props.items.events)}
					{this.association(this.props.items.association)}
				</p>
			);
		}
	}

	/**
	 * CardParty 
	 */
	class CardParty extends React.Component {
		render() {
			let output = [];
			for (const [index, party] of this.props.parties.entries()) {
				const img = (party.gameName.imageUrl) ? party.gameName.imageUrl : 'https://cdn.pixabay.com/photo/2014/12/14/18/33/cube-568105_960_720.jpg';
				const desc = (party.gameDescription) ? party.gameDescription : 'Aucune description';
				if (desc.length > 50) { desc = desc.slice(0, 150) + ' ...';}
				let date = null;
				if (this.props.byDate) {
					date = moment(party.date).format("MM/DD/YY HH:mm");
				} else {
					date = moment(new Date(party.date)).format("MM/DD/YY HH:mm");
				}

				output.push(
					<div key={party.id} className="col-md-4 card-container">
						<div className="card-flip">
							<div className="card mt-2 mb-3 front">
								<div className="card-header">
									<h5 className="card-title">{party.partyName}</h5>
								</div>
								<img src={img} alt={party.gameName.Name} className="card-img-top img-fluid" />
								<div className="card-body">
									<p className="card-text">{desc}</p>
								</div>
								<div className="card-footer text-center">
									<p className="card-text"><i className="far fa-calendar-alt"></i> <small className="text-muted">{date}</small></p>
								</div>
							</div>
							<div className="card mt-2 mb-3 back">
								<div className="card-header">
									Détails
								</div>
								<ul className="list-group list-group-flush">
									<li className="list-group-item">Place restante : <span className="badge badge-pill badge-secondary">{party.maxPlayer - (party.alreadySubscribed + party.registeredPlayers.length)}</span></li>
									<li className="list-group-item">Adresse : {this.props.address}</li>
									<li className="list-group-item">commentaires : <span className="badge badge-pill badge-secondary">{party.commentaries.length}</span></li>
								</ul>
								<div className="card-body"></div>
								<div className="card-footer text-center">
									<a href={ROUTES.APP_PARTY_SHOW + party.id} className="btn btn-primary">Voir plus</a>
								</div>
							</div>
						</div>
					</div>
				);
			}
			return output;
		}
	}

	/**
	 * CardEvents 
	 */
	class CardEvents extends React.Component {
		render() {
			let output = [];
			for (const [index, event] of this.props.events.entries()) {
				const desc = (event.description) ? event.description : 'Aucune description';
				if (desc.length > 50) { desc = desc.slice(0, 150) + ' ...';}
				const dateStart = moment(new Date(event.dateStart)).format("MM/DD/YY HH:mm");
				const dateFinish = moment(new Date(event.dateFinish)).format("MM/DD/YY HH:mm");

				const img = (event.imageUrl && !event.pendding) ? <img src={event.imageUrl} alt={event.name} className="card-img-top img-fluid" /> : null;

				output.push(
					<div key={event.id} className="col-md-4 card-container">
						<div className="card-flip">
							<div className="card mt-2 mb-3 front">
								<div className="card-header">
									<h5 className="card-title">{event.name}</h5>
								</div>
								{img}
								<div className="card-body">
									<p className="card-text">{desc}</p>
								</div>
								<div className="card-footer text-center">
									<p className="card-text"><i className="far fa-calendar-alt"></i> <small className="text-muted">{dateStart}</small></p>
									<p className="card-text"><i className="far fa-calendar-alt"></i> <small className="text-muted">{dateFinish}</small></p>
								</div>
							</div>
							<div className="card mt-2 mb-3 back">
								<div className="card-header">
									Détails
								</div>
								<ul className="list-group list-group-flush">
									<li className="list-group-item">Adresse : {this.props.address}</li>
									<li className="list-group-item">commentaires : <span className="badge badge-pill badge-secondary">{event.commentaries.length}</span></li>
								</ul>
								<div className="card-body"></div>
								<div className="card-footer text-center">
									<a href={ROUTES.APP_EVENT_SHOW + event.id} className="btn btn-primary">Voir plus</a>
								</div>
							</div>
						</div>
					</div>
				);
			}
			return output;
		}
	}

	/**
	 * CardAssociation
	 */
	class CardAssociation extends React.Component {
		render() {
			let output = [];
			const association = this.props.association;
			if (!association) {
				return output;
			}

			const desc = (association.description) ? association.description : 'Aucune description';
			if (desc.length > 50) { desc = desc.slice(0, 150) + ' ...';}

			const img = (association.imageUrl && !association.pendding) ? <img src={association.imageUrl} alt={association.name} className="card-img-top img-fluid" /> : null;

			output.push(
				<div key={association.id} className="col-md-4 card-container">
					<div className="card-flip">
						<div className="card mt-2 mb-3 front">
							<div className="card-header">
								<h5 className="card-title">{this.props.association.name}</h5>
							</div>
							{img}
							<div className="card-body">
								<p className="card-text">{desc}</p>
							</div>
						</div>
						<div className="card mt-2 mb-3 back">
							<div className="card-header">
								Détails
							</div>
							<ul className="list-group list-group-flush">
								<li className="list-group-item">Adresse : {this.props.address}</li>
								<li className="list-group-item">commentaires : <span className="badge badge-pill badge-secondary">{association.commentaries.length}</span></li>
							</ul>
							<div className="card-body"></div>
							<div className="card-footer text-center">
								<a href={ROUTES.APP_ASSOCIATION_SHOW + association.id} className="btn btn-primary">Voir plus</a>
							</div>
						</div>
					</div>
				</div>
			);
			return output;
		}
	}

	/**
	 * Card 
	 */
	class Cards extends React.Component {
		render() {
			const output = [];
			let count = 0;

			if(this.props.byDate) {
				for (const [idx, value] of this.props.byDate.entries()) {
					if(!value.dateFinish) {
						output.push(<CardParty key={count} byDate='true' parties={[value]} address={value.address}/>)
					} else {
						output.push(<CardEvents key={count} events={[value]} address={value.address}/>)
					}
					count++;
				}
			} else {
				for (const [idx, value] of this.props.items.entries()) {
					output.push(<CardParty key={count} parties={value.parties} address={value.address}/>)
					count++;
					output.push(<CardEvents key={count} events={value.events} address={value.address}/>)
					count++;
					output.push(<CardAssociation key={count} association={value.association} address={value.address}/>)
					count++;
				}
			}

			return <div className="row mt-3 mb-2">{output}</div>;
		}
	}

	const stepIncrement = 12;

	/**
	 * Step
	 */
	class Step extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				step : stepIncrement,
				stop: false
			};
			this.more = this.more.bind(this);
		}

		static getDerivedStateFromProps(nextProps, prevState){
			if(nextProps.resetStep!==prevState.resetStep){
				if (nextProps.resetStep == true){
					nextProps.clickHandler(true);
				}
				return { resetStep: nextProps.resetStep, stop: false, step: stepIncrement};
			}
			else return null;
		 }

		async more() {
			const step = this.state.step;
			const items = this.props.items;

			let count = 0;
			for (const [idx, l] of items.entries()) {
				count += (l.parties.length + l.events.length);
				if (l.association != null) { count += 1; } 
			}

			const load = (count < (this.state.step + stepIncrement));

			if (!this.props.stop && load) {
				await this.props.clickHandler(false);
				this.more();
			} else if (!load) {
				this.setState({
					step: step + stepIncrement,
				});
			} else if (this.props.stop && count > this.state.step && load) {
				this.setState({
					step: step + stepIncrement,
					stop: true
				});
			} else {
				this.setState({
					stop: true
				});
			}
		}

		step(items, step) {
			let countItems = step;
			const ouputItems = [];

			for (const [idx, l] of items.entries()) {
				if (idx == 0 && l.distance == 0 && l.parties.length == 0 && l.events.length == 0 && l.association == null && items.length > 1) {
					ouputItems.push(l)
				}
				if (l.parties.length > countItems) {
					l = { ...l, parties: l.parties.slice(0, countItems) };
					countItems = 0;
				} else {
					l = { ...l, parties: l.parties.slice(0, l.parties.length) };
					countItems -= l.parties.length;
				}

				if (l.events.length > countItems) {
					l = { ...l, events: l.events.slice(0, countItems) };
					countItems = 0;
				} else {
					l = { ...l, events: l.events.slice(0, l.events.length) };
					countItems -= l.events.length;
				}

				if ( 1 <= countItems && (l.association != null)) {
					l = { ...l, association: l.association };
					countItems -= 1;
				} else {
					l = { ...l, association: null };
				}

				if (l.parties.length > 0 || l.events.length > 0 || l.association != null) {
					ouputItems.push(l);
				}
			}

			return ouputItems;
		}

		sortByDate(location) {
			let items = [];
			for (const [idx, l] of location.entries()) {
				for (const [idx, p] of l.parties.entries()) {
					const date = new Date(p.date);
					p = { ...p, date: date, address: l.address};
					items.push(p)
				}
				for (const [idx, e] of l.events.entries()) {
					const date = new Date(e.dateFinish);
					e = { ...e, date: date, address: l.address};
					items.push(e)
				}
			}
			items.sort((a, b) => a.date - b.date);
			return items;
		}

		render() {
			const step = this.state.step;
			const items = this.step(this.props.items, step);
			let byDate = null;

			if (this.props.items['byDate']) {
				byDate = this.sortByDate(items);
			}

			if (items.length == 0 ) {
				return <MoreButton items={items} error={this.props.error} isLoaded={this.props.isLoaded} stop={this.state.top} clickHandler={()=>this.more()} />
			}
			return (
				<div className="col-xs-12">
					<OpenStreetMap items={items} />
					<Cards items={items} byDate={byDate} />
					<MoreButton items={items} error={this.props.error} isLoaded={this.props.isLoaded} stop={this.state.stop} clickHandler={()=>this.more()} />
				</div>
			);
		}
	}

	/**
	 * Filter
	 */
	class Filter extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				game: null,
				filters : {
					parties: false,
					events: false,
					association: false,
					disponible: false,
					minor: false,
					openCampaign: true,
					distance: false,
				}
			}
		}

		updatefilter(filter) {
			const state = this.state.filters;
			if(filter == 'date') {filter='distance'};
			state[filter] = (this.state.filters[filter] == true)?false:true;
			this.setState({filters: state});
		}

		handleSelect(e) {
			const state = (e)?Number(e.value):null;
			this.setState({game: state});
		}
		
		search(filteredItems) {
			const search = this.state.game;
			const parties = this.state.filters.parties;
			const disponible = !this.state.filters.disponible;
			const minor = !this.state.filters.minor;
			const openCampaign = !this.state.filters.openCampaign;
			const distance = this.state.filters.distance;
			const newList = [];

			if (!parties) {
				for (const [idx, l] of filteredItems.entries()) {
					const newParties = []
					for (const [idx, p] of l.parties.entries()) {
						if(!disponible || (disponible && (p.maxPlayer - (p.alreadySubscribed + p.registeredPlayers.length)) > 0)) {
							if(p.minor == minor) {
								if(p.openedCampaign == openCampaign) {
									if (search && p.gameName.id == search) {
										newParties.push(p)
									} else if (!search) {
										newParties.push(p)
									}
								}
							}
						}
					}
					l = { ...l, parties: newParties };
					newList.push(l)
				}
				if(distance) {
					newList['byDate'] = true
					return newList;
				}
				return newList;
			}
			if(distance) {
				filteredItems['byDate'] = true
				return filteredItems;
			}
			return filteredItems;
		}

		filter() {
			const filters = this.state.filters;
			const filteredItems = [];

			for (const [idx, l] of this.props.items.entries()) {
				if (filters.parties) {
					l = { ...l, parties: [] };
				}
				if (filters.events) {
					l = { ...l, events: [] };
				}
				if (filters.association) {
					l = { ...l, association: null };
				}
				if (l.parties.length > 0 || l.events.length > 0  || l.association != null ||l.distance == 0) {
					filteredItems.push(l);
				}
			}
			return this.search(filteredItems);
		}

		render() {
			const filters = this.state.filters;
			let count = 0;
			const filtersBtn = [];
			for (const [key, value] of Object.entries(filters)) {
				if (key == 'distance' && value) {
					key = 'date';
				}
				filtersBtn.push(
					<button key={count} type="button" className={`btn mb-1 btn-${value ? "danger" : "primary"}`} data-toggle="button" aria-pressed="false" onClick={()=>this.updatefilter(key)}>
						{key}
					</button>
				);
				count++
			}

			const formatOptionLabel = ({ value, label, img }) => (
				<div>
					<img src={img} height="40px" style={{maxWidth: "150px"}} className='mr-2' />{label}
				</div>
			);
			
			const selectStyles = {
				container: (base, state) => ({...base, zIndex: "9999"})
			};
			
			const items = this.filter();

			return (
				<>
					<div className="col-md-6 col-xs-12">
						<div className="form">
							<Select placeholder="Choix de Jeux" isClearable={true} styles={selectStyles} formatOptionLabel={formatOptionLabel} options={GAMES} onChange={this.handleSelect.bind(this)}/>
						</div>
					</div>
					<div className="col-md-12 mt-3 mb-2">
						<div className="d-flex flex-wrap justify-content-between">
							{filtersBtn}
						</div>
					</div>
					<div className="col-md-12">
						<Step items={items} error={this.props.error} isLoaded={this.props.isLoaded} stop={this.props.stop} resetStep={this.props.resetStep} clickHandler={this.props.handler} />
					</div>
				</>
			);
		}
	}

	/**
	 * Button
	 */
	class MoreButton extends React.Component {
		render() {
			if(!this.props.isLoaded) {
				return <div className='text-center'><i className="fas fa-spinner fa-2x fa-spin"></i></div>;
			}

			if(this.props.error) {
				return <div className='text-center'>Erreur : {this.props.error.message}</div>;
			}

			if((this.props.stop || this.props.stop == undefined) && this.props.items.length == 0) {
				return <div className='text-center'>Aucun resultat</div>;
			}

			if(this.props.stop && this.props.items.length > 0) {
				return <div className='text-center'>Aucun autres resultats</div>;
			}

			return (
				<button className="btn btn btn-secondary btn-lg btn-block" onClick={this.props.clickHandler}>
					<i className="fas fa-plus-circle fa-2x"></i>
				</button>
			);
		}
	}

	/**
	 * SearchPage
	 */
	class Search extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				lat: null,
				lng: null,
				address: null,
				error: null,
				isLoaded: true,
				step: 0,
				items: [],
				stop: false,
				resetStep:false
			};
	
			this.loadMore = this.loadMore.bind(this);
		}

		async loadMore(resetStep) {
			this.setState({isLoaded: false})
			let step = 0
			if(! resetStep) {step = this.state.step }

			await fetch(window.location.href,{
				method: 'POST',
				headers: {
					"X-Requested-With": "XMLHttpRequest"
				},
				body: JSON.stringify({step: step, lat: this.state.lat, lng: this.state.lng, address: this.state.address}),
			})
			.then(
				r => r.json()
				.then(
					data => {
						if(r.status !== 200){
							throw new Error(data.error)
						} else { return data }
					}
				)
			)
			.then(
				(result) => {
					let items = null;
					let step = null;

					if (resetStep) {
						items = result;
						if (result.length > 0 && result[0].distance == 0) {
							step = (result.length - 1);
						} else {
							step = result.length;
						}
					} else {
						items = this.state.items.concat(result);
						step = this.state.step + result.length;
					}

					const stop = (!result.length>0)?true:false;

					this.setState({
						isLoaded: true,
						items: items,
						step: step,
						stop: stop,
						resetStep: false
					});
				},
				(error) => {
					this.setState({
						isLoaded: true,
						error,
						stop: true
					});
				}
			)
		}

		handleSelect(e) {
			if(e) {
				this.setState({
					lat: (e)? e.lat : null,
					lng: (e)? e.lng : null,
					address: (e)? e.value : null,
					resetStep: true
				});
			} else if (this.state.address) {
				this.setState({
					lat: (e)? e.lat : null,
					lng: (e)? e.lng : null,
					address: (e)? e.value : null,
					resetStep: true
				});
			} else {
				this.setState({
					lat: null,
					lng: null,
					address: null,
					items: [],
				});
			}
		}

		render() {
			const {error, isLoaded, stop, items, resetStep} = this.state;
			const promiseOptions = inputValue => {
				const url = "http://photon.komoot.de/api?" + $.param({q: inputValue, lang: 'fr'});
				return fetch(url)
					.then(response => response.json())
					.then(response => {
						let o_data = [];
						$.each(response.features, function(idx, i_data) {
							let l_str = "";
							if (i_data.properties.name) { l_str += i_data.properties.name }
							if (i_data.properties.postcode) { l_str += ", " + i_data.properties.postcode }
							if (i_data.properties.city) { l_str += ", " + i_data.properties.city }
							if (i_data.properties.state) { l_str += ", " + i_data.properties.state }
							if (i_data.properties.name) { l_str += ", " + i_data.properties.country}

							o_data.push({
								label: l_str,
								lat: i_data.geometry.coordinates[1],
								lng: i_data.geometry.coordinates[0],
								value: l_str
							});
						});
						return o_data;
					})
					.catch(err => {
						console.log('error', err);
					});
				
			};

			const formatOptionLabel = ({ value, label, lat, lng }) => (
				<div>
					<div>{label}</div>
					<div className="d-none">{lat}</div>
					<div className="d-none">{lng}</div>
				</div>
			);

			const selectStyles = {
				container: (base, state) => ({...base, zIndex: "9999"})
			};

			return (
				<div className="row">
					<div className="col-md-6 col-xs-12">
						<div className="form">
							<label className="sr-only" htmlFor="inputLocation">Ville de recherche</label>
							<AsyncSelect placeholder="Rechercher proche d'un endroits" cacheOptions isClearable={true} formatOptionLabel={formatOptionLabel} styles={selectStyles} loadOptions={promiseOptions} onChange={this.handleSelect.bind(this)} isDisabled={!isLoaded}/>
						</div>
					</div>
					<Filter items={items} error={error} isLoaded={isLoaded} stop={stop} handler={this.loadMore} resetStep={resetStep}/>
				</div>
			)
		}
	}

	ReactDOM.render(<Search />, document.getElementById('map'))
});
