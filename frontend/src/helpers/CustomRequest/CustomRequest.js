export default function CustomRequest(endpoint, successCallback, errorCallback, data) {
    const _successCallback = (result) => {
        console.log(result);
    };
    const _errorCallback = (error) => {
        console.error(error);
    };

    if (successCallback === undefined) {
        successCallback = _successCallback;
    }

    if (errorCallback === undefined) {
        errorCallback = _errorCallback;
    }

    if (endpoint === undefined) {
        return;
    }

    const prepareGetRequest = () => {
        return new Request(endpoint.url());
    };

    const preparePostRequest = (postData) => {
        return new Request(
            endpoint.url(),
            {
                method: endpoint.method(),
                body: JSON.stringify(
                    postData !== undefined ? postData : {}
                )
            }
        );
    };

    const fetchRequest = (method, requestData) => {
        if (method === 'GET') {
            return prepareGetRequest();
        } else if (method === 'POST') {
            return preparePostRequest(requestData);
        }
        return new Request(undefined);
    };

    fetch(fetchRequest(endpoint.method(), data)).then(res => res.json())
        .then(
            (res) => {
                if (res.status === 'error') {
                    errorCallback(res);
                }
                else {
                    successCallback(res);
                }
            },
            errorCallback
        );
}