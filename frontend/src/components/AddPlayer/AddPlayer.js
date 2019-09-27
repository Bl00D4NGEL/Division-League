import React from 'react';
import FakeLoader from "../BaseElements/FakeLoader";
import AddPlayerForm from "./AddPlayerForm";

export default function AddPlayer() {
    return <FakeLoader
        content={<AddPlayerForm/>}
    />;
}