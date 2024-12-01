import axios from "axios"
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = "http://192.168.1.204:8000";
/* This line of code is setting the default base URL for all HTTP requests made using the Axios library
to "http://192.168.1.204:8000". This means that when you make a request using Axios without
specifying a full URL, it will automatically prepend "http://192.168.1.204:8000" to the request URL. */


// axios.defaults.baseURL = "http://127.0.0.1:8000";


