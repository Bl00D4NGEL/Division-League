import React from "react";

export default function Loader(props) {
    const {error, isLoaded} = props;
    if (error) {
        return <div>Error: {error.message}</div>;
    } else if (!isLoaded) {
        return (
            <div className="App">
                Loading...
            </div>
        );
    } else {
        props.content = props.content !== undefined ? props.content : null;
        return props.content;
    }
}