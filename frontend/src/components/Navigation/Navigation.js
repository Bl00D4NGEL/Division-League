import RouteConfig from "../../RouteConfig";
import React, {useState} from "react";
import {NavLink} from "react-router-dom";
import classNames from "classnames";

export default function Navigation({isLoggedIn, user}) {
    const [hideNavigation, setHideNavigation] = useState(true);
    const generateNavigationLinks = () => {
        return RouteConfig.getAll().map((route) => {
            if (
                (route.requiresLogin && !isLoggedIn)
                || route.requiredRole > user.role
            ) {
                return null;
            }
            if (route.path === '/login' && isLoggedIn) {
                return null;
            }
            return <NavLink key={route.path} to={route.path}>{route.name}</NavLink>
        });
    };

    const toggleNavigation = () => {
        setHideNavigation(!hideNavigation);
    };

    const navClasses = classNames({
        'sidenav': true,
        'hidden': hideNavigation
    });

    return <div>
        <div style={{right: 0, position: 'fixed'}}>
            <button onClick={toggleNavigation}>Toggle</button>
        </div>
        <div className={navClasses}>
            <div>
                <img alt="Damage Incorporated" src="/img/dmg-inc-icon-light.png"/>
            </div>
            {generateNavigationLinks()}
        </div>
    </div>
}