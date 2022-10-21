---  
title: Docker_Containerization  
created: 2022-10-20 22:59  
modified: <%+ tp.file.last_modified_date() %>  
topic: How to containerize Apache, PHP, MySQL, and Adminer.  
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
  
## Installing Docker Desktop  
  
Installing Docker is fairly simple and is available on all OS's. I am currently developing on a Mac so your installation method may differ from mine, but the beauty of Docker is, once it's installed,  no matter where the containers are run they operate in Linux (or Windows). The Docker Desktop GUI is my preferred way to run Docker because you can see exactly what images, containers, and volumes are running as well as have access to active logging to help troubleshoot.   
  
Install for Mac OS (Intel) by one of two ways:  
- Go to [Docker.com](https://www.docker.com/) and install via .dmg file (preferred method)  
- Install via [Homebrew](https://formulae.brew.sh/cask/docker) with the command:  `$ brew install --cask docker`  
  
> [!Note]  
> I had issues when I installed via Homebrew that resulted in my docker daemon not running despite Docker Desktop being active. To fix this, I had to completely [uninstall Docker Desktop](https://nektony.com/how-to/uninstall-docker-on-mac) and then reinstall/update the application which was a major pain.   
  
To install on Linux or Windows go [here](https://dockerwebdev.com/tutorials/install-docker/)for instructions.  
  
  
  
