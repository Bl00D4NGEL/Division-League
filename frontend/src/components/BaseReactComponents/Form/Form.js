import React from "react";

export default function CustomForm({onSubmit, formFields}) {
    return <form onSubmit={onSubmit}>
        {formFields}
    </form>
}
