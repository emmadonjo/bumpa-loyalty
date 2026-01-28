import { Pagination as HeroUIPagination, PaginationProps } from "@heroui/react";

interface Props extends PaginationProps {
    className?: string;
}

export default function Pagination({
   page,
   total,
   variant="flat",
   color = "primary",
   showControls = true,
   showShadow = false,
   className = '',
   ...props
}: Props) {
    return (
        <div className="flex items-center justify-between gap-4 flex-wrap px-6 py-3 rounded-md bg-primary-50/50">
            <div className={`inline-flex text-sm text-default-700 ${className}`}>
                show page {page} of {total}
            </div>
            <HeroUIPagination
                showControls={showControls}
                showShadow={showShadow}
                color={color}
                page={page}
                total={total}
                variant={variant}
                {...props}
                classNames={{
                    wrapper: "gap-0 overflow-visible h-8 rounded border border-divider bg-none border-0",
                    item: "w-8 h-8 text-small bg-default-100 mx-1 hover:cursor-pointer text-default",
                    cursor:
                        "bg-gradient-to-b from-primary-200 to-primary-700  text-white font-bold",
                }}
            />
        </div>
    )
}