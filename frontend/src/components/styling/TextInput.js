import React from 'react';

export default function TextInput(props) {
    return <input {...props} type="text" onChange={props.onChange}/>
}