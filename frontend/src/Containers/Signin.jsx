import React, { useState }  from 'react';
import styled from 'styled-components';
import { ThemeProvider, useThemeValue, ThemeContext} from '../Context/ThContext';
import { UserProvider, useUserValue, UserContext} from '../Context/UserContext';
import MainCard from "../Components/Basic/MainCard";
import Avatar from "@material-ui/core/Avatar";
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';
import Input from '../Components/Basic/Input';
import Button from '../Components/Basic/Button';
import { Link } from "react-router-dom";
import axios from '../Components/Axios/Axios';

const StyledLink = styled(Link)`
      &:visited {
        color: inherit
      };
      &:hover {
        background: linear-gradient(90deg, #92FE9D 0%, #00C9FF 100%) !important
      }
`;

export default function SignIn(props) {
    const [{ palette }] = useThemeValue();
    const [{ user }, dispatch] = useUserValue();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const login = () => {
        axios.post('api/login', {
                email,
                password
        }).then(function (response) {
            const apiToken = response.data.api_token ? response.data.api_token : console.log('No');
            localStorage.setItem('api_token', JSON.stringify(apiToken));
            const id = response.data.id ? response.data.id : console.log('No');
            localStorage.setItem('id', JSON.stringify(id));
            const email = response.data.email ? response.data.email : console.log('No');
            localStorage.setItem('email', JSON.stringify(email));
            const roles = response.data.roles ? response.data.roles : console.log('No');
            localStorage.setItem('roles', JSON.stringify(roles));
            dispatch({ type: 'changeUser', email: email, token: apiToken, id: id, roles: roles})
            setTimeout(() => props.history.push("/"), 0.3);
        }).catch(function (error) {

        });
    };

    return (
        <MainCard>
            <div style={{
                display: 'grid',
                justifyContent: 'center',
                alignItems: 'center',
                gridRow: '1 / span 2',
                gridColumn: '4 / span 6'
            }}>
                <Avatar style={{
                    backgroundColor: palette.secondary.main
                }}>
                    <LockOutlinedIcon/>
                </Avatar>
            </div>
            <div style={{
                display: 'grid',
                justifyContent: 'end',
                alignItems: 'start',
                gridRow: '1 / span 2',
                gridColumn: '10 / span 3',
            }}>
                <StyledLink style={{
                    textDecoration: 'none',
                    color: 'grey !important',
                    background: 'linear-gradient(90deg, #00C9FF 0%, #92FE9D 100%)',
                    borderRadius: '5px',
                    padding: '5px',
                }} to="/signup" color="white">
                    Sign Up
                </StyledLink>
            </div>

                <Input style={{
                    borderColor: palette.secondary.main,
                    color: palette.secondary.main,
                    gridRow: '4 / span 2',
                    gridColumn: '2 / span 10'
                }}
                       type='text'
                       placeholder="Email"
                       onBlur={e => {setEmail(e.target.value)}}
                />
                <Input style={{
                    borderColor: palette.secondary.main,
                    color: palette.secondary.main,
                    gridRow: '7 / span 2',
                    gridColumn: '2 / span 10'
                }}
                       type='password'
                       placeholder="Password"
                       onBlur={e => {setPassword(e.target.value)}}
                />
                <Button style={{
                    backgroundColor: palette.secondary.main,
                    color: palette.secondary.contrastText,
                    width: '100%',
                    gridRow: '10 / span 2',
                    gridColumn: '2 / span 10',
                }} hover={`${palette.secondary.dark} !important`}
                    active={`${palette.primary.light} !important`}
                    onClick={login}
                >
                    Sign in
                </Button>
        </MainCard>
    );
}