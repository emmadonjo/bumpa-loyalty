"use client";

import {HeroUIProvider, ToastProvider} from '@heroui/react';
import { Provider } from 'react-redux';
import {ReactNode} from "react";
import {store} from "@/store";

type ProvidersProps = {
    children: ReactNode;
}
export function Providers({children}: ProvidersProps) {
    return (
        <HeroUIProvider>
            <Provider store={store}>
                <ToastProvider placement="top-right" />
                {children}
            </Provider>
        </HeroUIProvider>
    )
}