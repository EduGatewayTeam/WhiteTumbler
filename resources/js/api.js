import axios from "axios";

const api = axios.create();
api.defaults.withCredentials = true;
api.defaults.headers.common["Content-Type"] = "application/json";

export default api;