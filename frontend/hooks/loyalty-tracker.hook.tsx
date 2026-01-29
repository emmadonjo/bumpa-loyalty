"use client";

import {useCallback, useEffect, useState} from "react";
import {User} from "@/types";
import http from "@/libs/http";

const useLoyaltyTracker = (userId: number) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [user, setUser] = useState<User>();

    const fetchLoyaltyInfo = useCallback(async () => {
        setIsLoading(true);
        try {
            const response = await http.get(`/users/${userId}`);
            setUser(response.data.data as User);
        }catch (error) {
            console.log(error);
        }finally {
            setIsLoading(false);
        }
    }, [userId]);

    useEffect(() => {
        fetchLoyaltyInfo();
    }, [fetchLoyaltyInfo]);

    return {
        isLoading,
        user
    }
}

export default useLoyaltyTracker;