const _apiUrl = 'http://localhost';
const _apiPort = 8000;

export default class Config {
    static _getApiUrlBase() {
        return _apiUrl + (_apiPort !== '' ? ":" + _apiPort : '');
    }

    static historyEndpointUrl(suffix) {
        return this._getApiUrlBase() + "/history" + (suffix !== undefined ? "/" + suffix : '');
    }

    static playerEndpointUrl(suffix) {
        return this._getApiUrlBase() + "/player" + (suffix !== undefined ? "/" + suffix : '');
    }

    static recentHistoryEndpoint() {
        return new Endpoint(this.historyEndpointUrl('get/recent'));
    }

    static getAllPlayersEndpoint() {
        return new Endpoint(this.playerEndpointUrl("get/all"));
    }

    static addHistoryEndPoint() {
        return new Endpoint(this.historyEndpointUrl('add'), 'POST');
    }

    static addPlayerEndPoint() {
        return new Endpoint(this.playerEndpointUrl('add'), 'POST');
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