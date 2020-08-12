# Mail Retry plugin for Craft CMS 3.x

Retry mails in background when they fail

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require nerds-and-company/craft-mail-retry

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Mail Retry.

## Mail Retry Overview

When a mail fails for some reason, f.e. when the mail server is unavailable, a job is created to retry the mail.

## Configuring Mail Retry

You can configure maximum number of automatic retry attempts and the time to reserve between attemts

## Using Mail Retry

It just works

Brought to you by [Nerds & Company](nerds.company)
