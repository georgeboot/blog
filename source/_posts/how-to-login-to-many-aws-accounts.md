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


Unfortunately, AWS does not support this behaviour. Yes, AWS allows you to create multiple IAM users, but they are basically users within the AWS account. You can not access AWS account 1 using AWS account 2.

*Or can you?* In fact, it turns out, you totally can! But – as is with many AWS services – it works totally different from what you expect.

## IAM Assumed Roles
Meet IAM Assumed Roles. IAM Assumed *what*? Exactly, that was my first reaction as well. The concept is actually pretty simple, so let me explain.

The whole concept revolves around pretending to be someone, yet officially still being someone else. Some admin panels give admins the options to log-in as a user to debug something. This is basically that. You log-in using *Account 1*, but should be treated as if you are *Account 2*.

AWS calls this concept *Assumed Roles* and they are part of IAM. Where in most of these admin systems a flag like `pretend_to_be_user_id` will be added to your session, AWS calls this `assumed_role`. And instead of putting the user account in there, AWS store the role.

This last part is actually quite important in practise: your user ID always remains the same, so audit logs will always show your user name. Only the permissions of your account are temporarily changed. See why AWS named it *Assumed* roles? You are basically instructing the systems to *assume* you have a specific role.

## Set up your own
Convinced yet? Let's go though the process of setting this up together. It should take 2-3 minutes per account and can easily be undone.

### Primary account
First of, let's establish some important names, otherwise we will still go crazy ;-)

Let's pick our `Primary Account`. For me personally, this is my personal AWS account. This will be the account you always login to and will be used to access the other accounts.

In the next steps we are going to create an access structure so that your `Primary Account` has access to one or multiple `Secondary Accounts`.

### Adding a role
Log in to (one of) your `Secondary Account`, and go to the IAM service. Once there, select the *Roles* tab on the left.

To add a new role, follow these steps:
1. From the *roles& tab, click *Create Role*
2. Select `another AWS account` as the type of trusted entity and fill in the `Account ID` of your primary AWS account
3. Go to the permissions, and select `Administrator Access` (or more restricted permissions if you prefer, but this will be the easiest)
4. On the tags tab, add tags if you want
5. On the review tab, give the role a kebab-case name. You will need this name later.

### Using the role
Now that we have the role created, we can start using it from our `Primary Account`.

1. Make sure you are logged in to your `Primary Account`
2. Click on your name in the top-right of your screen and select `Switch Roles`
3. Enter the `Account ID` of the account you want to access
4. Enter the role name you picked when setting up the role
5. Optionally, give the role a display name and/or a colour
6. Click `Switch Role`

You should now be logged in as the secondary account you selected. Or to be more precise, AWS added the role you provided as an *assumed role* to your session.

Once you are done, you can simply click `Back to {your user name}` in the same menu.

## Hot tip: use bookmarks
If you have quite a couple of AWS accounts to take care of (like me) I've found that it is super handy to add bookmarks to all the roles. AWS doesn't really document this feature, but it works nevertheless.

I have the following bookmarks set-up:
1. Log in to AWS: `https://signin.aws.amazon.com/console` (alternatively you can use `https://{your-primary-account-id}.signin.aws.amazon.com/console` to pre-fill the account ID)
2. AWS Role: Secondary Account 1: `https://signin.aws.amazon.com/switchrole?account={secondary-account-1-id}&roleName={secondary-account-1-role-name}&displayName=SecondaryAccount1`
2. AWS Role: Secondary Account 2: `https://signin.aws.amazon.com/switchrole?account={secondary-account-2-id}&roleName={secondary-account-2-role-name}&displayName=SecondaryAccount2`
3. AWS Role: Secondary Account 3: `https://signin.aws.amazon.com/switchrole?account={secondary-account-3-id}&roleName={secondary-account-3-role-name}&displayName=SecondaryAccount3`
4. Etc.
