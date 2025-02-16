import { Link } from "react-router-dom";
import "./pages.css";

const NotFound = () => {
  return (
    <div className="error-page">
      <h1>404</h1>
      <h2>Not Found</h2>
      <p>we couldn&apos;t find the page you are looking for.</p>
      <Link to="/"> Back To Home </Link>
    </div>
  );
};

export default NotFound;
