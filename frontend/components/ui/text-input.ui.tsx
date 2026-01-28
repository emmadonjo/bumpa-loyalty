import { InputProps, Input } from "@heroui/react";

export default function TextInput({
  type = 'text',
  color = 'primary',
  size = 'lg',
  variant = 'bordered',
  labelPlacement = 'outside',
  radius = "sm",
  errorMessage = null,
  ...props
}: InputProps) {
    return (
        <div>
            <Input
                type={type}
                color={color}
                size={size}
                variant={variant}
                labelPlacement={labelPlacement}
                radius={radius}
                {...props}
                classNames={{
                    clearButton: 'text-danger',
                    label: 'text-sm text-default',
                    input: 'placeholder:text-default-400 disabled:!cursor-not-allowed'
                }}
            />
            {
                typeof errorMessage !== 'function' && errorMessage && (
                    <small className="text-danger block mt-1">{errorMessage}</small>
                )
            }
        </div>
    )
}