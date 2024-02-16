const milliseconds = 3000;

// Remove flash messages after 3 seconds
// ================================================================================================
setTimeout(() => {
    const element = document.querySelector(".flash");

    if (element) document.querySelector(".flash").style.display = "none";
}, milliseconds);