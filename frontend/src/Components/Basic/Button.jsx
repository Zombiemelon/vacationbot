import styled from "styled-components";

const Button = styled.div`
    border-radius: 5px;
    height: 56px;
    display: grid;
    justify-content: center;
    align-content: center;
    box-sizing: border-box;
    font-size: 1rem;
    font-weight: bold;
    padding: 0 1.8rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: .3s ease-in-out;
    &:hover {
        background: ${props => props.hover}
    };
    &:active {
        background: ${props => props.active}
    }
`;

export default Button;