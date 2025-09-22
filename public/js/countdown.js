/**
 * Optimized Countdown Timer System
 * Handles countdown timers for events with minimal server load
 */

(function() {
    'use strict';
    
    let lastApiCall = 0;
    let countdownData = {};
    let isInitialized = false;
    
    // Client-side countdown calculation (days only)
    function calculateClientCountdown(eventDate) {
        const now = new Date().getTime();
        const eventTime = new Date(eventDate).getTime();
        const distance = eventTime - now;
        
        if (distance < 0) {
            return { ended: true };
        }
        
        return {
            days: Math.floor(distance / (1000 * 60 * 60 * 24))
        };
    }
    
    // Update display for specific element (days only)
    function updateDisplay(element, data) {
        if (data.ended) {
            element.innerHTML = '<div class="text-center"><span class="badge bg-success">Event Dimulai!</span></div>';
            return;
        }
        
        const daysEl = element.querySelector('.days');
        
        if (daysEl) daysEl.textContent = data.days.toString().padStart(2, '0');
    }
    
    // Fetch API data for all events (single call)
    function fetchAllCountdownData() {
        const now = Date.now();
        if (now - lastApiCall < 3600000) return; // Rate limit: max 1 call per hour
        
        lastApiCall = now;
        
        // Get all event IDs
        const elements = document.querySelectorAll('.countdown-timer[data-event-id]');
        const eventIds = Array.from(elements).map(el => el.getAttribute('data-event-id'));
        
        if (eventIds.length === 0) return;
        
        // Fetch data for all events
        Promise.all(eventIds.map(eventId => 
            fetch(`/api/countdown/${eventId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => ({ eventId, data }))
                .catch(error => {
                    console.warn(`Event ${eventId} - API Error:`, error.message);
                    return { eventId, data: null };
                })
        )).then(results => {
            results.forEach(({ eventId, data }) => {
                if (data) {
                    if (data.status === 'ended') {
                        countdownData[eventId] = { ended: true };
                    } else {
                        countdownData[eventId] = data.countdown;
                    }
                }
            });
        });
    }
    
    // Update all countdown displays
    function updateAllCountdowns() {
        const elements = document.querySelectorAll('.countdown-timer[data-event-id]');
        
        elements.forEach(element => {
            const eventId = element.getAttribute('data-event-id');
            const eventDate = element.getAttribute('data-date');
            
            if (!eventId || !eventDate) return;
            
            // Use API data if available, otherwise use client calculation
            const data = countdownData[eventId] || calculateClientCountdown(eventDate);
            updateDisplay(element, data);
        });
        
        // Fetch from API every hour
        fetchAllCountdownData();
    }
    
    // Initialize countdown system
    function initCountdown() {
        if (isInitialized) return;
        isInitialized = true;
        
        console.log('Initializing optimized countdown system...');
        
        // Initial load
        updateAllCountdowns();
        
        // Update every hour (minimal server load)
        setInterval(updateAllCountdowns, 3600000);
    }
    
    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdown);
    } else {
        initCountdown();
    }
})();
