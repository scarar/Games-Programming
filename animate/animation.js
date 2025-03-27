document.addEventListener('DOMContentLoaded', () => {
    const shape = document.querySelector('.shape');
    const root = document.documentElement;
    
    // Color transition function
    function updateColors() {
        const hue1 = Math.random() * 360;
        const hue2 = (hue1 + 180) % 360; // Complementary color
        
        const color1 = `hsl(${hue1}, 80%, 60%)`;
        const color2 = `hsl(${hue2}, 80%, 60%)`;
        
        root.style.setProperty('--primary-color', color1);
        root.style.setProperty('--secondary-color', color2);
    }

    // Update colors periodically
    setInterval(updateColors, 4000);

    // Add mouse interaction
    document.addEventListener('mousemove', (e) => {
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        shape.style.transform = `scale(${1 + x * 0.1}) rotate(${y * 360}deg)`;
    });
}); 