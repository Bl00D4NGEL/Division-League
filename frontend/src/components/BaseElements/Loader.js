import React from "react";

export default function Loader({error, isLoaded, content}) {
    if (error) {
        return <div>Error: {error.message}</div>;
    } else if (!isLoaded) {
        return (
            <div>
                <div className="spinner"/>
            </div>
        );
    } else {
        return content !== undefined ? content : <div/>;
    }
}