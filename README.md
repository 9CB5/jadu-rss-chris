# Jadu RSS
An RSS aggregator developed for Jadu.
The project was made using the following technologies:
 - Symfony (v7.0.3)
 - Symfony CLI (5.8.11)
 - PHP (v8.3.2)
 - Composer (v2.6.6)
 - Node (v18.16.0)
 - MySQL (v8.0.36)

# Getting started
1. Ensure that you have the technologies listed in the previous section installed in your machine and ideally in the same version to avoid incompatibility issues.
2. Download and unzip the project zip file, or clone the repo with the following command:
    - `git clone`.
3. Install all project dependencies by running the following commands:
    - ` composer install `
    - ` npm install `
4. Create the database by running the following command:
    - ` symfony console doctrine:database:create `
4. Migrate the database by running the following command:
    - ` symfony console doctrine:migrations:migrate `
5. Seed the database with the command:
    - ` symfony console doctrine:fixtures:load `
6. Run the project locally with:
    - ` symfony serve -d `

- (Optional) To apply Tailwind classes, run the following command: ``` npx tailwindcss -i ./assets/styles/app.css -o ./public/build/app.css --watch  ```
- (Optional) To add/edit js files and apply it, run the following command: ``` npm run dev ```

# File structure
This section provides an overview of the project's file structure, which may assist in understanding the project better.

`documents/` - Where README files are located to assist you in getting started and navigating around the app.
`templates/` - Twig files for the front-end.
`public/` - Compiled js and css files, as well as images used on the app.
`assets/` - Uncompiled js and css files.
`migrations/` - Migration files for the database.
`src/` - PHP files for our backend such as controllers, entities, etc.
`root/` - where the dependencies and config files are located.

# Usage
**Account**
Upon opening the app for the first time, you'll be greeted with a login page. If you seeded the databases correctly, then two users will be created. An admin and a regular user. Below are the following credentials: 

(email : password):
Admin - (admin@fixtures.com : 123456)
User - (user@fixtures.com : 654321)

Feel free to create your own account by clicking the "Register" button on the login page. No email confirmation is required. Please note that accounts created through this method will default to regular user status, not admin. To grant admin privileges, you'll need to manually access the database and insert the `["ROLE_ADMIN"]` value into the ``roles`` column for the desired user.

**Add/Create an RSS link**
To add an RSS link, simply press the "Add link" button which can be found on the top right of the screen. You'll be taken to another page where you'll be able to enter a name and a link. If it proves to be a valid link, it will be saved. Note that to avoid duplication, the system checks for existing links in the database, meaning that if the RSS link already exists, the user will be linked to the existing rss link instead rather than creating a new link.

**Viewing an RSS link**
To view the RSS links, simply glance at the sidebar on the left-hand side of the desktop screen or near the top for mobile. Click on the link, and you'll be directed to another page where you can access the channel's feed.
- Admin: When an admin deletes the channel, it will be fully removed from the database (i.e. delete directly from the table called `rss_links`).
- User: When a user deletes the channel, only the link between the user and the channel will be removed (i.e. delete from the pivot table called `user_link`).

**Editing an RSS link**
There is an "Edit" button next to the channel name near the top of the page. When clicked, you'll be taken to another page where you can enter a new name or link. Note that the system will only accept valid links (i.e. returns an xml).
- Admin: Can edit channel data.
- User: Cannot edit channel data.

**Deleting an RSS link**
There is a "Delete" button next to the channel name near the top of the page. Press the button to delete the page.
- Admin: When an admin deletes the channel, it will be fully removed from the database (i.e. delete directly from the table called `rss_links`).
- User: When a user deletes the channel, only the link between the user and the channel will be removed (i.e. delete from the pivot table called `user_link`).

# Troubleshooting
You may encounter some errors during the setup process and this section will show you the errors I came across and how I fixed them.

**Curl error 60: SSL cert problem: unable to get the local issuer certificate.**
1. Download the `cacert.pem` file at https://curl.se/docs/caextract.html
2. Go to your PHP ini file.
3. Find the line: ` curl.cainfo = `
4. On the right hand side, enter the location of the ` cacert.pem ` file (e.g. `openssl.cafile="C:\cacert.pem"`)
5. Repeat step 4 to line `openssl.cafile=`
6. Save the file.

**Enable some extensions in the PHP ini file**
1. Uncomment the following extensions (i.e. remove the semicolon):
    - extension=curl
    - extension=openssl
    - extension=pdo_mysql
    - extension=mbstring
2. Save the file.
































