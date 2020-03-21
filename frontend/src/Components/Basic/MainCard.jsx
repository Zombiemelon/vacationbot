import styled, {keyframes, css} from 'styled-components';
import React from "react";

export const animation = css`
    animation-name: ${props => props.animation};
    animation-timing-function: ease-in-out;
    animation-duration: 0.7s
`;

export const MainCardDiv = styled.div`
    ${animation};
    top: ${props => props.top ? props.top : ''};
    grid-row: 4 / span 6;
    grid-column: 4 / span 6;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-template-rows: repeat(12, 1fr);
    transition: all 2s ease;
    border-radius: 5px;
    background-color: white;
    padding: 10px;
    @media (max-width: 768px) {
        grid-column: 1 / span 12;
        grid-row: 3 / span 6;
    }
`;

export default MainCardDiv;

