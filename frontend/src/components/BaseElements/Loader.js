import React from "react";

/**
 * @return {null}
 */
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
        return props.content !== undefined ? props.content : null;
    }
}