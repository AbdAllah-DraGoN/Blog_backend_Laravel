import { useState } from "react";
import "./pages.css";
import {
  handleInputsChange,
  handleSubmitBtnsClick,
} from "../../functions/handleForms";
import axios from "axios";

const Login = () => {
  const MAIN_API_URL = "http://127.0.0.1:8000/api";
  const [formValues, setFormValues] = useState({
    email: "",
    password: "",
  });
  const handleInputChange = (e) => {
    handleInputsChange(e, formValues, setFormValues);
  };
  const handleSubmitBtnClick = () => {
    handleSubmitBtnsClick(formValues);
  };
  const sendData = () => {
    const form = new FormData();

    form.append("email", formValues.email);
    form.append("password", formValues.password);

    axios.post(`${MAIN_API_URL}/logn`, form);
  };

  return (
    <div>
      <div className="container">
        <h1 className="page-header"> login </h1>
        <div className="my-form login-form">
          <label htmlFor="email">email</label>
          <input
            type="email"
            name="email"
            className="email"
            id="email"
            placeholder="email"
            value={formValues.email}
            onChange={handleInputChange}
          />
          <label htmlFor="password">password</label>
          <input
            type="password"
            name="password"
            className="password"
            id="password"
            placeholder="password"
            value={formValues.password}
            onChange={handleInputChange}
          />
          <button className="submit" onClick={handleSubmitBtnClick}>
            Login
          </button>
        </div>
      </div>
    </div>
  );
};

export default Login;
