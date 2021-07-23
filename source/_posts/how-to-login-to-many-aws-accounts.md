---
extends: _layouts.post
section: content
title: How to login to multiple AWS accounts without going crazy
author: George Boot
date: 2021-07-23
description: In this blog post I will explain a super handy technique on how to organise access to multiple AWS accounts.
categories: [tech]
featured: true
---

Most SAAS platforms allow you to create multiple users within an organisation these days, which comes in very useful when you manage multiple accounts. For me personally, I manage not only the accounts from my work, but also the ones from my own side projects. On most SAAS sites, I simply create a second organisation for my side projects and add my user to it. This way, I have to remember only one account, but can access multiple profiles.


```php
class TestClass extends BaseClass
{
    public readonly string $test;
}
```

Unfortunately, AWS does not support this behaviour. Yes, AWS allows you to create multiple IAM users, but they are basically users within the AWS account. You can not access AWS account 1 using AWS account 2.

*Or can you?* In fact, it turns out, you totally can! But – as is with many AWS services – it works totally different from what you expect.

## IAM Assumed Roles
Meet IAM Assumed Roles. IAM Assumed *what*? Exactly, that was my first reaction as well. The concept is actually pretty simple, so let me explain.

// what are they?

## Set up your own
//

### Primary account

Pick your 'primary' AWS account. I use my personal one (side projects etc)

### Adding a role
Now for each AWS you want to have easy access to (let's call them secondary accounts), login to the account and do the following:

In IAM, add a role:
- Select `another AWS account` as the type of trusted entity.
- Fill in the `Account ID` of your primary AWS account
- Go to the permissions, and select `Administrator Access` (or more restricted permissions if you prefer, but this will be the easiest)
- On the tags tab, add tags if you want
- On the review tab, give the role a kebab-case name. You will need this name later.

### Using the role
Now to use the role from your primary account:
- Make sure you are logged in to your primary account
- Click on your name in the top-right of your screen and select `Switch Roles`
- Enter the `Account ID` of the account you want to access
- Enter the role name you picked when setting up the role
- Optionally, give the role a display name and/or a colour
- Click `Switch Role`

## Hot tip: bookmarks
To make the above easier, I made bookmarks to all of them.

When I need to access any of my accounts, I login to the AWS console to my primary account, and click one of the bookmarks.

A typical bookmark url will be: `https://signin.aws.amazon.com/switchrole?account={ACCOUNT_ID}}&roleName={ROLE_NAME}&displayName={DISPLAY_NAME}`.