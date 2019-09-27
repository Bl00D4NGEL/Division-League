import React, {useState} from "react";
import TextInput from "../BaseElements/TextInput";
import Label from "../BaseElements/Label";
import PasswordInput from "../BaseElements/PasswordInput";
import CustomForm from "../BaseElements/Form";
import SubmitInput from "../BaseElements/SubmitInput";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import FakeLoader from "../BaseElements/FakeLoader";
import Loader from "../BaseElements/Loader";

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
                    formField={<TextInput name="user" required onChange={(e) => setUser(e.target.value)}/>}
                />

            </div>
            <div>
                <Label
                    text='Password:'
                    formField={<PasswordInput name="password" required onChange={(e) => setPassword(e.target.value)}/>}
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
                    <SubmitInput value="Login"/>
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