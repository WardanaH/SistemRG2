/* ==========================================================
   PAGE FADE-IN + REMOVE OVERLAY
========================================================== */
window.addEventListener("load", () => {
    const root = document.getElementById("body-root");
    if (root) root.classList.remove("opacity-0");

    const overlay = document.getElementById("overlay");
    if (overlay) {
        overlay.style.opacity = "0";
        setTimeout(() => overlay.remove(), 550);
    }

    /* INIT AOS */
    if (window.AOS) {
        AOS.init({
            duration: 850,
            once: true,
            offset: 80,
            easing: "ease-out-cubic",
        });
    }
});

/* ==========================================================
   NAVBAR SHRINK
========================================================== */
window.addEventListener("scroll", () => {
    const navbar = document.getElementById("navbar");
    if (!navbar) return;

    if (window.scrollY > 70) {
        navbar.classList.add("navbar-shrink");
    } else {
        navbar.classList.remove("navbar-shrink");
    }
});

/* ==========================================================
   MOBILE MENU
========================================================== */
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menu-btn");
    const menu = document.getElementById("mobile-menu");

    if (btn && menu) {
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    }
});

/* ==========================================================
   PAGE FADE-OUT ON LINK CLICK (Smooth Navigation)
========================================================== */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("a[href]").forEach((link) => {
        const url = link.getAttribute("href");

        if (!url || url.startsWith("#") || url.startsWith("mailto:")) return;

        link.addEventListener("click", (e) => {
            e.preventDefault();
            const root = document.getElementById("body-root");
            if (root) root.style.opacity = "0";
            setTimeout(() => (window.location = url), 260);
        });
    });
});

/* ==========================================================
   MICRO PARALLAX (Soft & Light)
========================================================== */
window.addEventListener("scroll", () => {
    document.querySelectorAll("[data-micro-parallax]").forEach((el) => {
        const offset = window.scrollY * 0.025;
        el.style.transform = `translateY(${offset}px)`;
    });
});
