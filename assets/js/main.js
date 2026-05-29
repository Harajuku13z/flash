function toggleMenu() {
    var menu = document.getElementById('mobile-menu');
    menu.classList.toggle('open');
}

// Close menu on outside click
document.addEventListener('click', function(e) {
    var menu = document.getElementById('mobile-menu');
    var btn = document.getElementById('hamburger');
    if (menu && !menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('open');
    }
});

// Header scroll shadow
window.addEventListener('scroll', function() {
    var header = document.getElementById('site-header');
    if (header) {
        if (window.scrollY > 10) {
            header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.12)';
        } else {
            header.style.boxShadow = '0 1px 8px rgba(0,0,0,0.06)';
        }
    }
});

// Animate numbers on scroll
function animateNumber(el) {
    var target = parseInt(el.getAttribute('data-target'));
    var duration = 1500;
    var start = 0;
    var step = (target / duration) * 16;
    var timer = setInterval(function() {
        start += step;
        if (start >= target) { start = target; clearInterval(timer); }
        el.textContent = Math.floor(start).toLocaleString('fr-FR');
    }, 16);
}

var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            entry.target.classList.add('animated');
            animateNumber(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('[data-target]').forEach(function(el) {
    observer.observe(el);
});

// Flash success messages (auto-hide after 5s)
var alerts = document.querySelectorAll('.alert-success');
alerts.forEach(function(alert) {
    setTimeout(function() {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s';
        setTimeout(function() { alert.style.display = 'none'; }, 500);
    }, 5000);
});

// Active nav link highlight
(function() {
    var path = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(function(link) {
        var href = link.getAttribute('href');
        if (href === path || (href !== '/' && path.startsWith(href))) {
            link.classList.add('active');
        }
    });
})();

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// File upload label update
document.querySelectorAll('input[type="file"]').forEach(function(input) {
    input.addEventListener('change', function() {
        var label = this.nextElementSibling;
        if (label && this.files.length > 0) {
            label.textContent = '✅ ' + this.files[0].name;
        }
    });
});

// Form submit — disable button to avoid double submit
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.textContent = 'Envoi en cours…';
        }
    });
});
