import React from "react";

export default function CustomForm(props) {
    return <form onSubmit={props.onSubmit}>
        {props.formFields}
    </form>
}