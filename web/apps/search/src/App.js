import React, { Component } from 'react';
import serialize from 'form-serialize';
import queryString from 'query-string';

class App extends Component {
    constructor(props) {
        super(props);
        this.form = null;
        this.search = this.search.bind(this);
        this.filters = window.filters;
        this.defaultValues = queryString.parse(window.location.hash, {arrayFormat: 'bracket'});
        this.state = {
            searching: false,
            searchUrl: null,
            rssSearchUrl: null,
            searchResult: null,
            searchMessage: null
        };
    }

    componentDidMount() {
        this.search();
    }

    search() {
        const query = serialize(this.form);
        const searchUrl = '/api/items.json?' + query;
        const rssSearchUrl = '/api/items.rss?' + query;
        this.setState({
            searching: true
        });
        window.location.hash = query;
        this.defaultValues = queryString.parse(window.location.hash, {arrayFormat: 'bracket'});
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                let state = {
                    searching: false,
                    searchResult: null,
                    searchStatus: xhr.status,
                    searchUrl: null,
                    rssSearchUrl: null
                };
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        state.searchResult = result;
                        state.searchMessage = null;
                        state.searchUrl = searchUrl;
                        if (result.length > 0) {
                            state.rssSearchUrl = rssSearchUrl;
                        }
                    } catch (ex) {
                        state.searchMessage = ex.message;
                    }
                } else {
                    state.searchMessage = 'Search failed';
                }
                this.setState(state);
            }
        };
        xhr.open('GET', searchUrl);
        xhr.send();
    }

    onSubmit (event, self) {
        event.preventDefault();
        self.search();
    }

    render() {
        return (
            <div className="row">
                <div className="col-md-4">
                    <form onSubmit={event => this.onSubmit(event, this)} ref={el => this.form = el}>
                        {this.filters.map((filter, index) => {
                            switch (filter.type) {
                            case 'search':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <SearchFilter title={filter.title} name={filter.name} controller={this} placeholder={filter.placeholder || null}/>
                                    </div>
                                );
                            case 'taxonomy':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <TaxonomyFilter title={filter.title} name={filter.name} controller={this}/>
                                    </div>
                                );
                            case 'geolocation':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <GeolocationFilter title={filter.title} name={filter.name} controller={this}/>
                                    </div>
                                );
                            case 'duration':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <DurationFilter title={filter.title} name={filter.name} controller={this}/>
                                    </div>
                                );
                            default:
                                return null;
                            }
                        })}
                        <button className="btn btn-primary" type="button" onClick={this.search}>Search</button>
                    </form>
                </div>
                <div className="col">
                    <div className="urls">
                        {!this.state.searching && this.state.rssSearchUrl ? <a className="btn btn-light" href={this.state.rssSearchUrl}>RSS</a> : null}
                    </div>
                    <Result data={this.state.searchResult} searching={this.state.searching} status={this.state.searchStatus} message={this.state.searchMessage}/>
                </div>
            </div>
        );
    }
}

class Result extends Component {
    render() {
        if (this.props.searching) {
            return (
                <div className="alert alert-info">Searching …</div>
            );
        } else if (this.props.data !== null) {
            return this.props.data.length === 0
                ? <div className="alert alert-warning">No matches</div>
                : this.props.data.map((item, index) => <ResultItem key={'result-' + index} data={item}/>);
        } else if (this.props.message !== null) {
            return (
                <div className="alert alert-warning">{this.props.message}</div>
            );
        } else {
            return null;
        }
    }
}

class ResultItem extends Component {
    render() {
        const item = this.props.data;
        return (
            <div className="result-item">
                <h1>{item.title}</h1>

                <div className="description" dangerouslySetInnerHTML={{__html: item.description}}></div>

                { item.subjects ? <TaxonomyList name="subjects" items={item.subjects}/> : null }
                { item.recommenders ? <TaxonomyList name="recommenders" items={item.recommenders}/> : null }
                { item.contexts ? <TaxonomyList name="contexts" items={item.contexts}/> : null }
                { item.audiences ? <TaxonomyList name="audiences" items={item.audiences}/> : null }

                <audio controls="controls">
                    <source src={item.enclosure.url} type={item.enclosure.type}/>
                </audio>
            </div>
        );
    }
}

class TaxonomyList extends Component {
    render() {
        return this.props.items && this.props.items.length > 0 ?
            (
                <div className={'taxonomy-' + this.props.name}>
                    <span className="name">{this.props.name}</span>
                    {this.props.items.map((item, index) => <TaxonomyItem key={'taxonomy-' + this.props.name + '-' + index} name={this.props.name} value={item.name}/>)}
                </div>
            ) : null;
    }
}

class TaxonomyItem extends Component {
    render() {
        return (
            <span className={'badge badge-secondary badge-taxonomy-' + this.props.name}>{this.props.value}</span>
        );
    }
}

class AbstractFilter extends Component {
    getDefaultValue() {
        return typeof this.props.controller !== 'undefined'
            && typeof this.props.controller.defaultValues !== 'undefined'
            && this.props.name in this.props.controller.defaultValues
            ? this.props.controller.defaultValues[this.props.name]
            : null;
    }
}

class SearchFilter extends AbstractFilter {
    render() {
        return (
            <input className="form-control" type="search" name={this.props.name} defaultValue={this.getDefaultValue()} placeholder={this.props.placeholder}/>
        );
    }
}

class TaxonomyFilter extends AbstractFilter {
    // @see https://stackoverflow.com/a/31725038
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            terms: []
        };
    }

    componentDidMount() {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const terms = JSON.parse(xhr.responseText);
                    this.setState({loading: false, terms: terms});
                } catch (ex) {}
            }
        };
        xhr.open('GET', '/api/' + this.props.name + '.json');
        xhr.send();
    }

    render() {
        return (
            <fieldset className="form-group taxonomy-filter">
                <legend>{this.props.title}</legend>
                { this.state.loading ? <div className="loading">Loading {this.props.name} …</div> : null }
                { this.state.terms.map(term => (
                    <TaxonomyTerm controller={this.props.controller} key={this.props.name + term.id} name={this.props.name} value={'' + term.id}>{term.name}</TaxonomyTerm>
                ))}
            </fieldset>
        );
    }
}

class TaxonomyTerm extends AbstractFilter {
    render() {
        const defaultValue = this.getDefaultValue();
        const checked = defaultValue !== null && defaultValue.indexOf(this.props.value) > -1;
        return (
            <div className="form-check">
                <label className="form-check-label">
                    <input type="checkbox" onChange={this.props.controller.search} className="form-check-input" name={this.props.name + '[]'} value={this.props.value} checked={checked}/>
                    { ' ' }
                    {this.props.children}
                </label>
            </div>
        );
    }
}

class GeolocationFilter extends AbstractFilter {
    constructor(props) {
        super(props);
        this.lat = null;
        this.lng = null;
        this.getPosition = this.getPosition.bind(this);
        this.setPosition = this.setPosition.bind(this);
        this.state = {
            loading: false
        };
    }

    getPosition() {
        this.setState({loading: true});
        const self = this;
        navigator.geolocation.getCurrentPosition(function(position) {
            self.setPosition(position);
            self.props.controller.search();
        });
    }

    setPosition(position) {
        this.setState({loading: false});
        if (this.lat !== null && this.lng !== null) {
            this.lat.value = position.coords.latitude.toFixed(6);
            this.lng.value = position.coords.longitude.toFixed(6);
        }
    }

    render() {
        const getPosition = "geolocation" in navigator ?
              (
                  <div className="get-location">
                      <button className="btn btn-light" type="button" onClick={this.getPosition}>{ this.state.loading ? 'Getting location …' : 'Use my location'}</button>
                  </div>
              )
              : null;

        return (
            <fieldset className="form-group geolocation-filter">
                <legend>{this.props.title}</legend>

                { 'Within ' }
                <input type="number" size="4" placeholder="10" name={this.props.name + '[radius]'} />
                { ' km of ' }<br/>
                (
                    <input type="text" size="12" placeholder="latitude" name={this.props.name + '[lat]'} ref={el => this.lat = el}/>
                    { ', ' }
                    <input type="text" size="12" placeholder="longitude" name={this.props.name + '[lng]'} ref={el => this.lng = el}/>
                )
                {getPosition}
            </fieldset>
        );
    }
}

class DurationFilter extends AbstractFilter {
    constructor(props) {
        super(props);
        this.lt = null;
        this.gt = null;
    }

    validate(event) {}

    render() {
        return (
            <fieldset className="form-group duration-filter">
                <legend>{this.props.title}</legend>
                <input type="text" size="10" placeholder="hh:mm:ss" name={this.props.name + '[gt]'} />
                { ' – ' }
                <input type="text" size="10" placeholder="hh:mm:ss" name={this.props.name + '[lt]'} />
            </fieldset>
        );
    }
}


export default App;

// Local Variables:
// mode: rjsx
// End:
