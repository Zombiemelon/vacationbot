import styled from "styled-components";

const Input = styled.input.attrs(props => ({type: props => props.type}))`
    height: 56px;
    width: 100%;
    padding: 0 1.8rem;
    border-radius: 5px;
    border: 3px solid;
    box-sizing: border-box;
    outline: none;
    font-size: 1rem;
    color: pink;
    type: password;
    &::before {
        content: "Read this: ";
    }
  }
`;

export default Input;