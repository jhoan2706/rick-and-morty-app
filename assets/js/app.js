/**
 * Rick and Morty Explorer - Client-side Enhancements
 * 
 * This script handles:
 * - Smooth scrolling
 * - Image lazy loading with Intersection Observer
 * - Keyboard navigation enhancements
 * - Filter form auto-submit (optional)
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // Initialize Intersection Observer for lazy loading images
    initializeLazyLoading();
    
    // Add keyboard shortcut for search focus
    initializeKeyboardShortcuts();
    
    // Smooth scroll to top when pagination links are clicked
    enhancePaginationLinks();
    
    /**
     * Lazy loading using Intersection Observer API
     * This provides a more performant experience for large character lists
     */
    function initializeLazyLoading() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        if ('loading' in HTMLImageElement.prototype) {
            // Browser supports native lazy loading, no need for JS implementation
            return;
        }
        
        // Fallback for browsers that don't support native lazy loading
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    
                    // Add fade-in animation
                    img.classList.add('opacity-100');
                    img.style.transition = 'opacity 0.5s ease-in-out';
                    
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        images.forEach(img => {
            if (!img.src || img.src === '') {
                img.style.opacity = '0';
                imageObserver.observe(img);
            }
        });
    }
    
    /**
     * Keyboard shortcut: Press "/" to focus on search input
     */
    function initializeKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Don't trigger if user is typing in an input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                return;
            }
            
            if (e.key === '/') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="name"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    /**
     * Enhance pagination with smooth scrolling
     */
    function enhancePaginationLinks() {
        const paginationLinks = document.querySelectorAll('a[aria-label*="Go to page"], a[aria-label*="page"]');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Store scroll position before navigation
                sessionStorage.setItem('scrollRestoration', 'top');
            });
        });
        
        // Restore scroll position after page load
        if (sessionStorage.getItem('scrollRestoration') === 'top') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            sessionStorage.removeItem('scrollRestoration');
        }
    }
});