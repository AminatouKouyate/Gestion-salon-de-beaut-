/**
 * Professional Theme Switcher
 * Dark/Light Mode with localStorage persistence
 * 
 * @author Salon Management System
 */

(function() {
    'use strict';

    const THEME_KEY = 'salon-theme';
    const DARK_CLASS = 'dark-theme';
    const PRELOAD_CLASS = 'preload';

    /**
     * Get saved theme or detect system preference
     */
    function getSavedTheme() {
        const saved = localStorage.getItem(THEME_KEY);
        if (saved) return saved;
        
        // Check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Apply theme immediately (before DOM ready to prevent flash)
     */
    function applyThemeImmediately() {
        const theme = getSavedTheme();
        if (theme === 'dark') {
            document.documentElement.classList.add(DARK_CLASS);
            document.body?.classList.add(DARK_CLASS);
        }
    }

    // Apply immediately
    applyThemeImmediately();

    /**
     * Initialize theme system when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Add preload class to prevent transition flash
        document.body.classList.add(PRELOAD_CLASS);
        
        // Apply theme to body
        const theme = getSavedTheme();
        if (theme === 'dark') {
            document.body.classList.add(DARK_CLASS);
        }

        // Remove preload class after a brief delay
        setTimeout(function() {
            document.body.classList.remove(PRELOAD_CLASS);
        }, 100);

        // Initialize header toggle button
        initHeaderToggle();
        
        // Initialize sidebar toggle switch
        initSidebarToggle();

        // Listen for system preference changes
        initSystemPreferenceListener();
    });

    /**
     * Initialize header theme toggle button
     */
    function initHeaderToggle() {
        const toggleBtn = document.getElementById('theme-toggle-btn');
        if (!toggleBtn) return;

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
            
            // Add click animation
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    /**
     * Initialize sidebar toggle switch
     */
    function initSidebarToggle() {
        const toggle = document.getElementById('theme-toggle');
        const labelText = document.getElementById('theme-label-text');
        
        if (!toggle) return;

        // Set initial state
        const isDark = document.body.classList.contains(DARK_CLASS);
        toggle.checked = isDark;
        updateLabel(labelText, isDark);

        // Handle toggle change
        toggle.addEventListener('change', function() {
            if (this.checked) {
                enableDarkMode();
            } else {
                enableLightMode();
            }
            updateLabel(labelText, this.checked);
        });
    }

    /**
     * Toggle between dark and light mode
     */
    function toggleTheme() {
        const isDark = document.body.classList.contains(DARK_CLASS);
        
        if (isDark) {
            enableLightMode();
        } else {
            enableDarkMode();
        }

        // Sync sidebar toggle if exists
        syncSidebarToggle();
    }

    /**
     * Enable dark mode
     */
    function enableDarkMode() {
        document.documentElement.classList.add(DARK_CLASS);
        document.body.classList.add(DARK_CLASS);
        localStorage.setItem(THEME_KEY, 'dark');
        
        // Dispatch event for other components
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: 'dark' } }));
    }

    /**
     * Enable light mode
     */
    function enableLightMode() {
        document.documentElement.classList.remove(DARK_CLASS);
        document.body.classList.remove(DARK_CLASS);
        localStorage.setItem(THEME_KEY, 'light');
        
        // Dispatch event for other components
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: 'light' } }));
    }

    /**
     * Sync sidebar toggle with current theme
     */
    function syncSidebarToggle() {
        const toggle = document.getElementById('theme-toggle');
        const labelText = document.getElementById('theme-label-text');
        
        if (toggle) {
            const isDark = document.body.classList.contains(DARK_CLASS);
            toggle.checked = isDark;
            updateLabel(labelText, isDark);
        }
    }

    /**
     * Update label text
     */
    function updateLabel(labelElement, isDark) {
        if (labelElement) {
            labelElement.textContent = isDark ? 'Mode Clair' : 'Mode Sombre';
        }
    }

    /**
     * Listen for system preference changes
     */
    function initSystemPreferenceListener() {
        if (!window.matchMedia) return;

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only auto-switch if user hasn't manually set a preference
            const saved = localStorage.getItem(THEME_KEY);
            if (!saved) {
                if (e.matches) {
                    enableDarkMode();
                } else {
                    enableLightMode();
                }
                syncSidebarToggle();
            }
        });
    }

    // Expose API globally
    window.ThemeSwitcher = {
        toggle: toggleTheme,
        enableDark: enableDarkMode,
        enableLight: enableLightMode,
        isDark: function() {
            return document.body.classList.contains(DARK_CLASS);
        }
    };

})();
