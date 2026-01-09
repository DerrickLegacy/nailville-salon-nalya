import { animate } from "motion";

document.addEventListener("DOMContentLoaded", () => {
    const path = document.querySelector("#motion-path");
    const box = document.querySelector("#moving-box");

    if (!path || !box) return;

    const DURATION = 4; // seconds

    // Ensure path is ready for drawing animation
    path.style.strokeDasharray = "1";
    path.style.strokeDashoffset = "1";

    // Animate path drawing (like motion.path pathLength)
    animate(
        path,
        { strokeDashoffset: [1, 0] },
        {
            duration: DURATION,
            easing: "ease-in-out",
            repeat: Infinity,
            direction: "alternate"
        }
    );

    // Attach offset path dynamically (CRITICAL for box to follow the path)
    box.style.offsetPath = `path("${path.getAttribute("d")}")`;
    box.style.offsetRotate = "auto";
    box.style.offsetDistance = "0%";

    // Animate box along the path with scaling (like motion.div)
    animate(
        box,
        {
            offsetDistance: ["0%", "100%"],
            scale: [2.5, 1]
        },
        {
            duration: DURATION,
            easing: "ease-in-out",
            repeat: Infinity,
            direction: "alternate"
        }
    );

    // Optional: redirect after 1 animation loop
    setTimeout(() => {
        window.location.href = "/login";
    }, DURATION * 1000);
    
});
