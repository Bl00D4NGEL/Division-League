import React, {useState} from 'react';
import RegisterUserService from "../../../services/RegisterUserService";
import CustomForm from "../../BaseReactComponents/Form/Form";
import TextInput from "../../BaseReactComponents/TextInput/TextInput";
import Label from "../../BaseReactComponents/Label/Label";
import PasswordInput from "../../BaseReactComponents/PasswordInput/PasswordInput";
import CustomSelect from "../../BaseReactComponents/Select/Select";
import SubmitButton from "../../BaseReactComponents/SubmitButton/SubmitButton";
import Loader from "../../BaseReactComponents/Loader/Loader";

export default function RegisterUserWidget() {
    const [user, setUser] = useState(undefined);
    const [password, setPassword] = useState(undefined);
    const [role, setRole] = useState('MODERATOR');
    const [response, setResponse] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);

    const createUser = (e) => {
        e.preventDefault();
        RegisterUserService({user, password, role, setResponse, setError, setIsLoaded});
    };

    return <div>
        <CustomForm onSubmit={createUser} formFields={
            <div>
                <Label text='Username' formField={
                    <TextInput onChangeSetter={setUser}/>
                }/>
                <Label text='Password' formField={
                    <PasswordInput onChangeSetter={setPassword}/>
                }/>
                <Label text='Role' formField={
                    <CustomSelect options={
                        ['moderator', 'admin'].map(role => {
                            return {
                                key: role,
                                value: role.toUpperCase(),
                                name: role[0].toUpperCase() + role.slice(1)
                            }
                        })
                    } onChange={e => setRole(e.target.value)} defaultValue={'MODERATOR'}/>
                }/>
                <SubmitButton value='Create user'/>
            </div>
        }/>
        <Loader content={response} error={error} isLoaded={isLoaded}/>
    </div>
}

export const CREATE_USER = 'create_user_widget';
