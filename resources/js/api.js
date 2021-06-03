// import library for requests
import axios from "axios";

// create axios instance
const api = axios.create();

// set default settings
api.defaults.withCredentials = true;
api.defaults.headers.common["Content-Type"] = "application/json";

export default api;