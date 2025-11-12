// Script para el Modo Oscuro
(function() {
    'use strict';

    // Función para aplicar el tema
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    // Función para guardar preferencia en localStorage
    function saveThemePreference(isDark) {
        try {
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        } catch (e) {
            console.log('No se pudo guardar la preferencia del tema');
        }
    }

    // Función para cargar preferencia guardada
    function loadThemePreference() {
        try {
            const darkMode = localStorage.getItem('darkMode');
            return darkMode === 'enabled';
        } catch (e) {
            // Si no se puede acceder a localStorage, verificar la hora del día
            const hour = new Date().getHours();
            // Activar modo oscuro entre 7 PM y 7 AM
            return hour >= 19 || hour < 7;
        }
    }

    // Inicializar el tema cuando carga la página
    function initTheme() {
        const isDarkMode = loadThemePreference();
        applyTheme(isDarkMode);
        
        // Actualizar el toggle switch si existe
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.checked = isDarkMode;
        }
    }

    // Manejar el cambio de tema
    function toggleTheme() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        const newMode = !isDarkMode;
        
        applyTheme(newMode);
        saveThemePreference(newMode);
    }

    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            
            // Agregar event listener al toggle
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('change', toggleTheme);
            }
        });
    } else {
        initTheme();
        
        // Agregar event listener al toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('change', toggleTheme);
        }
    }

    // Detectar cambios en las preferencias del sistema (opcional)
    if (window.matchMedia) {
        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        darkModeQuery.addListener(function(e) {
            // Solo aplicar si el usuario no ha establecido una preferencia manual
            try {
                const manualPreference = localStorage.getItem('darkMode');
                if (!manualPreference) {
                    applyTheme(e.matches);
                }
            } catch (err) {
                console.log('No se pudo verificar preferencias del sistema');
            }
        });
    }

})();