import React from 'react';

export default function TextInput({onChange, ...props}) {
    return <input type="text" onChange={onChange} {...props}/>
}
