import React, {useState} from 'react';
import RegisterUserService from "../../../services/RegisterUserService";
import CustomForm from "../../BaseReactComponents/Form/Form";
import TextInput from "../../BaseReactComponents/TextInput/TextInput";
import Label from "../../BaseReactComponents/Label/Label";
import PasswordInput from "../../BaseReactComponents/PasswordInput/PasswordInput";
import CustomSelect from "../../BaseReactComponents/Select/Select";
import SubmitButton from "../../BaseReactComponents/SubmitButton/SubmitButton";
import Loader from "../../BaseReactComponents/Loader/Loader";
import {useOnChangeSetter} from "../../../customHooks/useOnChangeSetter";

export default function RegisterUserWidget() {
    const [user, setUser] = useOnChangeSetter(undefined);
    const [password, setPassword] = useOnChangeSetter(undefined);
    const [role, setRole] = useOnChangeSetter('MODERATOR');
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
                    <TextInput onChange={setUser}/>
                }/>
                <Label text='Password' formField={
                    <PasswordInput onChange={setPassword}/>
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
                    } onChange={setRole} defaultValue={'MODERATOR'}/>
                }/>
                <SubmitButton value='Create user'/>
            </div>
        }/>
        <Loader content={response} error={error} isLoaded={isLoaded}/>
    </div>
}

export const CREATE_USER = 'create_user_widget';
