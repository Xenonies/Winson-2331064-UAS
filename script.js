document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const customerForm = document.getElementById('customerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate name
            const name = document.getElementById('name');
            if (!name.value.trim()) {
                alert('Name is required');
                isValid = false;
                name.focus();
            }
            
            // Validate email
            const email = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailPattern.test(email.value)) {
                alert('Please enter a valid email address');
                isValid = false;
                email.focus();
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Search functionality
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById('searchCity').value;
            window.location.href = `index.php?search=${encodeURIComponent(searchTerm)}`;
        });
    }
    
    // Clear search
    const clearSearch = document.getElementById('clearSearch');
    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            window.location.href = 'index.php';
        });
    }
});
// Delete confirmation functionality
let customerIdToDelete = null;

function confirmDelete(id, name) {
    customerIdToDelete = id;
    document.getElementById('customerName').textContent = name;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
    customerIdToDelete = null;
}

// Event listeners for modal
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (customerIdToDelete) {
        window.location.href = `index.php?delete_id=${customerIdToDelete}`;
    }
});

document.getElementById('cancelDelete').addEventListener('click', closeModal);

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
});
// Validation functions
function validateAge(input) {
    const age = parseInt(input.value);
    const errorElement = document.getElementById('ageError');
    
    if (isNaN(age)) {
        if (input.value !== '') {
            input.classList.add('input-error');
            errorElement.textContent = 'Age must be a number';
            return false;
        }
        // Empty is okay (not required)
        input.classList.remove('input-error');
        input.classList.remove('input-success');
        errorElement.textContent = '';
        return true;
    }
    
    if (age < 1 || age > 120) {
        input.classList.add('input-error');
        errorElement.textContent = 'Age must be between 1 and 120';
        return false;
    }
    
    input.classList.remove('input-error');
    input.classList.add('input-success');
    errorElement.textContent = '';
    return true;
}

function validateCity(input) {
    const city = input.value.trim();
    const errorElement = document.getElementById('cityError');
    const hasNumbers = /\d/.test(city);
    
    if (city === '') {
        // Empty is okay (not required)
        input.classList.remove('input-error');
        input.classList.remove('input-success');
        errorElement.textContent = '';
        return true;
    }
    
    if (hasNumbers) {
        input.classList.add('input-error');
        errorElement.textContent = 'City should not contain numbers';
        return false;
    }
    
    input.classList.remove('input-error');
    input.classList.add('input-success');
    errorElement.textContent = '';
    return true;
}

// Add event listeners for validation
document.addEventListener('DOMContentLoaded', function() {
    const ageInput = document.getElementById('age');
    const cityInput = document.getElementById('city');
    
    if (ageInput) {
        ageInput.addEventListener('input', function() {
            validateAge(this);
        });
        
        ageInput.addEventListener('blur', function() {
            validateAge(this);
        });
    }
    
    if (cityInput) {
        cityInput.addEventListener('input', function() {
            validateCity(this);
        });
        
        cityInput.addEventListener('blur', function() {
            validateCity(this);
        });
    }
    
    // Form validation
    const customerForm = document.getElementById('customerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate name
            const name = document.getElementById('name');
            if (!name.value.trim()) {
                isValid = false;
                name.classList.add('input-error');
                document.getElementById('nameError').textContent = 'Name is required';
            } else {
                name.classList.remove('input-error');
                name.classList.add('input-success');
                document.getElementById('nameError').textContent = '';
            }
            
            // Validate email
            const email = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailPattern.test(email.value)) {
                isValid = false;
                email.classList.add('input-error');
                document.getElementById('emailError').textContent = 'Please enter a valid email address';
            } else {
                email.classList.remove('input-error');
                email.classList.add('input-success');
                document.getElementById('emailError').textContent = '';
            }
            
            // Validate age
            if (!validateAge(ageInput)) {
                isValid = false;
            }
            
            // Validate city
            if (!validateCity(cityInput)) {
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.input-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});