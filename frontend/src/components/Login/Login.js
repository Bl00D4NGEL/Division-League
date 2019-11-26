import React, {useState} from "react";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import PasswordInput from "../BaseReactComponents/PasswordInput/PasswordInput";
import FakeLoader from "../BaseReactComponents/Loader/FakeLoader";
import CustomForm from "../BaseReactComponents/Form/Form";
import Loader from "../BaseReactComponents/Loader/Loader";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";

export default function Login({isLoggedIn, setIsLoggedIn, setUserData}) {
    const [user, setUser] = useState(undefined);
    const [password, setPassword] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);

    const generateFormFields = () => {
        return <div>
            <div>
                <Label
                    text='User:'
                    autofocus
                    formField={<TextInput name="user" required onChangeSetter={setUser}/>}
                />

            </div>
            <div>
                <Label
                    text='Password:'
                    formField={<PasswordInput name="password" required onChangeSetter={setPassword}/>}
                />
            </div>
        </div>
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (areRequiredFieldsSet()) {
            login();
        } else {
            alert("Please enter all required fields");
        }
    };

    const login = () => CustomRequest(
        Config.loginEndpoint(), (result) => {
            setIsLoggedIn(result.data.isLoggedIn);
            setUserData(result.data.user);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        },
        {user, password}
    );

    const areRequiredFieldsSet = () => {
        return (
            user !== undefined
            && password !== undefined
        );
    };

    return <FakeLoader content={
        <CustomForm
            onSubmit={handleSubmit}
            formFields={
                <div>
                    {generateFormFields()}
                    <SubmitButton value="Login"/>
                    <Loader
                        isLoaded={isLoaded}
                        error={error}
                        content={(isLoggedIn ? 'User logged in!' : '')}
                    />
                </div>
            }
        />
    }/>;
}
