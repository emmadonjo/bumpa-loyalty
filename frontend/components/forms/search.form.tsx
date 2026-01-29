"use client";
import { SubmitEvent, useState } from "react";
import { HiMagnifyingGlass, HiXMark } from "react-icons/hi2";

interface SearchProps {
    handleSearch: (value: string) => void;
    placeholder?: string;
    className?: string;
}
export default function SearchForm({
   handleSearch,
   placeholder = 'Search',
   className = ''
}: SearchProps) {
    const [search, setSearch] = useState<string>("");

    const handleSubmit = (e: SubmitEvent): void => {
        e.preventDefault();
        handleSearch(search);
    }

    const handleClearSearch = () => {
        setSearch('');
        handleSearch('');
    }
    return (
        <form onSubmit={handleSubmit} className={`relative border rounded-md group ${className}`}>
            {!!search && <button type="button" className={`absolute left-2 inset-y-2.5 hover:cursor-pointer hover:opacity-40 text-danger-400`}onClick={handleClearSearch}>
                <HiXMark size={18} />
            </button>}
            <input
                type="text"
                placeholder={placeholder}
                value={search}
                onChange={e => setSearch(e.target.value as string)}
                className="bg-inherit py-1.5 px-8 border-0 outline-0 block w-full"
            />
            <button type="submit" className={`absolute right-0 -top-0.5 hover:cursor-pointer hover:opacity-40 bg-primary w-10 h-10 rounded-md text-white flex items-center justify-center`}>
                <HiMagnifyingGlass size={24} />
            </button>
        </form>
    )
}