import React from 'react';

export default function PasswordInput(props) {
    return <input {...props} type="password" onChange={props.onChange}/>
}