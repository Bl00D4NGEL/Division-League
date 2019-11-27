import React, {useState} from "react";
import Loader from "./Loader";

export default function FakeLoader({content, timeOut}) {
    const [loaded, setLoaded] = useState(false);
    setTimeout(() => setLoaded(true), timeOut || 200);

    return <Loader
        isLoaded={loaded}
        content={content}
    />
}
