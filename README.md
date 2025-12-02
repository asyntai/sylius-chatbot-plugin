# Asyntai - AI Chatbot for Sylius

Create and launch AI assistant/chatbot for your Sylius website in minutes. It talks to your visitors, helps, explains, never misses a chat and can increase conversion rates! All while knowing your website, customized just for you. Your Sylius website can now talk.

This plugin embeds the Asyntai chatbot on your Sylius site and provides a simple admin interface to connect your site to Asyntai.


## Why choose Asyntai?

* **Increase conversions**: Instant, human like replies keep shoppers engaged and buying.

* **Never miss a chat**: The AI replies day and night, even when your team is offline.

* **Knows your website**: Customized just for you; it follows your instructions.

* **Works in all languages**: Automatically detects and answers in the visitor's language.

* **Fast responses (1-3s)**: Keeps customers from bouncing.

## Installation

### Via Composer (recommended)

```bash
composer require asyntai/sylius-chatbot-plugin
```

Then run:

```bash
php bin/console asyntai:install
php bin/console assets:install public
php bin/console cache:clear
```

### Manual Installation

1. Copy the plugin folder to your Sylius project
2. Register the plugin in `config/bundles.php`:
   ```php
   return [
       // ... other bundles
       Asyntai\SyliusChatbotPlugin\AsyntaiSyliusChatbotPlugin::class => ['all' => true],
   ];
   ```

3. Import the routes in `config/routes/asyntai_chatbot.yaml`:
   ```yaml
   asyntai_chatbot:
       resource: '@AsyntaiSyliusChatbotPlugin/Resources/config/routes.yaml'
   ```

4. Run the install command (creates the database table automatically):
   ```bash
   php bin/console asyntai:install
   ```

5. Install assets:
   ```bash
   php bin/console assets:install public
   ```

6. Clear cache:
   ```bash
   php bin/console cache:clear
   ```

## Configuration

After installation:

1. Go to Admin Panel -> Asyntai AI Chatbot -> Settings
2. Click "Get started" to connect your Asyntai account
3. Sign in or create a free account
4. The plugin will automatically save your connection
5. The chatbot is now live on your site!
6. Set up your chatbot, review chat logs and more at: [asyntai.com/dashboard](https://asyntai.com/dashboard)

Don't have an account yet? Create a free Asyntai account at [asyntai.com/auth](https://asyntai.com/auth)

## Managing Your Chatbot

Once connected, you can manage your chatbot settings, review chat logs, and customize AI responses at:
[asyntai.com/dashboard](https://asyntai.com/dashboard)



## Requirements

- Sylius 2.0 or higher
- PHP 8.2 or higher
- Symfony 7.0 or higher
- Backend admin access for configuration



## Have a question?
Email us at hello@asyntai.com or try our chatbot directly at [asyntai.com/](https://asyntai.com/)


![Asyntai AI chatbot 1](https://asyntai.com/static/images/ai-chatbot-for-websites-1.png)
![Asyntai AI chatbot 2](https://asyntai.com/static/images/ai-chatbot-for-websites-2.png)
![Asyntai AI chatbot 3](https://asyntai.com/static/images/ai-chatbot-for-websites-3.png)
![Asyntai AI chatbot 4](https://asyntai.com/static/images/ai-chatbot-for-websites-4.png)
![Asyntai AI chatbot 5](https://asyntai.com/static/images/ai-chatbot-for-websites-5.png)
