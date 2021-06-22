//импортируем библиотеку 
import axios from "axios";

// создаем HTTP-клиент
const api = axios.create();

// устанавливаем стандартные настройки
api.defaults.withCredentials = true;
api.defaults.headers.common["Content-Type"] = "application/json";

export default api;