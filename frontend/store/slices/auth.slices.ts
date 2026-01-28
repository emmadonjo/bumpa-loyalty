import { login, logout } from "@/store/actions/auth.actions";
import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import {ApiResponseProps, ApiErrorProps, User} from "@/types";
import { addToast } from "@heroui/react";
import {getToken, saveToken} from "@/helpers";

export interface AuthState {
    isLoading: boolean;
    isSuccess: boolean;
    isError: boolean;
    errors: Record<string, string>|null;
    message: string | null;
    authToken: string | null;
    user: User | null;
    hydrated: boolean;
}

const initialState: AuthState = {
    isLoading: false,
    isSuccess: false,
    isError: false,
    errors: null,
    message: null,
    authToken: getToken(),
    user: null,
    hydrated: false,
};

const auth = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        setError: (state: AuthState, action: PayloadAction<{ key: string, value: string }>) => {
            const key = action.payload.key;
            const value = action.payload.value;
            state.errors = state.errors
                ? Object.assign(state.errors, {[key]: value})
                : {[key]: Array.isArray(value) ? value : value};
        },
        clearErrors: (state: AuthState) => {
            state.errors = null;
        },
        clearToken: (state: AuthState) => {
            state.authToken = null;
        },
        hydrateAuth(state, action: PayloadAction<{ token: string|null; user: User|null }>) {
            state.authToken = action.payload.token;
            state.user = action.payload.user;
            state.hydrated = true;
        },
    },
    extraReducers: (builder) => {
        builder
            .addCase(login.pending, (state: AuthState) => {
                state.isLoading = true;
                state.message = null;
                state.errors = null
                state.isSuccess = false;
                state.isError = false;
                state.authToken = null;
                state.user = null;
            })
            .addCase(login.fulfilled, (state: AuthState, action: PayloadAction<ApiResponseProps>) => {
                const data = action.payload.data as { user: User, token: string };
                state.authToken = data.token;
                state.user = data.user;
                saveToken(data.token);
                console.log(action.payload)
                state.isLoading = false;
                state.isSuccess = true;
                state.message = 'Login successful!';
                addToast({
                    description: state.message,
                    color: 'success'
                });
            })
            .addCase(login.rejected, (state: AuthState, action: PayloadAction<ApiErrorProps | undefined>) => {
                let message: string = "Authentication failed.";
                if (action.payload) {
                    if (action.payload.status === 403) {
                        message = action.payload.data?.message as string;
                        state.errors = action.payload.data?.data as Record<string, string>;
                    }
                    else if (action.payload.status === 422) {
                        message = action.payload.data?.message ?? "Wrong email/password combinations.";
                        state.errors = action.payload.data?.data as Record<string, string>;
                    }

                }

                state.isLoading = false;
                state.isError = true;
                state.isSuccess = false;
                state.message = message;

                addToast({
                    description: state.message,
                    color: 'danger'
                });
            })
            .addCase(logout.fulfilled, (state: AuthState) => {
                clearToken();
                state.authToken = null;
                state.user = null;
                state.isLoading = false;
                state.isSuccess = true;
                state.message = 'Logout successful!';

                addToast({
                    description: state.message,
                    color: 'success'
                });
            })
            .addCase(logout.rejected, (state: AuthState, action: PayloadAction<ApiErrorProps | undefined>) => {
                let message: string = "Logout failed.";
                if (action.payload && action.payload.message) {
                    message = action.payload.message as string;
                }
                state.isLoading = false;
                state.isError = true;
                state.isSuccess = false;
                state.message = message;

                addToast({
                    description: state.message,
                    color: 'danger'
                });
            })
    }
});

export const { setError, clearErrors, clearToken, hydrateAuth} = auth.actions;
export default auth.reducer;