import React from 'react';

export default function Button({text, onClick, ...props}) {
    return <button onClick={onClick} {...props}>{text}</button>
}
