Timeline:

Learning Symfony - 6 hours
Design - 1 hour
Implementation - 3 hours
Testing - 1 hour
Write up - 1.5 hours

Report:

It took approximately 12 hours to complete this project. My objective was to develop it using the technologies currently utilized at Jadu in order familiarise myself with them. This meant some learning is required before starting development as I come from a Vue/Laravel background. I would like to take this opportunity to apologize for the delayed submission. The past few weeks have been particularly busy at work, and I have only recently recovered from a bout of severe flu/covid.

Developing with Symfony was an enjoyable experience for me, largely because it offers convenient features similar to Laravel, such as the ORM, authentication, and form types, among others. Exploring a template system like Twig was particularly intriguing, especially since I'm more accustomed to developing with single-page application (SPA) frameworks like React.

In the end, I'm pleased with the app's outcome. Its responsiveness across various devices ensures a user-friendly experience. The CRUD implementation feels robust and well-constructed. Moreover, I've dedicated time to set up conditional statements in both the front and backend to ensure that the system can distinguish between different user types apply the appropriate behaviors accordingly.

Although I'm satisfied with the app, there's still room for further enhancements. One aspect I'm particularly interested in improving is its performance, as I believe it could be significantly faster. This concern arises from the app's heavy reliance on the speed of retrieving the RSS feed; if the RSS retrieval is slow, the app's performance suffers as a consequence. Initially, I attempted to address this by reducing the maximum time the app waits for a response. However, I now believe a more elegant solution would involve storing articles in our database, potentially facilitated by a cron job that periodically checks and parses data. While I considered implementing this approach, I deemed it impractical for a demo project that already takes many steps to setup.