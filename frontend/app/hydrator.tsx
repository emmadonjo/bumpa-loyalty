"use client";

import {ReactNode, useEffect} from "react";
import {store} from "@/store";
import {hydrateAuth} from "@/store/slices/auth.slices";
import {getToken} from "@/helpers";

type HydratorProps = {
    children: ReactNode;
}
export default function Hydrator({ children }: HydratorProps) {
    useEffect(() => {
        const data = getToken();
        if (data) {
            store.dispatch(hydrateAuth({token: data, user: null}))
        } else {
            store.dispatch(hydrateAuth({ token: null, user: null }))
        }
    }, [])

    return <>{children}</>
}