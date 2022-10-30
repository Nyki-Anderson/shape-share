---  
title: Introduction to Shape-Share  
created: 2022-10-21 04:59  
topic: Introduction to Shape-Share and Formatting of this Tutorial.  
tags:  
  - tutorial  
  - shape-share  
share: true  
---  
  
  
# Introduction to Shape-Share  
  
##  About this Tutorial  
  
This tutorial will take you from configuring your own `Docker` server and database to a fully building a fully interactive `PHP` web application with tons of fun functionality. All tutorial files can be found in the **docs** folder at the root of this repository and will be numerically ordered for your convenience. Follow along or pick and choose where to jump in. This project is about sharing what I’ve learned through nearly a year of trial and error and hopefully helping someone make less mistakes than I did. It’s about taking a project from zero to development and maybe even through to production.   
  
When I first started out, I had no idea where to start and I struggled to find a single resource that could take me through the whole process of web development. That’s what I hope this repository will eventually become. I will be providing some of the better references I was able to find as footnotes where helpful and I will also do my best to explain why I did things a certain way or at the very least warn you of how not to do it. I am a student and by no means an authority on any of the concepts or languages used in this project. I am simply documenting my journey should it be useful to anyone else at a similar stage in their programming career.   
  
## What is Shape-Share?  
  
Shape-Share is a concept I created with the sole purpose of exploring all facets of web development in the broadest sense. I wanted something I could expand upon without being limited by the what of it all. It’s less about shapes as it is about universality. It was also important to me that the project could be filled with content that I could easily render on my own without much fuss. What’s easier than pulling up `Paint` and drawing up a purple triangle? Plus, there’s no risk of copyright infringement or need to upload personal content.   
  
The first iteration of this project can be found in my other repository Shape_Search. With that project I designed a members-only platform where users could make a free account and upload images of shapes and interact with other users. This time around, I am going to focus on delineating between content creators and the casual subscriber. Creators will have access to a dashboard where they can upload images and interact with fans while the casual user will simply collect and react to their favorite images.  
  
From there I’ll likely implement subscriptions to creators, the ability to comment, and anything else that sounds interesting or challenging. A key focus throughout this project will be to implement a safe and secure web application, not just a hacker’s fun box with gaping security holes. I became obsessed with web security throughout Shape_Search and most of my time was spent running in circles with how much there is to consider when hardening a website. It’s no joke.  I plan to have the application pentested routinely to help identify trouble areas and maintain a DevSecOps mindset. So get to know an ethical hacker and let them practice on your project as well!  
  
Let’s get started!  
  
## Formatting Conventions in this Tutorial  
  
Before we get going, here are some typographical conventions I will be using throughout the tutorial. Notice that *italics* denote the project file system that you will be implementing on your host computer and **bold** text is a reference to a file within this repository specifically. Most of this project will be containerized and not require you to store or install anything on your host computer itself but some secrets and security keys will need to be handled outside of the project directory for obvious reasons.  
  
| Type of Text  | Corresponding Information                       |  
| ------------- | ----------------------------------------------- |  
| **bold**      | Filename or Path to File Within this Repository                    |  
| *italic*      | Project Filename or Path to File on Host System |  
| `inline code` | Application, Language, or Tool Name             |  
  
```  
A code block will either display instructions to run or a file to be copied.  
```  
  
> :memo: Note  
> This is a note  
  
> :warning: Warning!  
> This is a warning  
  
## Some Considerations  
I am coding on a Macbook Pro and thus some of this tutorial will be Mac-specific. I will try to include resources for other operating systems where necessary but once you get your `Docker` container up and running, we will essentially be building in a `Linux` environment anyways.   
  
The remainder of this document will detail my coding setup for anyone interested and will of course be entirely subjective. I am pretty proud of my go to tools but they are by no means the only way to implement this project. Skip ahead to the **doc/1.0_Docker_Containerization** document if you are ready, otherwise let’s talk about tools and apps that make coding more enjoyable and productive.  
  
> :note: Note:  
> I am not compensated in anyway by the products or companies I am about to shower with praise. I just get really passionate about my tools.  
  
## Note-Taking and Organization  
When it comes to organizing my research and task management, I have jumped around between `Evernote`, `OneNote`, and even `Apple Reminders` (I know…). But until recently, nothing was really satisfying my need to keep a centralized brain-dump that easily kept track of all the things my ADHD brain tries to forget. In walks, `Obsidian`. If you’ve never heard of it here’s a link to their [website](https://obsidian.md). I have only been using it for a few months and I’m just now delving into the really awesome stuff that makes it a truly one-of-a-kind app.