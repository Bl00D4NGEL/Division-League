import {useState} from 'react';
import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";
import UserRoles from "../UserRoles";

export const useAuth = () => {
    const [user, setUser] = useState({role: UserRoles.NORMAL});
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    CustomRequest(
        Config.authEndpoint(),
        result => {
            setIsLoggedIn(result.data.isLoggedIn);
            setUser(result.data.user);
        },
        console.error
    );

    return {user, setUser, isLoggedIn, setIsLoggedIn};
};
