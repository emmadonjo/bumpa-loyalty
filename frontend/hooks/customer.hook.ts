import {useCallback, useEffect, useRef, useState} from "react";
import {Meta, QueryParams, User} from '@/types';
import http from "@/libs/http";


const useCustomer = (autoFetch = false) => {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [customers, setCustomers] = useState<User[]>([]);
    const [meta, setMeta] = useState<Meta>({current_page: 1, last_page: 1, per_page: 20, total: 0});
    const params = useRef<QueryParams>({
        per_page: 20,
        page: 1,
    });

    const getCustomers = useCallback(async (overrideParams?: QueryParams) => {
        setIsLoading(true);

        try {
            const finalPrams = {...params.current, ...overrideParams};
            const response = await http.get('/admin/users', {params: finalPrams});

            setCustomers(response.data.data as User[]);
            setMeta(response.data.meta);
            params.current = finalPrams;
        }catch (error) {
            console.log(error);
        }finally {
            setIsLoading(false);
        }
    }, [params]);

    useEffect(() => {
        if (autoFetch) {
            getCustomers();
        }
    }, [autoFetch, getCustomers]);

    return {
        isLoading,
        customers,
        meta,
        getCustomers,
        setPage: (page: number) => getCustomers({ page }),
        setSearch: (search: string) => getCustomers({search, page: 1}),
    }
}

export default useCustomer;