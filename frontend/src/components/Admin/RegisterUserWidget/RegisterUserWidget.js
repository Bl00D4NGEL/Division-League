import React, {useState} from 'react';
import CustomForm from "../../BaseElements/Form";
import Label from "../../BaseElements/Label";
import TextInput from "../../BaseElements/TextInput";
import PasswordInput from "../../BaseElements/PasswordInput";
import CustomSelect from "../../BaseElements/Select";
import SubmitInput from "../../BaseElements/SubmitInput";
import RegisterUserService from "../../../services/RegisterUserService";
import Loader from "../../BaseElements/Loader";

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
                <SubmitInput value='Create user'/>
            </div>
        }/>
        <Loader content={response} error={error} isLoaded={isLoaded}/>
    </div>
}

export const CREATE_USER = 'create_user_widget';