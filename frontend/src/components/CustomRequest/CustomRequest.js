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

        const request = this.getRequest(this._endpoint.method(), data);

        if (request !== undefined) {
            fetch(request).then(res => res.json())
                .then(
                    this._successCallback,
                    this._errorCallback
                );
        }
    }

    prepareGetRequest() {
        return new Request(this._endpoint.url());
    }

    preparePostRequest(data) {
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

    getRequest(method, data) {
        if (method === 'GET') {
            return this.prepareGetRequest();
        } else if (method === 'POST') {
            return this.preparePostRequest(data);
        }
    }
}