export default function CustomRequest(endpoint, onSuccess, onError, data) {
    const _successCallback = (result) => {
        console.log(result);
    };
    const _errorCallback = (error) => {
        console.error(error);
    };

    if (onSuccess === undefined) {
        onSuccess = _successCallback;
    }

    if (onError === undefined) {
        onError = _errorCallback;
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
                    onError(res);
                }
                else {
                    onSuccess(res);
                }
            },
            onError
        );
}