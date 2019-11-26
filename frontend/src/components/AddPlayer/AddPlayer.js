import React from 'react';
import AddPlayerForm from "./AddPlayerForm";
import FakeLoader from "../BaseReactComponents/Loader/FakeLoader";

export default function AddPlayer() {
    return <FakeLoader
        content={<AddPlayerForm/>}
    />;
}
