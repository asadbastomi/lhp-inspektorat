
// Toggle mobile menu
function toggleMobileMenu() {
    document.body.classList.toggle('mobile-menu-open');
}

// Close mobile menu when clicking outside
function closeMobileMenuOnClickOutside(event) {
    const sidebar = document.querySelector('.sidebar');
    const menuButton = document.querySelector('[onclick*="mobile-menu"]');
    
    if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
        document.body.classList.remove('mobile-menu-open');
    }
}

// Toggle user dropdown
function toggleUserDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
function closeDropdownOnClickOutside(event) {
    const dropdown = document.getElementById('user-dropdown');
    const userButton = document.getElementById('user-menu');
    
    if (!dropdown.contains(event.target) && !userButton.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButtons = document.querySelectorAll('[onclick*="mobile-menu"]');
    mobileMenuButtons.forEach(button => {
        button.addEventListener('click', toggleMobileMenu);
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', closeMobileMenuOnClickOutside);
    
    // User dropdown
    const userButton = document.getElementById('user-menu');
    if (userButton) {
        userButton.addEventListener('click', toggleUserDropdown);
        
        // Close dropdown when clicking outside
        document.addEventListener('click', closeDropdownOnClickOutside);
    }
});

// Close dropdown when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
        document.body.classList.remove('mobile-menu-open');
    }
});