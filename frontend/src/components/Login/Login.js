import React from "react";
import TextInput from "../BaseElements/TextInput";
import Label from "../BaseElements/Label";
import PasswordInput from "../BaseElements/PasswordInput";
import CustomForm from "../BaseElements/Form";
import SubmitInput from "../BaseElements/SubmitInput";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import FakeLoader from "../BaseElements/FakeLoader";

export default class Login extends React.Component {
    constructor(props) {
        super(props);
        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleInputChange(e) {
        const key = JSON.parse(e.target.attributes.getNamedItem('data').value).field;
        const value = e.target.value;
        const change = {
            [key]: value
        };
        this.setState(change);
    }

    handleSubmit(e) {
        e.preventDefault();
        if (this.areRequiredFieldsSet()) {
            this.login();
        } else {
            alert("Please enter all required fields");
        }
    }

    areRequiredFieldsSet() {
        return (
            this.state.user !== undefined &&
            this.state.password !== undefined
        );
    }

    login() {
        this.setState({isLoaded: false});
        const data = {
            user: this.state.user,
            password: this.state.password,
        };
        new CustomRequest(Config.loginEndpoint(), (result) => {
            this.setState({
                isLoaded: true,
                isLoggedIn: result.data.isLoggedIn
            });
            if (this.props.setter.setIsLoggedIn !== undefined) {
                this.props.setter.setIsLoggedIn(result.data.isLoggedIn);
            }
            if (this.props.setter.setUser !== undefined) {
                this.props.setter.setUser(result.data.user);
            }
        }, (error) => {
            this.setState({
                isLoaded: true,
                error
            });
        }).execute(data);
    }

    render() {
        return <FakeLoader content={
            <CustomForm
                onSubmit={this.handleSubmit}
                formFields={
                    <div>
                        {this.generateFormFields()}
                        <SubmitInput value="Login"/>
                    </div>
                }
            />
        }/>;
    }

    generateFormFields() {
        return <div>
            <div>
                <Label
                    text='User:'
                    autofocus
                    formField={<TextInput data={JSON.stringify({field: 'user'})} required onChange={this.handleInputChange}/>}
                />

            </div>
            <div>
                <Label
                    text='Password:'
                    formField={<PasswordInput data={JSON.stringify({field: 'password'})} required
                                              onChange={this.handleInputChange}/>}
                />
            </div>
        </div>
    }
}