---
extends: _layouts.post
section: content
title: How to login to multiple AWS accounts without getting crazy
author: George Boot
date: 2021-07-23
description: In this blog post I will explain a super handy technique on how to organise access to multiple AWS accounts.
categories: [tech]
featured: true
---

Pick your 'primary' AWS account. I use my personal one (side projects etc)

Now for each AWS you want to have easy access to (let's call them secondary accounts), login to the account and do the following:

In IAM, add a role:
- Select `another AWS account` as the type of trusted entity.
- Fill in the `Account ID` of your primary AWS account
- Go to the permissions, and select `Administrator Access` (or more restricted permissions if you prefer, but this will be the easiest)
- On the tags tab, add tags if you want
- On the review tab, give the role a kebab-case name. You will need this name later.

Now to use the role from your primary account:
- Make sure you are logged in to your primary account
- Click at your name on the top-right of your screen and select `Switch Roles`
- Enter the `Account ID` of the account you want to access
- Enter the role name you picked when setting up the role
- Optionally, give the role a display name and/or a colour
- Click `Switch Role`

To make the above easier, I made bookmarks to all of them.

When I need to access any of my accounts, I login the the AWS console to my primary account, and click one of the bookmarks.

A typical bookmark url will be: `https://signin.aws.amazon.com/switchrole?account={ACCOUNT_ID}}&roleName={ROLE_NAME}&displayName={DISPLAY_NAME}`.