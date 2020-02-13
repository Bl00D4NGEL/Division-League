const _apiUrl = 'https://localhost:8000';

const _mdrUrL = 'http://localhost:2048';

export default class Config {
    static _getApiUrlBase() {
        return _apiUrl;
    }

    static playerEndpointUrl(suffix) {
        return this._getApiUrlBase() + '/player' + (suffix !== undefined ? '/' + suffix : '');
    }

    static getAllPlayersEndpoint() {
        return new Endpoint(this.playerEndpointUrl('get/all'));
    }

    static addPlayerEndPoint() {
        return new Endpoint(this.playerEndpointUrl('add'), 'POST');
    }

    static mdrDivisionMembersEndPoint(division) {
        return new Endpoint(_mdrUrL + '/get/divisionMembers/' + division);
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
