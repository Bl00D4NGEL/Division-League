import React from 'react';

export default function PasswordInput({onChangeSetter, ...props}) {
    return <input {...props} type="password" onChange={e => onChangeSetter(e.target.value)}/>
}