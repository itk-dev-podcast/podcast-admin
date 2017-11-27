import React, { Component } from 'react';
// import './App.css';

class App extends Component {
    constructor(props) {
        super(props);
        this.form = null;
        this.search = this.search.bind(this);
        this.filters = window.filters;
        this.state = {
            searching: false,
            searchResult: null,
            searchMessage: null
        };
    }

    componentDidMount() {
        this.search();
    }

    search() {
        this.setState({searching: true});
        let data = new FormData(this.form);
        let queryString = '';
        for (const item of data) {
            if (queryString) {
                queryString += '&';
            }
            queryString += encodeURIComponent(item[0]) + '=' + encodeURIComponent(item[1]);
        }

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const result = JSON.parse(xhr.responseText);
                    this.setState({searching: false, searchResult: result, searchMessage: null});
                } catch (ex) {
                    this.setState({searching: false, searchResult: null, searchMessage: ex.message});
                }
            }
        };
        xhr.open('GET', '/api/items.json?' + queryString);
        xhr.send();
    }

    onSubmit (event, self) {
        event.preventDefault();
        self.search();
    }

    render() {
        return (
            <div className="row">
                <div className="col-md4">
                    <form onSubmit={event => this.onSubmit(event, this)} ref={el => this.form = el}>
                        {this.filters.map((filter, index) => {
                            switch (filter.type) {
                            case 'search':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <SearchFilter title={filter.title} name={filter.name} placeholder={filter.placeholder || null}/>
                                    </div>
                                );
                            case 'taxonomy':
                                return (
                                    <div key={'filter-' + filter.name}>
                                        <TaxonomyFilter title={filter.title} taxonomy={filter.name} controller={this}/>
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
                    <Result data={this.state.searchResult} searching={this.state.searching} message={this.state.searchMessage}/>
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

class SearchFilter extends Component {
    render() {
        return (
            <input className="form-control" type="search" name={this.props.name} placeholder={this.props.placeholder}/>
        );
    }
}

class TaxonomyFilter extends Component {
    // @see https://stackoverflow.com/a/31725038
    constructor(props) {
        super();
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
        xhr.open('GET', '/api/' + this.props.taxonomy + '.json');
        xhr.send();
    }

    render() {
        return (
            <fieldset className="form-group taxonomy-filter">
                <legend>{this.props.title}</legend>
                { this.state.loading ? <div className="loading">Loading {this.props.taxonomy} …</div> : null }
                { this.state.terms.map(term => (
                    <TaxonomyTerm controller={this.props.controller} key={this.props.taxonomy + term.id} name={this.props.taxonomy} value={term.id}>{term.name}</TaxonomyTerm>
                ))}
            </fieldset>
        );
    }
}

class TaxonomyTerm extends Component {
    render() {
        return (
            <div className="form-check">
                <label className="form-check-label">
                    <input type="checkbox" onChange={this.props.controller.search} className="form-check-input" name={this.props.name + '[]'} value={this.props.value}/>
                    { ' ' }
                    {this.props.children}
                </label>
            </div>
        );
    }
}

export default App;

// Local Variables:
// mode: rjsx
// End:
