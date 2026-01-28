"use client";

import {ReactNode, useEffect} from "react";
import {store} from "@/store";
import {hydrateAuth} from "@/store/slices/auth.slices";
import {getStoreItem} from "@/helpers";

type HydratorProps = {
    children: ReactNode;
}
export default function Hydrator({ children }: HydratorProps) {
    useEffect(() => {
        const token = getStoreItem("auth");
        const user = getStoreItem('_user');
        if (token) {
            store.dispatch(hydrateAuth({token: token, user:  user ? JSON.parse(user) : null}))
        } else {
            store.dispatch(hydrateAuth({ token: null, user: null }))
        }
    }, [])

    return <>{children}</>
}