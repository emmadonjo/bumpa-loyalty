import axios, { AxiosResponse, InternalAxiosRequestConfig } from "axios";
import { clearStoreItem, getStoreItem} from "@/helpers";
import {app} from "@/configs";

const http = axios.create({
    baseURL: app.apiBaseUrl,
});

http.interceptors.request.use(
    async function (config: InternalAxiosRequestConfig) {
        if (typeof window !== 'undefined') {
            const token = getStoreItem('auth');

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
                clearStoreItem('auth');
                clearStoreItem('_user');
            }

            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
)

export default http;