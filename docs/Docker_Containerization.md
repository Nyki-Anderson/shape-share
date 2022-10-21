---  
title: Docker_Containerization  
created: 2022-10-20 22:59  
modified: <%+ tp.file.last_modified_date() %>  
topic: How to containerize Apache, PHP, and MySQL.  
tags:  
  - docker  
  - readme  
share: true  
---  
  
# Docker Containerization  
---  
## Why Docker?  
  
 If you've ever tried to setup your own server configuration, it can be a pretty daunting task. Not to mention trying to reproduce a given project on multiple machines with different OS's and host configurations is a hassle and is often prone to inconsistencies come runtime. [Docker](https://www.docker.com) is a containerization solution that solves all those problems and more.   
  
[Containerization](https://www.citrix.com/solutions/app-delivery-and-security/what-is-containerization.html) is a form of virtualization where applications run in isolated user spaces, called containers. The benefit of this is that you essentially have a pre-packaged instance of your project that runs the same on any host computer. Containerization can carry a project from development to production and is capable of being much lighter than running a virutal machine. This is because instead of building an entire OS, containers are slim images that only contain the packages your application needs. This makes containers faster and more streamlined than any other build method.   
  
Docker is used extensively among software development teams as a way to test an application on multiple environments and maintain consistency. It is the industry standard and when used correctly, it can speed up the development and testing process. I spent months learning and configuring my Docker images, so there is a steep learning curve but once you get everything running, you have a perfectly curated development environment. Docker is heavily documented and has a great wiki, visit their site to learn the lingo as I won't go into much detail throughout the tutorial.  
  
---  
  
## Docker-Specific Terminology  
  
> **NOTE:**  
> Definitions borrowed form the official [Docker wiki](https://docs.docker.com/glossary/)  
  
  
Term | Definition  
--- | ---  
Container | A container is a runtime instance of a docker image. A docker container consists of a docker image, an execution environment, and a standard set of instructions.  
Image | Docker images are the basis of containers. An image is an ordered collection of root filesystem changes and the corresponding execution parameters for use within a container runtime.  
Dockerfile | A dockerfile is a text document that contains all the commands you would normally execute manually in order to build a Docker image.  
Compose | Compose is a tool for defining and running complex applications with Docker. With Compose, you define a multi-container application in a single file, then spin your application up in a single commmand which does everything that needs to be done to get it running.  
Volume | A volume is a specifically-designated directory within one or more containers that bypasses the Union File System. Volumes are designed to persist data, independent of the container's life-cycle.  
  
---  
  
## Installing Docker Desktop and Docker Compose  
  
Installing Docker is fairly simple and is available on all OS's. I am currently developing on a Mac so your installation method may differ from mine, but the beauty of Docker is, once it's installed,  no matter where the containers are run they operate in Linux (or Windows). The Docker Desktop GUI is my preferred way to run Docker because you can see exactly what images, containers, and volumes are running as well as have access to active logging to help troubleshoot.   
  
Install for Mac OS (Intel) by one of two ways:  
- Go to [Docker.com](https://www.docker.com/) and install via .dmg file (preferred method)  
- Install via [Homebrew](https://formulae.brew.sh/cask/docker) with the command:  `$ brew install --cask docker`  
  
> **Issues**  
> I had issues when I installed via Homebrew that resulted in my docker daemon not running despite Docker Desktop being active. To fix this, I had to completely [uninstall Docker Desktop](https://nektony.com/how-to/uninstall-docker-on-mac) and then reinstall/update the application which was a major pain.   
  
To install on Linux or Windows go [here](https://dockerwebdev.com/tutorials/install-docker/) for instructions.  
  
We will also be making use of a terrific companion to Docker Desktop called Docker Compose. Docker Compose is supposed to come with the Docker Desktop installation but mine didn't so I had to install via [Homebrew](https://formulae.brew.sh/formula/docker-compose) `$ brew install docker-compose`. For installation instructions on other OS's, go [here](https://docs.docker.com/compose/install/).  
  
Docker Compose is not required to run containers. Everything that the Docker Compose file does can be run on command line but the CLI commands for Docker can get lengthy and are not practical when running multiple containers. Once we start building our containers we will use Dockerfiles to build our custom images and Docker Compose to configure the image builds and run the containers.  
  
---  
  
## Directory Structure for Docker Configuration  
  
```  
--- /docker-compose.yml  
--- /.docker  
	--- /db  
		--- /init_scripts  
			--- dev_db_instance.sql  
		--- /my.cnf.d  
			--- client_ssl.cnf  
			--- encrypt.cnf  
			--- my.cnf  
			--- server_ssl.cnf  
		--- /mysql_data  
		--- Dockerfile  
	--- /server  
		--- /apache  
			--- /sites-enabled  
				--- httpd.conf  
				--- vhost.conf  
		--- /php  
			--- conf.d  
				--- php.ini  
				--- xdebug.ini  
		--- Dockerfile  
```  
  
---  
  
## Building Docker Images  
  
##### References  
- [Installing Apache, MySQL, and PHP on macOS using Docker](https://jasonmccreary.me/articles/install-apache-php-mysql-macos-docker-local-development/)  
- [Dockerizing A PHP Application](https://semaphoreci.com/community/tutorials/dockerizing-a-php-application)  
  
> **NOTE**  
> In reality, I used dozens of tutorials and references over the course of a couple of months getting my head around Docker. I have listed only the most comprehensive resources I used for brevity.  
  
The first thing we need to do to get our Docker containers running is write the special Dockerfile's for each service we wish to deploy. In this case, we are running an **Apache** server, a **PHP** engine, and a **MySQL** database server. Each of these will need their own Dockerfile so that we may customize the images. We will be using Docker Hub official images but you can use whatever base image you like, though configurations may differ.   
  
```docker  
FROM php:8.1-apache  
  
# Update OS and install common dev tools  
RUN apt-get update  
RUN apt-get install -y zip unzip libzip-dev libpng-dev libicu-dev ca-certificates  
  
# Install PHP extensions needed  
RUN docker-php-ext-install mysqli pdo_mysql openssl curl  
RUN pecl install xdebug  
RUN docker-php-ext-enable  
  
# Enable common Apache modules  
RUN a2enmod headers expires rewrite php8.0    
  
# Install Composer  
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer  
RUN composer global require friendsofphp/php-cs-fixer  
  
# Set working directory to web files  
WORKDIR /var/www/local.shape-share.com  
```  
