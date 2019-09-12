
    const _apiUrl = 'http://localhost';
    const _apiPort = 8000;

export default class Config {
    static historyEndpoint(suffix) {
        return _apiUrl + ":" + _apiPort + "/history" + (suffix !== undefined ? "/" + suffix : '');
    }
}
