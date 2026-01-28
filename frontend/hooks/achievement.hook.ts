"use client";

import {useCallback, useEffect, useRef, useState} from "react";
import {Meta, QueryParams, UserAchievement} from "@/types";
import http from "@/libs/http";

const useAchievement = (autoFetch = false) => {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [usersAchievements, setUsersAchievements] = useState<UserAchievement[]>([]);
    const [meta,setMeta] = useState<Meta>({current_page: 1, last_page: 1, per_page: 20, total: 0});
    const params = useRef<QueryParams>({per_page: 20, page: 1});

    const fetchUsersAchievements = useCallback(async (newParams?: QueryParams) => {
        setIsLoading(true);
        try {
            const finalParams = {...params.current, ...newParams};
            const response = await http.get('/admin/users/achievements', { params: finalParams });

            setUsersAchievements(response.data.data);
            setMeta(response.data.meta);
            params.current = finalParams;
        }catch (e){
            console.error(e);
        }finally {
            setIsLoading(false);
        }
    }, [params]);

    useEffect(() => {
        if (autoFetch) {
            fetchUsersAchievements();
        }
    }, [autoFetch, fetchUsersAchievements]);

    return {
        isLoading,
        usersAchievements,
        meta,
        setPage: (page:number) => fetchUsersAchievements({page}),
        setSearch: (search: string) => fetchUsersAchievements({search, page: 1}),
    }
}

export default useAchievement;