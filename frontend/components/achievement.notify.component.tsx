'use client'
import {HiTrophy} from "react-icons/hi2";

interface Props {
    title?: string
    description?: string
}

export default function AchievementPop({
     title = 'Achievement Unlocked!',
     description = 'You just earned a new badge 🎉',
 }: Props) {

    return (
        <>
            <div className="fixed inset-0 pointer-events-none z-40">
                {[...Array(40)].map((_, i) => (
                    <span key={i} className={`confetti confetti-${i % 5}`} />
                ))}
            </div>

            <div className="fixed inset-0 z-50 flex items-center justify-center">
                <div className="achievement-core animate-achievement-enter">
                    <div className="achievement-glow animate-glow-pulse" />
                    <div className="achievement-card animate-bounce-shake">

                        <div className="achievement-icon animate-dance">
                            <HiTrophy size={42} />
                        </div>

                        <div className="text-center">
                            <h2 className="text-xl font-bold">{title}</h2>
                            <p className="text-sm opacity-80 pb-10">{description}</p>
                        </div>

                        <div className="sparkles">
                            {[...Array(12)].map((_, i) => (
                                <span key={i} className="sparkle" />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}