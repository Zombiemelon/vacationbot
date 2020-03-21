import React from 'react';
import { UserProvider, useUserValue, UserContext} from './Context/UserContext';
import { ThemeProvider, useThemeValue, ThemeContext} from './Context/ThContext';
import SignIn from "./Containers/Signin";
import SignUp from "./Containers/Signup";
import MainPage from "./Containers/MainPage";
import './index.css';
import { Route, Switch, BrowserRouter, Redirect} from "react-router-dom";
import ProtectedRoute from "./Components/Authentication/ProtectedRoute";

export default function App () {
    const initialState = {
        user: {
            email: JSON.parse(window.localStorage.getItem('email')),
            roles: JSON.parse(window.localStorage.getItem('roles')),
            id: JSON.parse(window.localStorage.getItem('id')),
            token: JSON.parse(window.localStorage.getItem('api_token')),
        }
    };

    const theme = {
        palette: {
            primary: {
                light: '#b2fef7',
                main: '#80cbc4',
                dark: '#4f9a94',
                contrastText: 'white',
            },
            secondary: {
                light: '#ffa270',
                main: '#ff7043',
                dark: '#c63f17',
                contrastText: '#000',
            },
            basic: {
                main: '#e5e5e5'
            }
        },
    };

    const reducer = (state, action) => {
        switch (action.type) {
            case 'changeUser':
                return( {
                    ...state,
                    user: {
                        email: action.email,
                        roles: action.roles,
                        id: action.id,
                        token: action.token
                    }
                });
            default:
                return state;
        }
    };

    return (
        <BrowserRouter>
            <UserProvider initialState={initialState} reducer={reducer}>
                <ThemeProvider initialState={theme}>
                    <Switch>
                        <ProtectedRoute exact
                                        path="/"
                                        component={MainPage}
                                        allowedRoles={['USER']}/>
                        <Route exact path="/" component={SignIn}/>
                        <Route exact path="/signin" component={SignIn}/>
                        <Route exact path="/signup" component={SignUp}/>
                    </Switch>
                </ThemeProvider>
            </UserProvider>
        </BrowserRouter>
    );
}