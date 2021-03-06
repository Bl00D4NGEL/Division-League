const _apiUrl = 'https://localhost:8000';

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

    static authEndpointUrl() {
        return this._getApiUrlBase() + '/auth';
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

    static addDeletedPlayerEndPoint() {
        return new Endpoint(this.playerEndpointUrl('add/confirm'), 'POST');
    }

    static deletePlayerEndPoint() {
        return new Endpoint(this.playerEndpointUrl(), 'DELETE')
    }

    static loginEndpoint() {
        return new Endpoint(this.loginEndpointUrl(), 'POST');
    }

    static authEndpoint() {
        return new Endpoint(this.authEndpointUrl(), 'GET');
    }

    static registerUserEndPoint() {
        return new Endpoint(this.registerEndpointUrl(), 'POST');
    }

    static mdrDivisionMembersEndPoint(division) {
        return new Endpoint(_mdrUrL + '/get/divisionMembers/' + division);
    }

    static getStatisticsEndPoint() {
        return new Endpoint(this._getApiUrlBase() + '/statistics/get/gamesPerWeek');
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
