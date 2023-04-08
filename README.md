Yoki backend
https://api.yoki.uz

.env setup

```
APP_ADMIN_URL=<yoki-admin-panel-url>
API_SECRET=<api-key>

//Social auth:
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=
SOCIAL_AUTH_REDIRECT_URL=<redirect_url_after_successful_social_auth>

//Sms:
SMS_DRIVER=<driver_to_send_messages>// valid values: playmobile, telegram_bot
SMS_CODE_LIFETIME=<sms_code_timeout>

//Playmobile:
PLAYMOBILE_HOST=
PLAYMOBILE_USERNAME=
PLAYMOBILE_PASSWORD=

//Telegram Bot:
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHAT_ID=

//Digital ocean:
DIGITALOCEAN_SPACES_KEY=
DIGITALOCEAN_SPACES_SECRET=
DIGITALOCEAN_SPACES_ENDPOINT=
DIGITALOCEAN_SPACES_REGION=
DIGITALOCEAN_SPACES_BUCKET
```

## Testing:

### How to run tests?

1. First make sure you have connection to database
2. Delete all migration files in <code>database/migrations</code> folder
3. Run <code>php artisan migrate:generate --squash</code> to generate new migration file
4. Then run tests: <code>php artisan test</code>

## Exceptions:

App exceptions are thrown to https://sentry.io/
