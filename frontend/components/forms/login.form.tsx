"use client";

import {SubmitEvent, useState} from "react";
import TextInput from "@/components/ui/text-input.ui";
import PasswordInput from "@/components/ui/password-input.ui";
import Button from "@/components/ui/button.ui";
import useAuth from "@/hooks/auth.hook";

export default function LoginForm(){
    const [email, setEmail] = useState<string>("");
    const [password, setPassword] = useState<string>("");
    const { errors, isLoading, loginUser} = useAuth();

    const onSubmit = async (e: SubmitEvent<HTMLFormElement>) => {
        e.preventDefault();
        await loginUser({email, password});
    }
    return (
        <form onSubmit={onSubmit} className="mt-10 grid gap-y-6">
            <TextInput
                label="Email Address"
                placeholder="you@email.com"
                isRequired
                maxLength={255}
                type="email"
                value={email}
                isDisabled={isLoading}
                onChange={(e) => setEmail(e.target.value)}
                errorMessage={errors?.email}
                name="email"
            />

            <PasswordInput
                label="Password"
                placeholder="Enter your password"
                isRequired
                maxLength={64}
                value={password}
                isDisabled={isLoading}
                errorMessage={errors?.email}
                name="password"
                onChange={(e) => setPassword(e.target.value)}
            />

            <Button
                type="submit"
                isLoading={isLoading}
                isDisabled={isLoading || !password || !email}
            >
                Log In
            </Button>
        </form>
    )
}