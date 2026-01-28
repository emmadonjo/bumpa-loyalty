import { useState } from "react";
import { HiOutlineEyeSlash, HiOutlineEye } from 'react-icons/hi2'
import { Input, InputProps } from "@heroui/react";

export default function PasswordInput({
  color = 'primary',
  size = 'lg',
  variant = 'bordered',
  radius="sm",
  labelPlacement = 'outside',
  errorMessage = null,
  ...props
}: InputProps) {
    const [inputType, setInputType] = useState('password');
    const toggleType = () => {
        setInputType((previous) => previous === 'password' ? 'text' : 'password');
    }
    return (
        <div>
            <Input
                type={inputType}
                color={color}
                size={size}
                variant={variant}
                labelPlacement={labelPlacement}
                radius={radius}
                {...props}
                endContent={
                    <button type="button" onClick={toggleType} className="text-default-600 outline-none hover:cursor-pointer" tabIndex={1}>
                        {
                            inputType === 'password'
                                ? <HiOutlineEye />
                                : <HiOutlineEyeSlash />
                        }
                    </button>
                }
                classNames={{label: 'text-sm text-default-700', input: 'placeholder:text-default-400'}}
            />
            {
                typeof errorMessage !== 'function' && errorMessage && (
                    <small className="text-danger block mt-1">{errorMessage}</small>
                )
            }
        </div>
    )
}