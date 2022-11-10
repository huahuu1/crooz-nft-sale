<h1 align="center" style="font-size:50px"><strong>Xeno Backend</strong></h1>

You need to use Amazon Cloud and Aurora MYSQL to the database, S3 to upload files, and SES to send email.

## I. Source code clone

### 1. Source code clone from github

> `git clone git@github.com:Kozocom-Lab/kz-crooz-backend.git`

### 2. Change branch depending on usage environment

- Develop environment:
    > `git checkout -b develop`
- Production environment:
    > `git checkout master`

## II. Setting env file

### 1. Create env file

> `cd ./kz-crooz-backend/` </br>
> `cp .env.example .env`

### 2. Update env file

1. Open env file
    > `vi .env`
2. Set app name
    > `APP_NAME=YOUR_APP_NAME`
3. Change app url
    > `APP_URL=YOUR_APP_URL`
4. Set name environment
    - Develop environment
        > `APP_ENV=develop`
    - Production environment
        > `APP_ENV=production` <br/> `APP_DEBUG=false`
5. Change database connection information
    > `DB_HOST=YOUR_DB_HOST`<br/> `DB_PORT=YOUR_DB_PORT`<br/> `DB_DATABASE=YOUR_DB_DATABASE`<br/> `DB_USERNAME=YOUR_DB_USERNAME`<br/> `DB_PASSWORD=YOUR_DB_PASSWORD`<br/>
6. Change mail information
    > `MAIL_DRIVER=YOUR_MAIL_DRIVER`<br/> `MAIL_HOST=YOUR_MAIL_HOST` <br/> `MAIL_PORT=YOUR_MAIL_PORT` <br/> `MAIL_USERNAME=YOUR_MAIL_USERNAME` <br/> `MAIL_PASSWORD=YOUR_MAIL_PASSWORD` <br/> `MAIL_ENCRYPTION=YOUR_MAIL_ENCRYPTION`
7. Change AWS information
    > `AWS_ACCESS_KEY_ID=YOUR_KEY_ID` <br/> `AWS_SECRET_ACCESS_KEY=YOUR_ACCESS_KEY`<br/>`AWS_DEFAULT_REGION=YOUR_DEFAULT_REGION`<br/> `AWS_BUCKET=YOUR_AWS_BUCKET` <br/> `AWS_URL=YOUR_AWS_URL`
8. Change custom variable

    - Email sent confirmation code
        > `MAIL_SEND_TOKEN_VALIDATE=YOUR_EMAIL`
    - Company wallet a receive Token Sale
        > `COMPANY_WALLET= YOUR_COMPANY_WALLET`
    - Company wallet a receive NFT Auction
        > `NFT_COMPANY_WALLET=YOUR_NFT_COMPANY_WALLET`
    - Change etherscan API Key.

        You can get the key by click [here](https://docs.etherscan.io/getting-started/viewing-api-usage-statistics)

        > `ETHERSCAN_API_KEY= YOUR_ETHERSCAN_API_KEY`

    - Change BscScan API Key

        You can get the key by click [here](https://docs.bscscan.com/getting-started/viewing-api-usage-statistics)

        > `BSCSCAN_API_KEY=YOUR_BSCSCAN_API_KEY`

    - Change contract wallet USDT

        You may get the wallet address click [here](https://goerli.etherscan.io/token/0xC2C527C0CACF457746Bd31B2a698Fe89de2b6d49) for the development environment.

        > `CONTRACT_WALLET_USDT=0xC2C527C0CACF457746Bd31B2a698Fe89de2b6d49`

        You may get the wallet address click [here](https://etherscan.io/token/0xdac17f958d2ee523a2206206994597c13d831ec7) for the production environment.

        > `CONTRACT_WALLET_USDT=0xdAC17F958D2ee523a2206206994597C13D831ec7`

    - Change contract wallet BUSD

        You may get the wallet address click [here](https://testnet.bscscan.com/token/0xeD24FC36d5Ee211Ea25A80239Fb8C4Cfd80f12Ee) for the development environment.

        > `CONTRACT_WALLET_BUSD=0xeD24FC36d5Ee211Ea25A80239Fb8C4Cfd80f12Ee`

        You may get the wallet address click [here](https://bscscan.com/token/0xe9e7cea3dedca5984780bafc599bd69add087d56) for the production environment.

        > `CONTRACT_WALLET_BUSD=0xe9e7cea3dedca5984780bafc599bd69add087d56`

    - Change Network Blockchain use.

        Use Etherscan.

        > `NETWORK_BLOCKCHAIN=ETHERS`

        Use BscScan.

        > `NETWORK_BLOCKCHAIN=BSC`

9. Change default password decrypted
    > `PASSWORD_DECRYPTE=YOUR_PASSWORD_DECRYPTE`
10. Change api key transfer token
    > `API_KEY = YOUR_API_KEY`
11. Change api url transfer
    > `URI_UNLOCK_TOKEN=YOUR_API_URL`

12. Config websocket serve
    - change pusher config
        >`PUSHER_APP_ID= YOUR_PUSHER_APP_ID`</br>
        `PUSHER_APP_KEY=YOUR_PUSHER_APP_KEY` </br>
        `PUSHER_APP_SECRET=YOUR_PUSHER_APP_SECRET`</br>
        `PUSHER_HOST=YOUR_PUSHER_HOST`</br>
        `PUSHER_PORT=YOUR_PUSHER_PORT`</br>
        `PUSHER_SCHEME=YOUR_PUSHER_SCHEME`</br>
13. Save env file
    > `:wq`

## III. Setup website

1. Install composer
    > `composer install`
2. Running migrations
    > `php artisan migrate`
3. Running seeders
    > `php artisan db:seed`

## IV. Setup Crontab

1. Update and upgrade package
    > `apt-get update && apt-get upgrade`
2. Check whether the cron is installed or not. If installed, skip steps 3,4
    > `dpkg -l cron`
3. Install crontab
    > `apt-get install cron`
4. Check the status of installed crontab
    > `systemctl status cron`
5. Create or edit crontab
    - Create or edit crontab file
        > `crontab -e`
    - Add content
        > `* * * * * cd /var/www/crooz/kz-crooz-backend && php artisan schedule:run >> /dev/null 2>&1`
    - Save file
        > `:wq`
6. Show crontab file
    > `crontab -l`

## V. Setting up Supervisor

1. Update and upgrade package
    > `apt-get update && apt-get upgrade`
2. Install supervisor
    > `sudo apt-get install supervisor`
3. Go to the supervisor folder
    > `cd /etc/supervisor/conf.d`
4. Create worker new file

    1. Worker check status transaction

        - Create file

            > `vi kz-crooz-backend-check-status-worker.conf`

        - Insert content file

            > `[program:kz-crooz-backend-check-status-worker]`<br/>
            `process_name=%(program_name)s_%(process_num)02d`<br/>
            `directory=/var/www/crooz/kz-crooz-backend`<br/>
            `command=php artisan queue:work --queue=checkStatus --sleep=2 --tries=1 --timeout=120`<br/>
            `autostart=true`<br/>
            `autorestart=true`<br/>
            `user=root`<br/>
            `numprocs=1`<br/>
            `redirect_stderr=true`<br/>
            `stdout_logfile=/var/www/crooz/kz-crooz-backend/storage/logs/worker.log`

    2. Worker general

        - Create file

            > `vi kz-crooz-backend-worker.conf`

        - Insert content file
            > `[program:kz-crooz-backend-worker]`<br/>
            > `process_name=%(program_name)s_%(process_num)02d`<br/>
            > `directory=/var/www/crooz/kz-crooz-backend`<br/>
            > `command=php artisan queue:work --queue=geneal --sleep=2 --tries=1 --timeout=120`<br/>
            > `autostart=true`<br/>
            > `autorestart=true`<br/>
            > `user=root`<br/>
            > `numprocs=1`<br/>
            > `redirect_stderr=true`<br/>
            > `stdout_logfile=/var/www/crooz/kz-crooz-backend/storage/logs/worker.log`
    3. Worker run Socket server
        - Create file
            >`vi kz-crooz-backend-websocket-worker.conf`

         - Insert content file
            > `[program: kz-crooz-backend-websocket-worker]`<br/>
            > `process_name=%(program_name)s_%(process_num)02d`<br/>
            > `directory=/var/www/crooz/kz-crooz-backend`<br/>
            > `command=php artisan websockets:serve`<br/>
            > `autostart=true`<br/>
            > `autorestart=true`<br/>
            > `user=root`<br/>
            > `numprocs=1`<br/>
            > `redirect_stderr=true`<br/>
            > `stdout_logfile=/var/www/crooz/kz-crooz-backend/storage/logs/worker.log`

5. Restart supervisor and run queue.

    > `sudo service supervisor restart`

    > `sudo supervisorctl reread`

    > `sudo supervisorctl update`

    > `sudo supervisorctl start kz-crooz-backend-worker:*`

    > `sudo supervisorctl start kz-crooz-backend-check-status-worker.conf:*`

    > `sudo supervisorctl start kz-crooz-backend-websocket-worker.conf:*`
