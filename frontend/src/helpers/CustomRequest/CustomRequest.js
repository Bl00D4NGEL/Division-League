export default class CustomRequest {
    _endpoint = undefined;
    _successCallback = (result) => {
        console.log(result);
    };
    _errorCallback = (error) => {
        console.error(error);
    };

    constructor(endpoint, successCallback, errorCallback) {
        this._endpoint = endpoint;
        if (successCallback !== undefined) {
            this._successCallback = successCallback;
        }
        if (errorCallback !== undefined) {
            this._errorCallback = errorCallback;
        }
    }

    execute(data) {
        if (this._endpoint === undefined) {
            return;
        }

        fetch(this._getRequest(this._endpoint.method(), data)).then(res => res.json())
            .then(
                this._successCallback,
                this._errorCallback
            );
    }

    _prepareGetRequest() {
        return new Request(this._endpoint.url());
    }

    _preparePostRequest(data) {
        return new Request(
            this._endpoint.url(),
            {
                method: this._endpoint.method(),
                body: JSON.stringify(
                    data !== undefined ? data : {}
                )
            }
        );
    }

    _getRequest(method, data) {
        if (method === 'GET') {
            return this._prepareGetRequest();
        } else if (method === 'POST') {
            return this._preparePostRequest(data);
        }
        return new Request(undefined);
    }
}