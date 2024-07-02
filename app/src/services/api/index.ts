import axios from "axios";
import csrf from "./csrf";

const client = axios.create({
  baseURL: import.meta.env.VITE_BASE_API,
  timeout: 60000,
  withCredentials: true,
  withXSRFToken: true,
  xsrfCookieName: "XSRF-TOKEN",
  xsrfHeaderName: "X-XSRF-TOKEN",
  headers: {
    Accept: "application/json",
  },
});

client.interceptors.request.use(async (request) => {
  if (!request.url?.includes("csrf-cookie")) await csrf.get();

  return request;
});

export default client;
