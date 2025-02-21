/**
 * Konfigurasi Tailwind CSS untuk animasi dan transisi
 * 
 * Modul ini mengekspor konfigurasi tema Tailwind CSS yang diperluas dengan:
 * 1. Animasi kustom (float, bounce, spin)
 * 2. Keyframes untuk animasi tersebut
 * 3. Durasi transisi tambahan
 * 4. Fungsi timing transisi kustom
 * 
 * @module tailwind.config
 * @type {Object}
 */
module.exports = {
    theme: {
        extend: {
            /**
             * Animasi kustom yang dapat digunakan di aplikasi
             * @property {string} float - Animasi mengambang naik turun
             * @property {string} bounce - Animasi memantul
             * @property {string} spin - Animasi berputar
             */
            animation: {
                float: 'float 3s ease-in-out infinite',
                bounce: 'bounce 1s infinite',
                spin: 'spin 1s linear infinite',
            },
            
            /**
             * Keyframes untuk animasi kustom
             * @property {Object} float - Keyframes untuk animasi float
             * @property {Object} bounce - Keyframes untuk animasi bounce
             * @property {Object} spin - Keyframes untuk animasi spin
             */
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                bounce: {
                    '0%, 100%': { 
                        transform: 'translateY(-25%)',
                        animationTimingFunction: 'cubic-bezier(0.8,0,1,1)'
                    },
                    '50%': {
                        transform: 'none',
                        animationTimingFunction: 'cubic-bezier(0,0,0.2,1)'
                    }
                },
                spin: {
                    from: { transform: 'rotate(0deg)' },
                    to: { transform: 'rotate(360deg)' }
                }
            },
            
            /**
             * Durasi transisi tambahan
             * @property {string} 2000 - Durasi 2000ms
             * @property {string} 3000 - Durasi 3000ms
             */
            transitionDuration: {
                '2000': '2000ms',
                '3000': '3000ms',
            },
            
            /**
             * Fungsi timing transisi kustom
             * @property {string} in-out - Timing function untuk transisi masuk dan keluar
             * @property {string} out - Timing function untuk transisi keluar
             * @property {string} in - Timing function untuk transisi masuk
             */
            transitionTimingFunction: {
                'in-out': 'cubic-bezier(0.4, 0, 0.2, 1)',
                'out': 'cubic-bezier(0, 0, 0.2, 1)',
                'in': 'cubic-bezier(0.4, 0, 1, 1)',
            }
        }
    }
}