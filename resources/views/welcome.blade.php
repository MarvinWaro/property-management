<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CHED Inventory Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* ! tailwindcss v3.4.17 | MIT License | https://tailwindcss.com */*,:before,:after{--tw-border-spacing-x: 0;--tw-border-spacing-y: 0;--tw-translate-x: 0;--tw-translate-y: 0;--tw-rotate: 0;--tw-skew-x: 0;--tw-skew-y: 0;--tw-scale-x: 1;--tw-scale-y: 1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness: proximity;--tw-gradient-from-position: ;--tw-gradient-via-position: ;--tw-gradient-to-position: ;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width: 0px;--tw-ring-offset-color: #fff;--tw-ring-color: rgb(59 130 246 / .5);--tw-ring-offset-shadow: 0 0 #0000;--tw-ring-shadow: 0 0 #0000;--tw-shadow: 0 0 #0000;--tw-shadow-colored: 0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: ;--tw-contain-size: ;--tw-contain-layout: ;--tw-contain-paint: ;--tw-contain-style: }::backdrop{--tw-border-spacing-x: 0;--tw-border-spacing-y: 0;--tw-translate-x: 0;--tw-translate-y: 0;--tw-rotate: 0;--tw-skew-x: 0;--tw-skew-y: 0;--tw-scale-x: 1;--tw-scale-y: 1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness: proximity;--tw-gradient-from-position: ;--tw-gradient-via-position: ;--tw-gradient-to-position: ;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width: 0px;--tw-ring-offset-color: #fff;--tw-ring-color: rgb(59 130 246 / .5);--tw-ring-offset-shadow: 0 0 #0000;--tw-ring-shadow: 0 0 #0000;--tw-shadow: 0 0 #0000;--tw-shadow-colored: 0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: ;--tw-contain-size: ;--tw-contain-layout: ;--tw-contain-paint: ;--tw-contain-style: }*,:before,:after{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}:before,:after{--tw-content: ""}html,:host{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;-o-tab-size:4;tab-size:4;font-family:Figtree,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji",Segoe UI Symbol,"Noto Color Emoji";font-feature-settings:normal;font-variation-settings:normal;-webkit-tap-highlight-color:transparent}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;font-feature-settings:normal;font-variation-settings:normal;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-feature-settings:inherit;font-variation-settings:inherit;font-size:100%;font-weight:inherit;line-height:inherit;letter-spacing:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}button,input:where([type=button]),input:where([type=reset]),input:where([type=submit]){-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dl,dd,h1,h2,h3,h4,h5,h6,hr,figure,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}ol,ul,menu{list-style:none;margin:0;padding:0}dialog{padding:0}textarea{resize:vertical}input::-moz-placeholder,textarea::-moz-placeholder{opacity:1;color:#9ca3af}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}button,[role=button]{cursor:pointer}:disabled{cursor:default}img,svg,video,canvas,audio,iframe,embed,object{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]:where(:not([hidden=until-found])){display:none}

                /* Custom animations */
                @keyframes float {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-20px) rotate(5deg); }
                }

                @keyframes floatReverse {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-15px) rotate(-3deg); }
                }

                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.8; }
                }

                @keyframes slideIn {
                    from { transform: translateX(-100px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }

                .float-animation { animation: float 6s ease-in-out infinite; }
                .float-reverse { animation: floatReverse 8s ease-in-out infinite; }
                .pulse-animation { animation: pulse 4s ease-in-out infinite; }
                .slide-in { animation: slideIn 1s ease-out; }

                /* Additional Tailwind classes */
                .absolute{position:absolute}.relative{position:relative}.inset-0{inset:0}.-top-10{top:-2.5rem}.-right-10{right:-2.5rem}.-bottom-10{bottom:-2.5rem}.-left-10{left:-2.5rem}.top-0{top:0}.right-0{right:0}.bottom-0{bottom:0}.left-0{left:0}.top-10{top:2.5rem}.right-10{right:2.5rem}.bottom-10{bottom:2.5rem}.left-10{left:2.5rem}.z-0{z-index:0}.z-10{z-index:10}.z-20{z-index:20}.z-30{z-index:30}.mx-auto{margin-left:auto;margin-right:auto}.my-8{margin-top:2rem;margin-bottom:2rem}.my-12{margin-top:3rem;margin-bottom:3rem}.mt-8{margin-top:2rem}.mb-8{margin-bottom:2rem}.mt-12{margin-top:3rem}.mb-12{margin-bottom:3rem}.mt-16{margin-top:4rem}.mb-16{margin-bottom:4rem}.flex{display:flex}.inline-flex{display:inline-flex}.grid{display:grid}.hidden{display:none}.h-6{height:1.5rem}.h-8{height:2rem}.h-12{height:3rem}.h-16{height:4rem}.h-20{height:5rem}.h-24{height:6rem}.h-32{height:8rem}.h-64{height:16rem}.h-full{height:100%}.h-screen{height:100vh}.min-h-screen{min-h-screen}.w-6{width:1.5rem}.w-8{width:2rem}.w-12{width:3rem}.w-16{width:4rem}.w-20{width:5rem}.w-24{width:6rem}.w-32{width:8rem}.w-64{width:16rem}.w-full{width:100%}.max-w-md{max-width:28rem}.max-w-lg{max-width:32rem}.max-w-xl{max-width:36rem}.max-w-2xl{max-width:42rem}.max-w-4xl{max-width:56rem}.max-w-6xl{max-width:72rem}.max-w-7xl{max-width:80rem}.transform{transform:translate(var(--tw-translate-x),var(--tw-translate-y)) rotate(var(--tw-rotate)) skew(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}.flex-col{flex-direction:column}.flex-wrap{flex-wrap:wrap}.items-start{align-items:flex-start}.items-center{align-items:center}.items-end{align-items:flex-end}.justify-start{justify-content:flex-start}.justify-end{justify-content:flex-end}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-4{gap:1rem}.gap-6{gap:1.5rem}.gap-8{gap:2rem}.gap-12{gap:3rem}.overflow-hidden{overflow:hidden}.rounded{border-radius:0.25rem}.rounded-lg{border-radius:0.5rem}.rounded-xl{border-radius:0.75rem}.rounded-2xl{border-radius:1rem}.rounded-full{border-radius:9999px}.border{border-width:1px}.border-2{border-width:2px}.border-white{--tw-border-opacity:1;border-color:rgb(255 255 255 / var(--tw-border-opacity))}.border-gray-200{--tw-border-opacity:1;border-color:rgb(229 231 235 / var(--tw-border-opacity))}.border-blue-200{--tw-border-opacity:1;border-color:rgb(191 219 254 / var(--tw-border-opacity))}.bg-white{--tw-bg-opacity:1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-gray-50{--tw-bg-opacity:1;background-color:rgb(249 250 251 / var(--tw-bg-opacity))}.bg-gray-100{--tw-bg-opacity:1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.bg-blue-50{--tw-bg-opacity:1;background-color:rgb(239 246 255 / var(--tw-bg-opacity))}.bg-blue-500{--tw-bg-opacity:1;background-color:rgb(59 130 246 / var(--tw-bg-opacity))}.bg-blue-600{--tw-bg-opacity:1;background-color:rgb(37 99 235 / var(--tw-bg-opacity))}.bg-purple-500{--tw-bg-opacity:1;background-color:rgb(168 85 247 / var(--tw-bg-opacity))}.bg-purple-600{--tw-bg-opacity:1;background-color:rgb(147 51 234 / var(--tw-bg-opacity))}.bg-gradient-to-r{background-image:linear-gradient(to right,var(--tw-gradient-stops))}.bg-gradient-to-br{background-image:linear-gradient(to bottom right,var(--tw-gradient-stops))}.from-blue-500{--tw-gradient-from:#3b82f6 var(--tw-gradient-from-position);--tw-gradient-to:rgb(59 130 246 / 0) var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from), var(--tw-gradient-to)}.from-purple-500{--tw-gradient-from:#a855f7 var(--tw-gradient-from-position);--tw-gradient-to:rgb(168 85 247 / 0) var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from), var(--tw-gradient-to)}.to-purple-600{--tw-gradient-to:#9333ea var(--tw-gradient-to-position)}.to-blue-600{--tw-gradient-to:#2563eb var(--tw-gradient-to-position)}.p-4{padding:1rem}.p-6{padding:1.5rem}.p-8{padding:2rem}.px-4{padding-left:1rem;padding-right:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.px-8{padding-left:2rem;padding-right:2rem}.py-2{padding-top:0.5rem;padding-bottom:0.5rem}.py-3{padding-top:0.75rem;padding-bottom:0.75rem}.py-4{padding-top:1rem;padding-bottom:1rem}.py-12{padding-top:3rem;padding-bottom:3rem}.py-16{padding-top:4rem;padding-bottom:4rem}.py-20{padding-top:5rem;padding-bottom:5rem}.pt-20{padding-top:5rem}.text-center{text-align:center}.text-left{text-align:left}.font-sans{font-family:Figtree,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji",Segoe UI Symbol,"Noto Color Emoji"}.text-sm{font-size:0.875rem;line-height:1.25rem}.text-base{font-size:1rem;line-height:1.5rem}.text-lg{font-size:1.125rem;line-height:1.75rem}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-2xl{font-size:1.5rem;line-height:2rem}.text-3xl{font-size:1.875rem;line-height:2.25rem}.text-4xl{font-size:2.25rem;line-height:2.5rem}.text-5xl{font-size:3rem;line-height:1}.text-6xl{font-size:3.75rem;line-height:1}.font-medium{font-weight:500}.font-semibold{font-weight:600}.font-bold{font-weight:700}.font-extrabold{font-weight:800}.leading-tight{line-height:1.25}.leading-relaxed{line-height:1.625}.text-gray-600{--tw-text-opacity:1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-700{--tw-text-opacity:1;color:rgb(55 65 81 / var(--tw-text-opacity))}.text-gray-800{--tw-text-opacity:1;color:rgb(31 41 55 / var(--tw-text-opacity))}.text-white{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.text-blue-600{--tw-text-opacity:1;color:rgb(37 99 235 / var(--tw-text-opacity))}.text-purple-600{--tw-text-opacity:1;color:rgb(147 51 234 / var(--tw-text-opacity))}.shadow{--tw-shadow:0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);--tw-shadow-colored:0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.shadow-lg{--tw-shadow:0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);--tw-shadow-colored:0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.shadow-xl{--tw-shadow:0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);--tw-shadow-colored:0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.hover\:bg-blue-700:hover{--tw-bg-opacity:1;background-color:rgb(29 78 216 / var(--tw-bg-opacity))}.hover\:bg-purple-700:hover{--tw-bg-opacity:1;background-color:rgb(126 34 206 / var(--tw-bg-opacity))}.hover\:shadow-xl:hover{--tw-shadow:0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);--tw-shadow-colored:0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.transition{transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.duration-300{transition-duration:300ms}.ease-in-out{transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1)}

                /* Responsive design */
                @media (min-width: 640px) {
                    .sm\:text-2xl{font-size:1.5rem;line-height:2rem}
                    .sm\:text-3xl{font-size:1.875rem;line-height:2.25rem}
                    .sm\:text-5xl{font-size:3rem;line-height:1}
                    .sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}
                }

                @media (min-width: 768px) {
                    .md\:text-6xl{font-size:3.75rem;line-height:1}
                    .md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}
                    .md\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}
                    .md\:px-8{padding-left:2rem;padding-right:2rem}
                }

                @media (min-width: 1024px) {
                    .lg\:text-left{text-align:left}
                    .lg\:px-8{padding-left:2rem;padding-right:2rem}
                }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg relative z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-blue-600">CHED Inventory</h1>
                        </div>
                    </div>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition duration-300">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white overflow-hidden">
            <!-- Floating Animations -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Inventory Box 1 -->
                <div class="absolute top-10 left-10 float-animation">
                    <svg class="w-16 h-16 text-white opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" fill="none"/>
                    </svg>
                </div>

                <!-- Inventory Box 2 -->
                <div class="absolute top-32 right-20 float-reverse">
                    <svg class="w-20 h-20 text-white opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16v2H4zm0 5h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6zm2-7a2 2 0 012-2h8a2 2 0 012 2v1H6V4z"/>
                    </svg>
                </div>

                <!-- Document Icon -->
                <div class="absolute bottom-20 left-32 pulse-animation">
                    <svg class="w-12 h-12 text-white opacity-30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                </div>

                <!-- Chart Icon -->
                <div class="absolute top-1/2 right-10 float-animation">
                    <svg class="w-14 h-14 text-white opacity-25" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 2v16c0 1.1.9 2 2 2h16v-2H5c-.55 0-1-.45-1-1V2H2zm4 12h2v4h-2v-4zm4-4h2v8h-2v-8zm4-4h2v12h-2V6z"/>
                    </svg>
                </div>

                <!-- Gear Icon -->
                <div class="absolute bottom-32 right-32 float-reverse">
                    <svg class="w-10 h-10 text-white opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5a3.5,3.5 0 0,1 3.5,3.5 3.5,3.5 0 0,1 -3.5,3.5M19.43,12.98C19.47,12.66 19.5,12.34 19.5,12C19.5,11.66 19.47,11.34 19.43,11.02L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11.02C4.53,11.34 4.5,11.66 4.5,12C4.5,12.34 4.53,12.66 4.57,12.98L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.98Z"/>
                    </svg>
                </div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="slide-in">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                            CHED Inventory Management System
                        </h1>
                        <p class="text-xl leading-relaxed mb-8 opacity-90">
                            Streamline your educational institution's inventory management with our comprehensive digital solution designed specifically for Commission on Higher Education requirements.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition duration-300 text-center">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition duration-300 text-center">
                                    Get Started
                                </a>
                                <a href="#features"
                                   class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition duration-300 text-center">
                                    Learn More
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="relative">
                        <!-- Main Illustration -->
                        <div class="relative z-10 bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8 float-animation">
                            <svg class="w-full h-64" viewBox="0 0 400 300" fill="none">
                                <!-- Dashboard mockup -->
                                <rect x="20" y="20" width="360" height="260" rx="20" fill="white" opacity="0.9"/>
                                <rect x="40" y="40" width="120" height="80" rx="8" fill="#3B82F6" opacity="0.8"/>
                                <rect x="180" y="40" width="120" height="80" rx="8" fill="#8B5CF6" opacity="0.8"/>
                                <rect x="320" y="40" width="40" height="80" rx="8" fill="#10B981" opacity="0.8"/>

                                <!-- Charts -->
                                <circle cx="80" cy="180" r="30" fill="#3B82F6" opacity="0.6"/>
                                <circle cx="80" cy="180" r="20" fill="none" stroke="#8B5CF6" stroke-width="3"/>

                                <!-- Bars -->
                                <rect x="180" y="160" width="15" height="40" fill="#3B82F6" opacity="0.7"/>
                                <rect x="200" y="140" width="15" height="60" fill="#8B5CF6" opacity="0.7"/>
                                <rect x="220" y="170" width="15" height="30" fill="#10B981" opacity="0.7"/>
                                <rect x="240" y="150" width="15" height="50" fill="#F59E0B" opacity="0.7"/>

                                <!-- Items -->
                                <rect x="290" y="160" width="50" height="8" rx="4" fill="#6B7280" opacity="0.5"/>
                                <rect x="290" y="175" width="40" height="8" rx="4" fill="#6B7280" opacity="0.5"/>
                                <rect x="290" y="190" width="60" height="8" rx="4" fill="#6B7280" opacity="0.5"/>
                            </svg>
                        </div>

                        <!-- Floating elements around illustration -->
                        <div class="absolute -top-5 -right-5 pulse-animation">
                            <div class="bg-yellow-400 rounded-full p-3">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="absolute -bottom-5 -left-5 float-reverse">
                            <div class="bg-green-400 rounded-full p-3">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
                        Comprehensive Inventory Features
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Our system provides all the tools you need to efficiently manage your educational institution's inventory and assets.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition duration-300">
                        <div class="bg-blue-500 rounded-full w-16 h-16 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4zm0 5h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6zm2-7a2 2 0 012-2h8a2 2 0 012 2v1H6V4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Asset Tracking</h3>
                        <p class="text-gray-600">
                            Track all institutional assets with detailed information, locations, and status updates in real-time.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition duration-300">
                        <div class="bg-purple-500 rounded-full w-16 h-16 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3v18h18v-2H5V3H3zm14 12h2V9h-2v6zm-4 2h2V7h-2v10zm-4 0h2v-4H9v4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Analytics & Reports</h3>
                        <p class="text-gray-600">
                            Generate comprehensive reports and analytics to make informed decisions about your inventory.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition duration-300">
                        <div class="bg-green-500 rounded-full w-16 h-16 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">CHED Compliance</h3>
                        <p class="text-gray-600">
                            Ensure full compliance with CHED requirements and regulations for educational institutions.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6">
                            Built for Educational Excellence
                        </h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Our inventory management system is specifically designed to meet the unique needs of educational institutions under CHED supervision. With years of experience in the education sector, we understand the challenges you face.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="bg-blue-500 rounded-full p-1 mr-3 mt-1">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-700">Real-time inventory tracking and management</p>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-blue-500 rounded-full p-1 mr-3 mt-1">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-700">CHED-compliant reporting and documentation</p>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-blue-500 rounded-full p-1 mr-3 mt-1">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-700">User-friendly interface for all staff levels</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <!-- Illustration -->
                        <div class="bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl p-8 float-animation">
                            <svg class="w-full h-64" viewBox="0 0 400 300" fill="none">
                                <!-- University building -->
                                <rect x="50" y="150" width="300" height="120" fill="white" opacity="0.9"/>
                                <rect x="70" y="170" width="30" height="40" fill="#3B82F6"/>
                                <rect x="110" y="170" width="30" height="40" fill="#8B5CF6"/>
                                <rect x="150" y="170" width="30" height="40" fill="#10B981"/>
                                <rect x="190" y="170" width="30" height="40" fill="#F59E0B"/>
                                <rect x="230" y="170" width="30" height="40" fill="#EF4444"/>
                                <rect x="270" y="170" width="30" height="40" fill="#6366F1"/>
                                <rect x="310" y="170" width="30" height="40" fill="#8B5CF6"/>

                                <!-- Roof -->
                                <polygon points="30,150 200,80 370,150" fill="#374151" opacity="0.8"/>

                                <!-- Door -->
                                <rect x="180" y="220" width="40" height="50" fill="#6B7280"/>

                                <!-- Flag -->
                                <rect x="190" y="50" width="20" height="30" fill="#EF4444"/>
                                <line x1="200" y1="50" x2="200" y2="150" stroke="#374151" stroke-width="2"/>
                            </svg>
                        </div>

                        <!-- Floating icons -->
                        <div class="absolute -top-3 right-5 pulse-animation">
                            <div class="bg-yellow-400 rounded-full p-2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                    Ready to Transform Your Inventory Management?
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    Join hundreds of educational institutions already using our system to streamline their operations.
                </p>

                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition duration-300 inline-block">
                        Access Your Dashboard
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}"
                           class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition duration-300">
                            Start Free Trial
                        </a>
                        <a href="{{ route('login') }}"
                           class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                            Sign In
                        </a>
                    </div>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-xl font-semibold mb-4">CHED Inventory Management System</h3>
                    <p class="text-gray-400 mb-4">
                        Empowering educational institutions with efficient inventory management solutions.
                    </p>
                    <p class="text-sm text-gray-500">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
