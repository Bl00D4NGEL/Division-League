import React from 'react';

export default function TextInput({onChangeSetter, ...props}) {
    return <input {...props} type="text" onChange={e => onChangeSetter(e.target.value)}/>
}