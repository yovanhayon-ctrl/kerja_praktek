import "./bootstrap";
import "bootstrap";

// ============================================================
// SCROLL ANIMATION (fade-up)
// ============================================================
const fadeEls = document.querySelectorAll(".fade-up");

const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add("visible");
                }, i * 100);
                observer.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1 },
);

fadeEls.forEach((el) => observer.observe(el));

// ============================================================
// NAVBAR — tambah shadow saat scroll
// ============================================================
window.addEventListener("scroll", () => {
    const navbar = document.querySelector(".navbar");
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = "0 4px 30px rgba(0,0,0,0.2)";
        } else {
            navbar.style.boxShadow = "0 2px 20px rgba(0,0,0,0.15)";
        }
    }
});

// ============================================================
// SMOOTH SCROLL untuk anchor link
// ============================================================
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
});
