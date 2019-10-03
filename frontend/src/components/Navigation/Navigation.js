import RouteConfig from "../../RouteConfig";
import React from "react";
import {NavLink} from "react-router-dom";

import icon from '../../assets/img/dmg-inc-icon-light.png';

export default function Navigation({isLoggedIn, user}) {
    const defaultMarginLeft = 40;
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

    const toggleContent = (e) => {
        const sideBarLength = document.getElementsByClassName('sidebar')[0].clientWidth;
        document.getElementsByClassName('content')[0].style.marginLeft = defaultMarginLeft + (e.target.checked ? sideBarLength : 0) + 'px';
    };

    return <div>
        <input onChange={toggleContent} type="checkbox" id="sidebar-toggle-input"/>
        <label className="sidebar-toggle" htmlFor="sidebar-toggle-input"/>
        <div className="sidebar">
            <div>
                <img alt="Damage Incorporated" src={icon}/>
            </div>
            {generateNavigationLinks()}
        </div>
    </div>
}