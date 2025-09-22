/**
 * Optimized JavaScript for YourStudio
 * Combines common functionality and optimizes performance
 */

(function() {
    'use strict';
    
    // Global configuration
    const CONFIG = {
        animationDuration: 300,
        scrollThreshold: 100,
        apiTimeout: 5000
    };
    
    // Utility functions
    const Utils = {
        // Debounce function for performance
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Throttle function for scroll events
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        
        // Check if element is in viewport
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };
    
    // Navbar scroll effect
    const NavbarManager = {
        init: function() {
            const navbar = document.querySelector('.navbar-modern');
            if (!navbar) return;
            
            const handleScroll = Utils.throttle(() => {
                if (window.scrollY > CONFIG.scrollThreshold) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }, 100);
            
            window.addEventListener('scroll', handleScroll);
        }
    };
    
    // Lazy loading for images
    const LazyLoader = {
        init: function() {
            const images = document.querySelectorAll('img[data-src]');
            if (!images.length) return;
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        }
    };
    
    // Smooth scrolling for anchor links
    const SmoothScroll = {
        init: function() {
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    const targetId = link.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }
    };
    
    // Form validation and enhancement
    const FormEnhancer = {
        init: function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Add loading state to submit buttons
                form.addEventListener('submit', (e) => {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Loading...';
                    }
                });
                
                // Real-time validation
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                });
            });
        },
        
        validateField: function(field) {
            const value = field.value.trim();
            const type = field.type;
            const required = field.hasAttribute('required');
            
            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            if (required && !value) {
                field.classList.add('is-invalid');
                return false;
            }
            
            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }
            
            if (value) {
                field.classList.add('is-valid');
            }
            
            return true;
        }
    };
    
    // Animation on scroll
    const ScrollAnimations = {
        init: function() {
            const animatedElements = document.querySelectorAll('[data-animate]');
            if (!animatedElements.length) return;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.dataset.animate;
                        element.classList.add('animate__animated', `animate__${animation}`);
                        observer.unobserve(element);
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => observer.observe(el));
        }
    };
    
    // Performance monitoring
    const PerformanceMonitor = {
        init: function() {
            // Log performance metrics in development
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        const perfData = performance.getEntriesByType('navigation')[0];
                        console.log('Page Load Time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
                        console.log('DOM Content Loaded:', perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart, 'ms');
                    }, 0);
                });
            }
        }
    };
    
    // Initialize all modules
    const App = {
        init: function() {
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', this.run.bind(this));
            } else {
                this.run();
            }
        },
        
        run: function() {
            NavbarManager.init();
            LazyLoader.init();
            SmoothScroll.init();
            FormEnhancer.init();
            ScrollAnimations.init();
            PerformanceMonitor.init();
            
            console.log('YourStudio optimized JavaScript loaded successfully');
        }
    };
    
    // Start the application
    App.init();
    
    // Expose utilities globally for other scripts
    window.YourStudioUtils = Utils;
    
})();
