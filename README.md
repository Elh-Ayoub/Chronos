<p align="center">
        <a href="https://www.php.net/" rel="nofollow">
            <img src="https://camo.githubusercontent.com/50fa7b8622a4da2f72e63ea33c4f5d4852fd8601e00e298285ca38033cf9fe2c/68747470733a2f2f75706c6f61642e77696b696d656469612e6f72672f77696b6970656469612f636f6d6d6f6e732f322f32372f5048502d6c6f676f2e737667" height="70" data-canonical-src="https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg" style="max-width:100%;">
        </a>
        <a href="https://laravel.com/" rel="nofollow">
            <img src="https://camo.githubusercontent.com/20b4a486c03551decc449bbca9e4fe3de15699c928a716f1442b9af721b2ded0/68747470733a2f2f75706c6f61642e77696b696d656469612e6f72672f77696b6970656469612f636f6d6d6f6e732f332f33362f4c6f676f2e6d696e2e737667" height="70" data-canonical-src="https://upload.wikimedia.org/wikipedia/commons/3/36/Logo.min.svg" style="max-width:100%;">
        </a>
        <a href="https://www.mysql.com/" rel="nofollow">
            <img src="https://camo.githubusercontent.com/19ab6bd09ac44d51db909362f5b77c47ab5679fda118a0bb5bfccf72cfc2a0d1/68747470733a2f2f7777772e766563746f726c6f676f2e7a6f6e652f6c6f676f732f6d7973716c2f6d7973716c2d617232312e737667" height="70" data-canonical-src="https://www.vectorlogo.zone/logos/mysql/mysql-ar21.svg" style="max-width:100%;">
        </a>
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Unofficial_JavaScript_logo_2.svg/480px-Unofficial_JavaScript_logo_2.svg.png" height="70"style="max-width:100%;">
</p>

## About Project

This is a web application that helps organize meetings, tasks for the day/month/year, and events.
With :
<ul>
    <li>a possibility to invite friends to an event via email.</li>
    <li>a possibility to share calendar and invite others.</li>
    <li>several users can create/update/delete events on the same calendar (considering rights of each user).</li>
    <li>email notifications (10 minutes befor and at the time of events).</li>
    <li>a simple chat for shared events and tasks.</li>  
</ul>
<h2>Technologies</h2>
<ul>
    <li><b>Backend: </b> PHP (Laravel), MySQL. </li>
    <li><b>Frontend: </b> HTML / CSS, JavaScript (Pure JavaScript, jQuery) </li>
    <li><b>Tools: </b> Git / VSCode IDE </li>
</ul>
<h2>Requirement</h2>
<ul>
    <li><b>PHP 8.0.6</b></li>
    <li><b>MySQL</b></li>
    <li><b>Composer</b></li>
    <li><b>NPM</b></li>
</ul>
<h2>Serve the Application</h2>
<h3>To try the application clone the repository and follow this steps:</h3>
<ol>
    <li><b>run: composer update</b></li>
    <li><b>run: npm install</b></li>
    <li><b>run: npm update</b></li>
    <li><b>create .env file depending on .env.example</b></li>
    <li><b>Generate key with: php artisan key:generate</b></li>
    <li><b>run: npm run dev</b></li>
    <li><b>run after creating database: php artisan migrate</b></li>
    <li><b>set mailer settings in .env file</b></li>
    <li><b>set pusher settings in .env file by creating account in (https://pusher.com/) and past PUSHER_APP_KEY in Public/js/chat.js</b></li>
    <li><b>serve the project with: php artisan serve</b></li>
    <li><b>Run laravel scheduler: php artisan schedule:work</b></li>
</ol>
