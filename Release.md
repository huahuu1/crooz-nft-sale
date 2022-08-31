<h1 align="center" style="font-size:50px"><strong>Xeno Backend</strong></h1>

You need use Amazon Cloud and use Aurora MYSQL to database, S3 to upload file and SES to send email.

Rename file .env.example to .env and update file.

## Update the .env file.

### Set Default variable

-   APP_ENV=<code>production</code>
-   APP_DEBUG=<code>false</code>
-   APP_NAME=<code>YOU_APP_NAME</code>
-   SUCCESS_TRANSACTION_BLOCK_COUNT=<code>24</code>
-   MAX_PER_PAGE_MYPAGE=<code>100</code>
-   MAX_PER_PAGE_AUCTION=<code>100</code>
-   MAX_PER_PAGE_TOKENSALE=<code>10</code>
-   CONTRACT_WALLET_USDT=<code>[0xdAC17F958D2ee523a2206206994597C13D831ec7](https://etherscan.io/token/0xdac17f958d2ee523a2206206994597c13d831ec7)</code>
-   FILESYSTEM_DRIVER=<code>s3</code>

### Database

-   DB_HOST=<code>YOU_DB_HOST</code>
-   DB_PORT=<code>YOU_DB_PORT</code>
-   DB_DATABASE=<code>YOU_DB_DATABASE</code>
-   DB_USERNAME=<code>YOU_DB_USERNAME</code>
-   DB_PASSWORD=<code>YOU_DB_PASSWORD</code>

### Mail
-   MAIL_DRIVER=<code>YOU_MAIL_DRIVER</code>
-   MAIL_HOST=<code>YOU_MAIL_HOST</code>
-   MAIL_PORT=<code>YOU_MAIL_PORT</code>
-   MAIL_USERNAME=<code>YOU_MAIL_USERNAME</code>
-   MAIL_PASSWORD=<code>YOU_MAIL_PASSWORD</code>
-   MAIL_ENCRYPTION=<code>YOU_MAIL_ENCRYPTION</code>
### Aws

-   AWS_ACCESS_KEY_ID=<code>YOU_KEY_ID</code>
-   AWS_SECRET_ACCESS_KEY=<code>YOU_ACCESS_KEY</code>
-   AWS_DEFAULT_REGION=<code>ap-northeast-1</code>
-   AWS_BUCKET=<code>YOU_AWS_BUCKET</code>
-   AWS_URL=<code>YOU_AWS_URL</code>

### Custom Variable

-   MAIL_SEND_TOKEN_VALIDATE=<code>YOU_EMAIL</code>
-   COMPANY_WALLET= <code>YOU_COMPANY_WALLET</code>
-   NFT_COMPANY_WALLET=<code>YOU_NFT_COMPANY_WALLET</code>
-   ETHERSCAN_API_KEY= <code>[YOU_ETHERSCAN_API_KEY](https://docs.etherscan.io/getting-started/viewing-api-usage-statistics)</code>


Then is config .env file. Run command to install Website
#### Setup composer
> composer install

#### Run database install
> php artisan migrate

#### Run database default
> php artisan db:seed

### Run Cronjob
> php artisan schedule:run
### Run Queue
> php artisan queue:work
