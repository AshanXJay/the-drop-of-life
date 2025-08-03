// manage-camps.js - JavaScript for camp management interface

document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('campForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const nameInput = document.getElementById('name');
    const locationInput = document.getElementById('location');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    endDateInput.min = today;
    
    // Real-time date validation
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        validateDates();
    });
    
    endDateInput.addEventListener('change', function() {
        validateDates();
    });
    
    // Date validation function
    function validateDates() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && startDate > endDate) {
            endDateInput.setCustomValidity('End date must be after start date');
            showFieldError(endDateInput, 'End date must be after start date');
        } else {
            endDateInput.setCustomValidity('');
            clearFieldError(endDateInput);
        }
    }
    
    // Form validation on submit
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        clearAllErrors();
        
        // Validate required fields
        const requiredFields = [nameInput, locationInput, startDateInput, endDateInput];
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate dates
        validateDates();
        if (endDateInput.validationMessage) {
            isValid = false;
        }
        
        // Validate name length
        if (nameInput.value.length > 255) {
            showFieldError(nameInput, 'Camp name must be less than 255 characters');
            isValid = false;
        }
        
        // Validate location length
        if (locationInput.value.length > 255) {
            showFieldError(locationInput, 'Location must be less than 255 characters');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showMessage('Please correct the errors above', 'error');
        }
    });
    
    // Helper functions for field validation
    function showFieldError(field, message) {
        clearFieldError(field);
        field.classList.add('error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearFieldError(field) {
        field.classList.remove('error');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }
    
    function clearAllErrors() {
        const errorElements = document.querySelectorAll('.field-error');
        errorElements.forEach(error => error.remove());
        
        const errorFields = document.querySelectorAll('.error');
        errorFields.forEach(field => field.classList.remove('error'));
    }
    
    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    if (descriptionTextarea) {
        const maxLength = descriptionTextarea.getAttribute('maxlength');
        
        // Create character counter
        const counterDiv = document.createElement('div');
        counterDiv.className = 'char-counter';
        counterDiv.textContent = `0 / ${maxLength}`;
        descriptionTextarea.parentNode.appendChild(counterDiv);
        
        // Update counter on input
        descriptionTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counterDiv.textContent = `${currentLength} / ${maxLength}`;
            
            if (currentLength > maxLength * 0.9) {
                counterDiv.classList.add('warning');
            } else {
                counterDiv.classList.remove('warning');
            }
        });
    }
    
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        const message = document.getElementById('message');
        if (message) {
            message.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => message.remove(), 300);
        }
    }, 5000);
});

// Delete confirmation
function confirmDelete(campName) {
    return confirm(`Are you sure you want to delete "${campName}"?\n\nThis action cannot be undone.`);
}

// Close message manually
function closeMessage() {
    const message = document.getElementById('message');
    if (message) {
        message.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => message.remove(), 300);
    }
}

// Show custom message
function showMessage(text, type) {
    // Remove existing message
    const existingMessage = document.getElementById('message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.id = 'message';
    messageDiv.className = `message ${type}`;
    messageDiv.innerHTML = `
        ${text}
        <span class="close-message" onclick="closeMessage()">&times;</span>
    `;
    
    // Insert at top of container
    const container = document.querySelector('.container');
    const pageHeader = container.querySelector('.page-header');
    container.insertBefore(messageDiv, pageHeader.nextSibling);
}

// Form reset with confirmation
document.addEventListener('DOMContentLoaded', function() {
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                e.preventDefault();
            }
        });
    }
});

// Add slide animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    
    .field-error {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
        animation: slideDown 0.3s ease;
    }
    
    .error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
    }
    
    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }
    
    .char-counter.warning {
        color: #e74c3c;
        font-weight: bold;
    }
`;
document.head.appendChild(style);
