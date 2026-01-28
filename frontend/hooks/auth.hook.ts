"use client";

import {useDispatch, useSelector} from "react-redux";
import {AppDispatch, RootState} from "@/store";
import {LoginData } from "@/types";
import { login, logout} from "@/store/actions/auth.actions";
import {useRouter} from "next/navigation";

const useAuth = () => {
    const dispatch: AppDispatch = useDispatch();
    const router = useRouter();
    const auth =useSelector((state: RootState) => state.auth);

    const loginUser = async (credentials: LoginData) => {
        const result = await dispatch(login(credentials));
        if (login.fulfilled.match(result)) {
            await router.push("/");
        }
    };

    const logoutUser = async () => {
        dispatch(logout());
    };

    return {
        isLoading: auth.isLoading,
        errors: auth.errors,
        isSuccess: auth.isSuccess,
        isError: auth.isError,
        message: auth.message,
        token: auth.authToken,
        user: auth.user,
        loginUser,
        logoutUser,
        hydrated: auth.hydrated
    }
}

export default useAuth;