import React from 'react';
import './warning.scss';

export default function Warning ({message}) {
    return <div className="flex full-width" style={{margin: 20 + 'px 0'}}>
        <span className="box warning-box vertical-center" style={{marginLeft: 3 + 'px'}}>!</span>
        <span style={{paddingLeft: 10 + 'px'}} className="vertical-center">
            <span>{message}</span>
        </span>
    </div>
}
