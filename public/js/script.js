// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Set current year in footer
    const currentYearElements = document.querySelectorAll('#current-year');
    currentYearElements.forEach(element => {
      element.textContent = new Date().getFullYear();
    });
    
    // Mobile menu toggle
    const menuButton = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuButton && mobileMenu) {
      menuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('active');
        
        // Toggle icon between menu and x
        const icon = menuButton.querySelector('i');
        if (icon) {
          if (mobileMenu.classList.contains('active')) {
            icon.setAttribute('data-lucide', 'x');
          } else {
            icon.setAttribute('data-lucide', 'menu');
          }
          lucide.createIcons();
        }
      });
    }
    
    // User dropdown toggle
    const userMenuBtn = document.querySelector('.user-menu-btn');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (userMenuBtn && userDropdown) {
      userMenuBtn.addEventListener('click', function() {
        userDropdown.classList.toggle('active');
      });
      
      // Close dropdown when clicking outside
      document.addEventListener('click', function(event) {
        if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
          userDropdown.classList.remove('active');
        }
      });
    }
    
    // Password visibility toggle
    const passwordToggle = document.querySelector('.password-toggle');
    const passwordField = document.querySelector('#password');
    const showPasswordIcon = document.querySelector('.show-password');
    const hidePasswordIcon = document.querySelector('.hide-password');
    
    if (passwordToggle && passwordField && showPasswordIcon && hidePasswordIcon) {
      passwordToggle.addEventListener('click', function() {
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          showPasswordIcon.classList.add('hidden');
          hidePasswordIcon.classList.remove('hidden');
        } else {
          passwordField.type = 'password';
          showPasswordIcon.classList.remove('hidden');
          hidePasswordIcon.classList.add('hidden');
        }
      });
    }
    
    // User type selector
    const userTypeButtons = document.querySelectorAll('.user-type-selector button');
    const usernameLabel = document.querySelector('#username-label');
    const usernameInput = document.querySelector('#username');
    
    if (userTypeButtons.length && usernameLabel && usernameInput) {
      userTypeButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all buttons
          userTypeButtons.forEach(btn => btn.classList.remove('active'));
          
          // Add active class to clicked button
          this.classList.add('active');
          
          // Update label and placeholder based on user type
          const userType = this.getAttribute('data-user-type');
          
          if (userType === 'student') {
            usernameLabel.textContent = 'NIM';
            usernameInput.placeholder = 'Masukkan NIM';
          } else if (userType === 'lecturer') {
            usernameLabel.textContent = 'NIP';
            usernameInput.placeholder = 'Masukkan NIP';
          } else if (userType === 'admin') {
            usernameLabel.textContent = 'Username';
            usernameInput.placeholder = 'Masukkan Username';
          }
        });
      });
    }
    
    // Initialize IPK Chart if on dashboard page
    const ipkChartCanvas = document.getElementById('ipkChart');
    
    if (ipkChartCanvas) {
      const ctx = ipkChartCanvas.getContext('2d');
      
      const ipkChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5'],
          datasets: [{
            label: 'IPK',
            data: [3.52, 3.61, 3.58, 3.66, 3.78],
            borderColor: 'rgb(37, 99, 235)',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            fill: true,
            tension: 0.2,
            borderWidth: 2,
            pointBackgroundColor: 'rgb(37, 99, 235)',
            pointRadius: 4,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              mode: 'index',
              intersect: false,
              backgroundColor: 'rgba(255, 255, 255, 0.9)',
              titleColor: '#0f172a',
              bodyColor: '#64748b',
              borderColor: '#e2e8f0',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
              titleFont: {
                size: 14,
                weight: 'bold'
              },
              bodyFont: {
                size: 14
              },
              callbacks: {
                label: function(context) {
                  return 'IPK: ' + context.parsed.y;
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#64748b'
              }
            },
            y: {
              min: 3.0,
              max: 4.0,
              ticks: {
                stepSize: 0.2,
                color: '#64748b'
              },
              grid: {
                color: '#e2e8f0'
              }
            }
          }
        }
      });
    }
  });
  

  document.addEventListener("DOMContentLoaded", function () {
    const roleButtons = document.querySelectorAll(".role-btn");
    const roleInput = document.getElementById("user-role");
    const usernameLabel = document.getElementById("username-label");
    const usernameInput = document.getElementById("username");

    roleButtons.forEach(button => {
        button.addEventListener("click", function () {
            roleButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            const selectedRole = this.getAttribute("data-role");
            roleInput.value = selectedRole;

            if (selectedRole === "student") {
                usernameLabel.textContent = "NIM";
                usernameInput.placeholder = "Masukkan NIM";
            } else if (selectedRole === "lecturer") {
                usernameLabel.textContent = "NIDN";
                usernameInput.placeholder = "Masukkan NIDN";
            } else if (selectedRole === "admin") {
                usernameLabel.textContent = "Username";
                usernameInput.placeholder = "Masukkan Username Admin";
            }
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
  const userMenuBtn = document.getElementById("userMenuBtn");
  const userDropdown = document.getElementById("userDropdown");

  userMenuBtn.addEventListener("click", function (event) {
      event.stopPropagation(); // Mencegah event dari bubbling ke document
      userDropdown.style.display = userDropdown.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", function () {
      userDropdown.style.display = "none"; // Sembunyikan saat klik di luar
  });

  userDropdown.addEventListener("click", function (event) {
      event.stopPropagation(); // Hindari dropdown tertutup saat diklik
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const mobileProfileToggle = document.getElementById("mobileProfileToggle");
  const mobileProfileMenu = document.getElementById("mobileProfileMenu");

  mobileProfileToggle.addEventListener("click", function () {
      mobileProfileMenu.classList.toggle("open");
  });

  // Menutup dropdown jika klik di luar menu
  document.addEventListener("click", function (event) {
      if (!mobileProfileToggle.contains(event.target) && !mobileProfileMenu.contains(event.target)) {
          mobileProfileMenu.classList.remove("open");
      }
  });
});

