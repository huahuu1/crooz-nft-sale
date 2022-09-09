<h1 align="center" style="font-size:50px"><strong>Xeno Backend</strong></h1>

You need use Amazon Cloud and use Aurora MYSQL to database, S3 to upload file and SES to send email.

## I. Source code clone

1. Source code clone from github

    > `git clone https://github.com/Kozocom-Lab/kz-crozz-backend.git`

2. Check current branch

    > `git branch –show-current`

3. Change branch of environment on development or production

    > `git checkout -b develop`

    > `git checkout -b main`

## II. Update the env file.
### 1. Open env file
1. Go to folder source

    > `cd ./kz-crozz-backend/`

2. Create env file

    > `cp .evn.example .env`

3. Open evn file

    > `vi ./evn`

### 2. Set Default variable

1. Set App Name

    > `APP_NAME=YOU_APP_NAME`

2. Change App Url

    > `APP_URL=YOU_APP_URL`

3. Environment on development
    Environment on develop

    > `APP_ENV=develop`

    Environment on production

    > `APP_ENV=production` <br/> `APP_DEBUG=false`

### 3. Change Database connect info

> `DB_HOST=YOU_DB_HOST`<br/> `DB_PORT=YOU_DB_PORT`<br/> `DB_DATABASE=YOU_DB_DATABASE`<br/> `DB_USERNAME=YOU_DB_USERNAME`<br/> `DB_PASSWORD=YOU_DB_PASSWORD`<br/>

### 4. Change Mail info

> `MAIL_DRIVER=YOU_MAIL_DRIVER`<br/> `MAIL_HOST=YOU_MAIL_HOST` <br/> `MAIL_PORT=YOU_MAIL_PORT` <br/> `MAIL_USERNAME=YOU_MAIL_USERNAME` <br/> `MAIL_PASSWORD=YOU_MAIL_PASSWORD` <br/> `MAIL_ENCRYPTION=YOU_MAIL_ENCRYPTION`

### 5. Change AWS info

> `AWS_ACCESS_KEY_ID=YOU_KEY_ID` <br/> `AWS_SECRET_ACCESS_KEY=YOU_ACCESS_KEY` `AWS_DEFAULT_REGION=YOU_DEFAULT_REGION`<br/> `AWS_BUCKET=YOU_AWS_BUCKET` <br/> `AWS_URL=YOU_AWS_URL`

### 6. Change custom variable

1. Mail send token validate in mail verify

    > `MAIL_SEND_TOKEN_VALIDATE=YOU_EMAIL`

2. Company wallet a receive Token Sale

    > `COMPANY_WALLET= YOU_COMPANY_WALLET`

3. Company wallet a receive NFT Auction

    > `NFT_COMPANY_WALLET=YOU_NFT_COMPANY_WALLET`

4. Change Etherscan Api Key. You can a get key by click [here](https://docs.etherscan.io/getting-started/viewing-api-usage-statistics)

    > `ETHERSCAN_API_KEY= YOU_ETHERSCAN_API_KEY`
5. Change Contact wallet USDT
 
    Environment on develop you can get contact wallet in [here](https://ropsten.etherscan.io/token/0x110a13fc3efe6a245b50102d2d79b3e76125ae83)
    > `CONTRACT_WALLET_USDT=0x110a13fc3efe6a245b50102d2d79b3e76125ae83`
    
    Environment on production you can get contact wallet in [here](https://etherscan.io/token/0xdac17f958d2ee523a2206206994597c13d831ec7)
    > `CONTRACT_WALLET_USDT=0xdAC17F958D2ee523a2206206994597C13D831ec7`
6. Change BSCScan API. You can a get key by click [here](https://docs.bscscan.com/getting-started/viewing-api-usage-statistics)
    > `BSCSCAN_API_KEY=YOU_BSCSCAN_API_KEY`

### 7. Save env file
Then update env file you can save file by press ESC in keyboard

To save

> `:wq`

To Quit

> `:qa`

III. Setup website

1.  Setup is PHP package please run command

    > `composer install`

2. Setup Database

    > `php artisan migrate`

3. Setup default Database

    > `php artisan db:seed`

IV. Setup Crontab

1. Update server, please

    > `apt-get update && apt-get upgrade`

2. Check cron it's installed or not if install skip step 3,4
    > `dpkg -l cron`
3. Install crontab
    > `apt-get install cron`
4. Check the status of installed Crontab
    > `systemctl status cron`
5. Create or edit crontab file
    - Create or edit file cron
        > `crontab -e`
    - add new line in last file
        > `* * * * * cd /var/www/crozz/kz-crozz-backend && php artisan schedule:run >> /dev/null 2>&1`
    - Save file 
6. Show crontab file
    > `crontab -l`

V. Setup Supervisor

1. Update server, please
    > `apt-get update && apt-get upgrade`
2. Setup SuperVisor
    > `sudo apt-get install supervisor`
3. Go to supervisor folder
    > `cd /etc/supervisor/conf.d`
4. create new file
    > `vi kz-crozz-backend-worker.conf`
5. add new code on kz-crozz-backend-worker.conf file 
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
