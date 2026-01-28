import { TableProps, Table as HeroUITable } from "@heroui/react";

export default function Table({
  children,
  color = 'primary',
  shadow = 'none',
  ...props
}: TableProps) {
    return (
        <HeroUITable
            color={color}
            shadow={shadow}
            {...props}
            classNames={props.classNames ?? {
                th: 'bg-primary-50 py-4'
            }}
        >
            {children}
        </HeroUITable>
    )
}