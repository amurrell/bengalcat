# Deploying to Google App Engine

Requirements:

- app.yaml in root of project
- php.ini in root of project (for php-curl in app engine environment)
- Google App Engine PHP SDK
- In google cloud platform make sure you have synced your github or bitbucket repo to google

### App.yaml and php.ini

These are already included in your repo! Yay. 

Make sure the name of your app engine project is up to date in the app.yaml.

### Install Google App Engine PHP SDK

1. Go to the [google app engine downloads page](https://cloud.google.com/appengine/downloads) and look for the **PHP SDK** zip.
2. Also on that page, toggle the install instructions for unzipping according to your operating system.
3. My docs will reflect linux (ubuntu specifically):
    - `unzip google_appengine...zip` (do this somewhere you remember, maybe up a level from where you install your site repos)
    - `export PATH="$PATH:/path/to/google_appengine/"` will allow you to use the python scripts from anywhere (where /path/to is the full path to whereever you unzipped the above.
    - To save the above setting for future use (need to log out of your computers user and log back in for it to be permanent) do the following:
        - `sudo nano .bashrc` and append `export PATH="$PATH:/path/to/google_appengine/"` to the file, and then save it.
    - make sure you have python 2.7+ `/usr/bin/env python -V`

### Actual Deploy!

Always do from the root of your project's repo

- You need to temporarily move node_modules (if you installed them) out of the project before deploying.
        - `mv html/assets/node_modules ~/`
        - move them back after deploy `mv ~/node_modules html/assets/`

- Deploy
        - `appcfg.py -A PROJECTNAME -V v1 update .`

You can see your project here: (but probably should disable this when your project goes live under your domain)

http://PROJECTNAME.appspot.com/


### Easier Deploy!

Instead of moving node_modules yourself you can do this

- First time only make sure to: `chmod +x deploy.sh`
- from then on... `./deploy.sh`

Be sure to update the deploy.sh to any new params you need like new versions or project names etc.