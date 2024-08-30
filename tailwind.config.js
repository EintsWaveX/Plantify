/** @type {import('tailwindcss').Config} */

module.exports = {
	mode: "jit",
	content: ["./src/**/*.{html,js}"],
	theme: {
		screens: {
			sm: "340px",
			md: "540px",
			lg: "768px",
			xl: "1180px",
			},
		extend: {},
		keyframes: {
			move: {
			  	"50%": { transform: "translateY(-1rem)" },
			},
			rotate: {
			  	"0%": { transform: "rotate(0deg)" },
			  	"100%": { transform: "rotate(360deg)" },
			},
			scaleUp: {
			  	"0%": { transform: "scale(0.8)" },
			  	"50%": { transform: "scale(1.2)" },
			  	"100%": { transform: "scale(0.8)" },
			},
		},
		animation: {
			movingY: "move 3s ease infinite",
			rotating: "rotate 15s ease infinite",
			scalingUp: "scaleUp 3s ease infinite",
		},
		fontFamily: {
			A: ["Overpass", "sans-serif"],
			B: ["Sevillana", "cursive"]
		},
		container: {
			center: true,
			padding: {
				DEFAULT: "12px",
				md: "32px"
			}
		}
	},
	plugins: [],
}