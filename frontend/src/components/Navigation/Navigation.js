import RouteConfig from "../../RouteConfig";
import React from "react";
import {NavLink} from "react-router-dom";

export default function Navigation({isLoggedIn, user}) {
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
        const isChecked = e.target.checked;
        const sideBarLength = document.getElementsByClassName('sidebar')[0].clientWidth;
        const defaultMarginLeft = 40;
        const el = document.getElementsByClassName('content')[0];
        el.style.marginLeft = defaultMarginLeft + (isChecked ? sideBarLength : 0) + 'px';
        console.log(sideBarLength, el, el.style, el.style.marginLeft);
    };

    return <div>
        <input onChange={toggleContent} type="checkbox" id="sidebar-toggle-input"/>
        <label className="sidebar-toggle" htmlFor="sidebar-toggle-input"/>
        <div className="sidebar">
            <div>
                <img alt="Damage Incorporated" src="%PUBLIC_URL%/img/dmg-inc-icon-light.png"/>
            </div>
            {generateNavigationLinks()}
        </div>
    </div>
}