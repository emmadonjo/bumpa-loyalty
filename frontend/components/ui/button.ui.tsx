import { ButtonProps, Button as HeroUIButton} from "@heroui/react";

export default function Button({
   children,
   color = 'primary',
   size = 'lg',
   variant = 'solid',
   ...props
}: ButtonProps) {
    return (
        <HeroUIButton
            color={color}
            size={size}
            variant={variant}
            {...props}
            className="disabled:!cursor-not-allowed disabled!bg-default-100 text-white"
        >
            {children}
        </HeroUIButton>
    )
}