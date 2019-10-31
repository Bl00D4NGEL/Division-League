import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function RegisterUserService({setIsLoaded, setError, setResponse, user, password, role}) {
    if (isNotAFunctionOrUndefined(setIsLoaded)) {
        setIsLoaded = defaultEmptyFunc;
    }
    if (isNotAFunctionOrUndefined(setError)) {
        setError = defaultEmptyFunc;
    }
    if(isNotAFunctionOrUndefined(setResponse)) {
        setResponse = defaultEmptyFunc;
    }

    setIsLoaded(false);
    CustomRequest(
        Config.registerUserEndPoint(), (res) => {
            setIsLoaded(true);
            setResponse(res.data);
        }, (error) => {
            setError(error);
            setIsLoaded(true);
        },
        {user, password, role}
    );
}

const isNotAFunctionOrUndefined = (varInQuestion) => {
    return typeof varInQuestion !== 'function' || varInQuestion === undefined;
};

const defaultEmptyFunc = () => {};