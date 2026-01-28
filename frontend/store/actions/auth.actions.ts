import { createAsyncThunk } from "@reduxjs/toolkit";
import {ApiResponseProps, ApiErrorProps, LoginData} from "@/types";
import {AxiosError} from "axios";
import http from "@/libs/http";

export const login = createAsyncThunk<ApiResponseProps, LoginData, {rejectValue: ApiErrorProps}>(
    'auth/login',
    async (credentials, { rejectWithValue }) => {
        try {
            const response = await http.post('/login', credentials);
            return response.data;
        } catch (error: unknown) {
            const axiosError = error as AxiosError<ApiResponseProps>;
            console.log(axiosError)
            if (axiosError.response) {
                return rejectWithValue({
                    status: axiosError.response.status,
                    message: axiosError.response.data.message,
                    data: axiosError.response.data
                });
            }
            return rejectWithValue({
                status: 500,
                message: "Something went wrong. Check your connection.",
                data: {
                    message: "Something went wrong. Check your connection.",
                    status: false,
                    data: null
                } as ApiResponseProps
            });
        }
    }
)

export const logout = createAsyncThunk<ApiResponseProps, void, {rejectValue: ApiErrorProps}>(
    'auth/logout',
    async (_, { rejectWithValue }) => {
        try {
            const response = await http.post('/logout');
            return response.data as ApiResponseProps;
        } catch (error: unknown) {
            const axiosError = error as AxiosError<ApiResponseProps>;
            if (axiosError.response) {
                return rejectWithValue({
                    status: axiosError.response.status,
                    message: axiosError.response.data.message,
                    data: axiosError.response.data
                });
            }
            return rejectWithValue({
                status: 500,
                message: "Something went wrong. Check your connection.",
                data: {
                    message: "Something went wrong. Check your connection.",
                    status: false,
                    data: null
                } as ApiResponseProps
            });
        }
    }
)