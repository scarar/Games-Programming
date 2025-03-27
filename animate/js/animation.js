document.addEventListener('DOMContentLoaded', () => {
    const shape = document.querySelector('.shape');
    const root = document.documentElement;
    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('main-content');
    
    // Color transition function
    function updateColors() {
        const hue1 = Math.random() * 360;
        const hue2 = (hue1 + 180) % 360;
        
        const color1 = `hsl(${hue1}, 80%, 60%)`;
        const color2 = `hsl(${hue2}, 80%, 60%)`;
        
        root.style.setProperty('--primary-color', color1);
        root.style.setProperty('--secondary-color', color2);
    }

    // Update colors periodically
    const colorInterval = setInterval(updateColors, 4000);

    // Mouse interaction
    document.addEventListener('mousemove', (e) => {
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        if (shape) {
            shape.style.transform = `scale(${1 + x * 0.1}) rotate(${y * 360}deg)`;
        }
    });

    // Let the animation play for longer before showing main content
    setTimeout(() => {
        loader.classList.add('hidden');
        mainContent.classList.remove('hidden');
        mainContent.classList.add('visible');
        
        // Keep the color interval running for the header gradient
        // Only clear it if you want to stop the color changes
        // clearInterval(colorInterval);
    }, 5000); // Increased to 5 seconds to show more of the animation
}); 