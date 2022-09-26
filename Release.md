<h1 align="center" style="font-size:50px"><strong>Xeno Backend</strong></h1>

You need to use Amazon Cloud and Aurora MYSQL to the database, S3 to upload files, and SES to send email.

## I. Source code clone
### 1. Source code clone from github
> `git clone https://github.com/Kozocom-Lab/kz-crozz-backend.git`
### 2. Change branch depending on usage environment.
- Develop environment: 
    > `git checkout -b develop`
- Production environment: 
    > `git checkout master`
## II. Setting env file
### 1. Create env file
> `cd ./kz-crozz-backend/`
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
7. Change AWS  information
    > `AWS_ACCESS_KEY_ID=YOUR_KEY_ID` <br/> `AWS_SECRET_ACCESS_KEY=YOUR_ACCESS_KEY` `AWS_DEFAULT_REGION=YOUR_DEFAULT_REGION`<br/> `AWS_BUCKET=YOUR_AWS_BUCKET` <br/> `AWS_URL=YOUR_AWS_URL`
8. Change custom variable
    - Mail send token validate in mail verify
        > `MAIL_SEND_TOKEN_VALIDATE=YOUR_EMAIL`
    - Company wallet a receive Token Sale
        > `COMPANY_WALLET= YOUR_COMPANY_WALLET`
    - Company wallet a receive NFT Auction
        > `NFT_COMPANY_WALLET=YOUR_NFT_COMPANY_WALLET`
    - Change etherscan API Key.

        You can a get key by click [here](https://docs.etherscan.io/getting-started/viewing-api-usage-statistics)
        > `ETHERSCAN_API_KEY= YOUR_ETHERSCAN_API_KEY`
    - Change contact wallet USDT

        Environment on develop you can get contact wallet in [here](https://ropsten.etherscan.io/token/0x110a13fc3efe6a245b50102d2d79b3e76125ae83)

        > `CONTRACT_WALLET_USDT=0x110a13fc3efe6a245b50102d2d79b3e76125ae83`

        Environment on production you can get contact wallet in [here](https://etherscan.io/token/0xdac17f958d2ee523a2206206994597c13d831ec7)

        > `CONTRACT_WALLET_USDT=0xdAC17F958D2ee523a2206206994597C13D831ec7`
    - Change BSCScan API

        You can a get key by click [here](https://docs.bscscan.com/getting-started/viewing-api-usage-statistics)
        > `BSCSCAN_API_KEY=YOUR_BSCSCAN_API_KEY`
8. Save env file
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
        > `* * * * * cd /var/www/crozz/kz-crozz-backend && php artisan schedule:run >> /dev/null 2>&1`
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
4. Create new file
    > `vi kz-crozz-backend-worker.conf`
5. Add content
    > `[program:kz-crozz-backend-worker]`<br/>
    `process_name=%(program_name)s_%(process_num)02d`<br/>
    `directory=/var/www/crozz/kz-crozz-backend`<br/>
    `command=php artisan queue:work --sleep=1 --tries=1`<br/>
    `autostart=true`<br/>
    `autorestart=true`<br/>
    `user=root`<br/>
    `numprocs=2`<br/>
    `redirect_stderr=true`<br/>
    `stdout_logfile=/var/www/crozz/kz-crozz-backend/storage/logs/worker.log`
6. Restart supervisor and run queue.
    > `sudo service supervisor restart`

    > `sudo supervisorctl reread` 

    > `sudo supervisorctl update` 

    > `sudo supervisorctl start kz-crozz-backend-worker:*`

