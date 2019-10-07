import React from "react";
import Error from "../Error/Error";

export default function Loader({error, isLoaded, content}) {
    if (error) {
        if (error.message.match(/^Unexpected token/)) {
            console.error(error);
            error.message = 'Invalid server response';
        }
        return <Error message={error.message}/>
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