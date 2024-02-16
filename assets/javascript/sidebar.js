// Checks if an element is partially out of view
// ================================================================================================
function isOutofView(element) {
    const rect = element.getBoundingClientRect();
    const isOutofView = rect.top <= 0;
    const sidebar = document.querySelector(".sidebar");

    // Fix the sidebar if it comes out of the page
    if (isOutofView) {
        sidebar.classList.add("fixed-sidebar");
    } else {
        sidebar.classList.remove("fixed-sidebar");
    }
}

// Main method
// ================================================================================================
function main() {
    const nav = document.querySelector(".sidebar");

    if (nav) {
        // Check sidebar positioning when a scroll is performed
        window.addEventListener('scroll', function() {
            isOutofView(nav);
        });

        // Initial check on page load
        document.addEventListener('DOMContentLoaded', function() {
            isOutofView(nav);
        });
    }
}

// Hides/shows the channel name(s) when burger is clicked
// ================================================================================================
document.querySelector('.burger').addEventListener('click', function() {
    const channels = document.querySelectorAll(".channel-name");

    channels.forEach(channel => {
        if (channel.classList.contains("hidden")) {
            channel.classList.remove("hidden");
        } else {
            channel.classList.add("hidden");
        }
    })
});

// Main method call
// ================================================================================================
main();