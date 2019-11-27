import RouteConfig from "../../RouteConfig";
import React from "react";
import {NavLink} from "react-router-dom";

import './navigation.scss';
import icon from '../../assets/img/dmg-inc-icon-light.png';
import UserRoles from "../../UserRoles";

export default function Navigation({isLoggedIn, user}) {
    const generateNavigationLinks = () => {
        return <ul className="menu-active">
            {
                RouteConfig.getAll().map((route) => {
                    if (
                        (route.requiresLogin && !isLoggedIn)
                        || route.requiredRole > UserRoles[user.role]
                    ) {
                        return null;
                    }
                    if (route.path === '/login' && isLoggedIn) {
                        return null;
                    }
                    return <li key={route.path}><NavLink onClick={() => document.getElementsByClassName('toggle-nav')[0].click()} to={route.path}>{route.name}</NavLink></li>
                })
            }
        </ul>
    };

    const toggleNavigation = (e) => {
        e.currentTarget.classList.toggle('menu-active');
        document.querySelector('.menu ul').classList.toggle('menu-active');
        e.preventDefault();
    };

    return <nav className="menu">
        {generateNavigationLinks()}
        <div id="di-logo"><img src={icon} alt="Damage Incorporated"/></div>
        <a onClick={toggleNavigation} className="toggle-nav" href="/">&#9776;</a>
    </nav>
}
