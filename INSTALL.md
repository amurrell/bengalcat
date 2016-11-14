# BengalCat PHP Framework - Local Development Installation

Requirements:

- You will need to install **docker** and **docker-compose** on your own.

## Install & Run - Docker Compose

Download repo

```
cd DockerLocal
sudo docker-compose up
```
Go to [http://localhost:8080](http://localhost:8080)

## Install & Run - Not docker compose...

Download repo

```
cd DockerLocal
sudo docker build -t mysitename .
sudo docker run -d -p 3000:8080 --net=host -v `pwd | sed 's,/*[^/]\+/*$,,'`:/var/www/site mysitename
```

Go to [http://localhost:8080](http://localhost:8080)

---

# Access Terminal, View Logs ...

## Get Name of your Container for all other commands

Observe the NAME of your mysitename container

`sudo docker ps -a`

## Check logs

`sudo docker logs CONTAINER_NAME`

## Get to the commandline of your image IF it IS RUNNING

`sudo docker exec -t -i CONTAINER_NAME /bin/bash`

## Get to the command line of your container IF NOT RUNNING

Observe name of image, probably your sitefolder_web
`sudo docker images`

Use it below:

`sudo docker run -it IMAGE_NAME /bin/bash`


## Stop your container

`sudo docker stop CONTAINER_NAME`

---

# Basic Docker Help

Refer to this doc: https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-getting-started

----

## Start docker

`sudo docker -d &`

## Test the Install

`sudo docker run hello-world`

## List available images

`sudo docker images`

## Commit changes to an image (save state)

`sudo docker commit [container ID] [image name]`

## Sharing your image by pushing

`sudo docker push my_username/my_first_image`

(you would need to sign up on index.docker.io to push images to docker index)

## List running containers

`sudo docker ps`

## List running AND non-running containers

`sudo docker ps -l`

## To get command access to a running container

`sudo docker exec -t -i container_name /bin/bash`

where you get the container_id from listing the containers

and if it was not running:

`sudo docker run -it --entrypoint /bin/bash`

## Remove containers

`sudo docker rm container ID`

----

# Advanced Docker Manipulation


## Install more packages, php libraries and so on

Your Dockerfile can be edited to install more things if you need.

Add to dockerfile at least before CMD

```
RUN apt-get update && \
    apt-get install -y a-package && \
    apt-get install -y another-package
```

## Update your environmental variables

Maybe you want to set the database environmental variables.

1. Edit the php7-fpm.site.conf to reflect these env var changes.
2. Need to rebuild. Either `sudo docker-compose build` or `sudo docker build -t mysitename`
3. Need to rerun the container. Either `sudo docker-compose up` or `sudo docker run -d -p 3000:8080 -v ./:/var/www/site mysitename`

## Update specific nginx config or location blocks

Maybe you want to edit your site's nginx configuration

1. Edit the nginx.site.conf to reflect those config changes.
2. Need to rebuild, need to restart - so refer to that in the section above, 2. and 3.

----

## Node Setup

You do not have to utilize build. Edit your `html/src/templates/head.php` to
alter the style sheet used to `/assets/css/style.css`.

### If you want to use gulp...

1. Edit your `html/src/templates/head.php` to alter the style sheet used to
 `/assets/build/main.css`.

Install NodeJS, npm etc - fastest most consistent way I've ever found:

1. Download **binaries** appropriate for your computer, ie mac etc from: https://nodejs.org/en/download/
1. `cd /usr/local`
1. `tar --strip-components 1 -xzf /path/to/downloaded/tar/your-zip.tar.gz`

Then go to `html/assets` and run `npm install`

Also had to run `npm install --global gulp-cli` to be able to use `gulp`

Additionally, on linux, was running into issues when trying to `gulp watch`
which was solved by:

    `echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p`
