const _apiUrl = 'http://localhost:8000';

const _mdrUrL = 'http://localhost:2048';

export default class Config {
    static _getApiUrlBase() {
        return _apiUrl;
    }

    static historyEndpointUrl(suffix) {
        return this._getApiUrlBase() + '/history' + (suffix !== undefined ? '/' + suffix : '');
    }

    static playerEndpointUrl(suffix) {
        return this._getApiUrlBase() + '/player' + (suffix !== undefined ? '/' + suffix : '');
    }

    static loginEndpointUrl() {
        return this._getApiUrlBase() + '/login';
    }
    
    static registerEndpointUrl() {
        return this._getApiUrlBase() + '/register';
    }

    static recentHistoryEndpoint() {
        return new Endpoint(this.historyEndpointUrl('get/recent'));
    }

    static getAllPlayersEndpoint() {
        return new Endpoint(this.playerEndpointUrl('get/all'));
    }

    static addHistoryEndPoint() {
        return new Endpoint(this.historyEndpointUrl('add'), 'POST');
    }

    static addPlayerEndPoint() {
        return new Endpoint(this.playerEndpointUrl('add'), 'POST');
    }

    static loginEndpoint() {
        return new Endpoint(this.loginEndpointUrl(), 'POST');
    }

    static registerUserEndPoint() {
        return new Endpoint(this.registerEndpointUrl(), 'POST');
    }

    static mdrDivisionMembersEndpoint(division) {
        return new Endpoint(_mdrUrL + '/divisionMembers/' + division);
    }
}

class Endpoint {
    constructor(url, method) {
        this._url = url;
        this._method = (method === undefined ? 'GET' : method);
    }

    url() {
        return this._url
    }

    method() {
        return this._method;
    }
}