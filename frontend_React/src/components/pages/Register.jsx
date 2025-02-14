import { useState } from "react";
import "./pages.css";
import {
  handleInputsChange,
  handleSubmitBtnsClick,
} from "../../functions/handleForms";
import axios from "axios";

const Register = () => {
  const MAIN_API_URL = "http://127.0.0.1:8000/api";
  const [formValues, setFormValues] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    image: null,
  });
  const handleInputChange = (e) => {
    handleInputsChange(e, formValues, setFormValues);
  };
  const handleSubmitBtnClick = () => {
    handleSubmitBtnsClick(formValues);
    sendData();
  };

  const sendData = () => {
    const form = new FormData();

    form.append("name", formValues.name);
    form.append("email", formValues.email);
    form.append("password", formValues.password);
    form.append("password_confirmation", formValues.password_confirmation);
    form.append("image", formValues.image);
    // console.log(Array.from(form));

    axios
      .post(`${MAIN_API_URL}/register`, form, {
        headers: {
          Accept: "application/json",
          "Content-Type": "multipart/form-data",
        },
      })
      .then((res) => {
        console.log(res);
      })
      .catch((rej) => {
        console.log(rej);
      });
  };
  return (
    <div>
      <div className="container">
        <h1 className="page-header"> Register </h1>
        <div className="my-form register-form">
          <label htmlFor="name">Name</label>
          <input
            type="text"
            name="name"
            className="name"
            id="name"
            placeholder="name"
            onChange={handleInputChange}
          />
          <label htmlFor="email">email</label>
          <input
            type="email"
            name="email"
            className="email"
            id="email"
            placeholder="email"
            onChange={handleInputChange}
          />
          <label htmlFor="password">password</label>
          <input
            type="password"
            name="password"
            className="password"
            id="password"
            placeholder="password"
            onChange={handleInputChange}
          />
          <label htmlFor="password-confirmation">Password Confirmation</label>
          <input
            type="password"
            name="password-confirmation"
            className="password-confirmation"
            id="password_confirmation"
            placeholder="password-confirmation"
            onChange={handleInputChange}
          />
          <label htmlFor="image">Your Photo</label>
          <input
            type="file"
            accept="image/png, image/jpg, image/jpeg"
            name="image"
            className="image"
            id="image"
            // for handle file input (image input)
            onChange={(e) => {
              setFormValues({
                ...formValues,
                [e.target.id]: e.target.files[0],
              });
            }}
          />
          <button className="submit" onClick={handleSubmitBtnClick}>
            Register
          </button>
        </div>
      </div>
    </div>
  );
};

export default Register;
