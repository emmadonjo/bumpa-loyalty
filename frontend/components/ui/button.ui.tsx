import { ButtonProps, Button as HeroUIButton} from "@heroui/react";
import useAuth from "@/hooks/auth.hook";

export default function Button({
   children,
   color = 'primary',
   size = 'lg',
   variant = 'solid',
   ...props
}: ButtonProps) {
    const auth = useAuth();
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