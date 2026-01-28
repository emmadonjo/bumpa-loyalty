import axios, { AxiosResponse, InternalAxiosRequestConfig } from "axios";
import { clearToken, getToken} from "@/helpers";
import {app} from "@/configs";

const http = axios.create({
    baseURL: app.apiBaseUrl,
});

http.interceptors.request.use(
    async function (config: InternalAxiosRequestConfig) {
        if (typeof window !== 'undefined') {
            const token = getToken();

            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
        }

        config.headers.Accept ??= 'application/json';
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);


http.interceptors.response.use(
    function (response: AxiosResponse): AxiosResponse {
        return response
    },
    function (error) {
        if (error.response && error.response.status === 401) {
            if (typeof window !== 'undefined') {
                clearToken();
            }

            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
)

export default http;