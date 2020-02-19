import React from 'react';

export default function TwoColumnLayout ({left, right}) {
    return <div style={{display: 'flex'}}>
        <div style={{width: 50 + '%'}}>
            {left}
        </div>
        <div style={{width: 50 + '%'}}>
            {right}
        </div>
    </div>
}
