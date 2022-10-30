---  
title: README  
created: 2022-10-21 04:59  
topic: Introduction to Shape-Share and Formatting of this Tutorial.  
tags:  
  - tutorial  
  - shape-share  
share: true  
---  
  
  
# Introduction to Shape-Share  
  
##  About this Tutorial  
  
This tutorial will take you from configuring your own **Docker** server and database to a fully building a fully interactive **PHP** web application with tons of fun functionality. All tutorial files can be found in the [docs](/docs/) folder at the root of this repository and will be numerically ordered for your convenience. Follow along or pick and choose where to jump in. This project is about sharing what I’ve learned through nearly a year of trial and error and hopefully helping someone make less mistakes than I did. It’s about taking a project from zero to development and maybe even through to production.   
  
When I first started out, I had no idea where to start and I struggled to find a single resource that could take me through the whole process of web development. That’s what I hope this repository will eventually become. I will be providing some of the better references I was able to find as footnotes where helpful and I will also do my best to explain why I did things a certain way or at the very least warn you of how not to do it. I am a student and by no means an authority on any of the concepts or languages used in this project. I am simply documenting my journey should it be useful to anyone else at a similar stage in their programming career.   
  
## What is Shape-Share?  
  
**Shape-Share** is a concept I created with the sole purpose of exploring all facets of web development in the broadest sense. I wanted something I could expand upon without being limited by the what of it all. It’s less about shapes as it is about universality. It was also important to me that the project could be filled with content that I could easily render on my own without much fuss. What’s easier than pulling up **Paint** and drawing up a purple triangle? Plus, there’s no risk of copyright infringement or need to upload personal content.   
  
The first iteration of this project can be found in my other repository **Shape_Search**. With that project I designed a members-only platform where users could make a free account and upload images of shapes and interact with other users. This time around, I am going to focus on delineating between content creators and the casual subscriber. Creators will have access to a dashboard where they can upload images and interact with fans while the casual user will simply collect and react to their favorite images.  
  
From there I’ll likely implement subscriptions to creators, the ability to comment, and anything else that sounds interesting or challenging. A key focus throughout this project will be to implement a safe and secure web application, not just a hacker’s fun box with gaping security holes. I became obsessed with web security throughout **Shape_Search** and most of my time was spent running in circles with how much there is to consider when hardening a website. It’s no joke.  I plan to have the application pentested routinely to help identify trouble areas and maintain a DevSecOps mindset. So get to know an ethical hacker and let them practice on your project as well!  
  
Let’s get started!  
  
## Formatting Conventions in this Tutorial  
  
Before we get going, here are some typographical conventions I will be using throughout the tutorial. Notice that *italics* denote the project file system that you will be implementing on your host computer and [linked]() text is a reference to a file within this repository specifically. Most of this project will be containerized and not require you to store or install anything on your host computer itself but some secrets and security keys will need to be handled outside of the project directory for obvious reasons.  
  
| Type of Text | Corresponding Information            |  
| ------------ | ------------------------------------ |  
| **bold**      | Application, Language, or Tool Name               |  
| *italic*      | Project Filename or Path to File on Host Computer |  
| `inline code` | Code Snippet                                      |  
  
```  
A code block will either display instructions to run or a file to be copied.  
```  
[Linked Text Will Navigate You Around This Repository  OR Link to an External Site]()  
  
> :memo: Note  
> This is a note  
  
> :warning: Warning!  
> This is a warning  
  
## Some Considerations  
I am coding on a Macbook Pro and thus some of this tutorial will be Mac-specific. I will try to include resources for other operating systems where necessary but once you get your **Docker** container up and running, we will essentially be building in a **Linux** environment anyways.   
  
The remainder of this document will detail my coding setup for anyone interested and will of course be entirely subjective. I am pretty proud of my go to tools but they are by no means the only way to implement this project. Skip ahead to the [Getting Started](/0.0_Getting_Started) document if you are ready, otherwise let’s talk about tools and apps that make coding more enjoyable and productive.  
  
> :memo: Note:  
> I am not compensated in anyway by the products or companies I am about to shower with praise. I just get really passionate about my tools.  
  
## Note-Taking and Organization  
  
When it comes to organizing my research and task management, I have jumped around between Evernote, OneNote, and even Apple Reminders (I know…). But until recently, nothing was really satisfying my need to keep a centralized brain-dump that easily kept track of all the things my ADHD brain tries to forget. In walks, **Obsidian**. If you’ve never heard of it here’s a link to their [website](https://obsidian.md). It’s free though I pay for syncing purposes. I have only been using it for a few months and I’m just now delving into the really awesome stuff that makes it a truly one-of-a-kind app.  
  
First off, it’s a note-taking app written entirely in their own flavor of Markdown language. Similar to Github Markdown, **Obsidian** allows you to format your notes using common Markdown notation and configurable Hotkeys. When I say it’s customizable, I mean there is an open API that supports custom **CSS** as well **Javascript** commands. If you’re like me and don’t know what the heck to do with all that, the **Obsidian** community has you covered. They churn out gorgeous typesetting themes and plugins that make this app a must have for project (or school) management.   
  
I will definitely recommend a ton of great plugins that have improved my workflow tremendously; but what really makes **Obsidian** shine comes native with the app. You can reference other notes within your “Vault” and even link to specific parts of other notes much in the same way you create a standard hyperlink. Even better, when creating a link to another note, a drop-down menu of all the notes in your Vault helps you select the correct one. This is great for rendering clickable todo lists that take you right to the corresponding note or keeping related notes together for bigger projects. You can also link YouTube videos, pdfs, and other media directly into the note and have tons of options for annotating those files in-app.   
  
A big feature that I just became aware of is the optional metadata frontmatter that you can append to all of your notes. Written in **YAML**, you can supply as much metadata as you want, including the title, created date, updated date, a summary, whatever you like. But the real jewel here is active tagging functionality. Instead of making a physical link to related notes, just give them all a shared tag and they are easily searchable. One of the more obscure features is a literal mind graph of all your files and how they are related to one another. With the graph you can easily see orphaned notes and make connections between files that you never considered. I don’t use it much but it might be a cool feature for you.  
  
Another big win for **Obsidian** is that it is not hosted on some cloud server. All your files are kept securely in folders on your local filesystem. All you do is create a Vault folder containing all your markdown notes and they are accessible in the app. Plus, it uses **Prism** syntax highlighting for code snippets and with the help of a plugin you can even run CLI commands from inside the app. Since it’s written in markdown language, you can also easily publish your notes to the hosting platform of your choice. I’m writing this tutorial in it right now. With all that out of the box, I was already sold. But there’s so much more that **Obsidian** can do for you.  
  
I was procrastinating doing homework the other day and totally squirreled over all the plugins that **Obsidian** has to offer. I will list all the plugins I currently have installed and deep-dive into the ones I use everyday.  
  
### Obsidian Plugins  
```json TI:"Obsidian Plugins Installed"  
[  
  "better-word-count",  
  "obsidian-kanban",  
  "recent-files-obsidian",  
  "templater-obsidian",  
  "dataview",  
  "obsidian-admonition",  
  "cm-editor-syntax-highlight-obsidian",  
  "obsidian-icon-folder",  
  "obsidian-tasks-plugin",  
  "obsidian-frontmatter-tag-suggest",  
  "obsidian-tabs",  
  "obsidian-html-tags-autocomplete",  
  "obsidian-metatable",  
  "obsidian-dynamic-toc",  
  "obsidian-better-code-block",  
  "tag-wrangler",  
  "fantasy-calendar",  
  "highlightr-plugin",  
  "obsidian-style-settings",  
  "omnisearch",  
  "obsidian-smart-typography",  
  "obsidian-dictionary-plugin",  
  "find-unlinked-files",  
  "oz-image-plugin",  
  "quick-latex",  
  "obsidian-auto-link-title",  
  "cm-typewriter-scroll-obsidian",  
  "obsidian-hotkeys-for-templates",  
  
]  
```  
#### Templater  
Definitely my top used plugin, is [Templater](https://github.com/SilentVoid13/Templater) which allows you to, you guessed it, make templates! With this plugin, you can automatically create a new note outfitted with all the frontmatter, headings, and content that you need. You can also dynamically input tags and titles upon note creation by issuing a system prompt command. I have templates for todo lists, research, notes, and assignments in my school Vault. I slapped a Hotkey onto the insert template command and bang, I can start a new assignment in seconds.  
  
#### Kanban  
I wasn’t hip to what a [Kanban](https://github.com/mgmeyers/obsidian-kanban) board was when I started **Obsidian** but I’m certainly a fan now. Kanban boards allow you to create workflows by moving cards to different staging boards depending on what you’re actively working on. In **Obsidian** you, simply create your boards (in my school Vault I have: Assignments, Reading, Lectures, In-Progress, Completed, and Not Finished ) and then add tasks (cards) to the boards as you see fit. You can also link notes directly to your Kanban cards so that they are just a click away. Due dates and tagging are also supported.  
  
#### Admonition  
[Admonition](https://github.com/valentine195/obsidian-admonition) is one of those, you don’t know you need it until you have it kind of plugins. Admonitions are callouts with pre-formatted (or customized) icons and colors that can help you separate content from the main body of your notes. The presets are all I really need, but you can put quotes, summaries, warnings, or anything you like in these distinct callouts.  
  
#### Dynamic Table of Contents  
Much like the auto-generated table of contents in GitHub,  [Dynamic TOC](https://github.com/Aidurber/obsidian-plugin-dynamic-toc) adds a clickable and auto-updating table of contents to your notes based on the headings used. I added this command to my notes template so I don’t even have to think about it anymore. Then when my note is complete I have a basic outline and can jump to any part of the file.  
  
#### Better Code Block  
The [Obsidian Better Code Block](https://github.com/stargrey/obsidian-better-codeblock) plugin adds line numbers, the name of the language specified, optional title, highlight line number, and fold default to your code blocks.   
  
#### Fantasy Calendar  
This Calendar plugin is obviously intended for other purposes but it is the best calendar plugin available. [Fantasy Calendar](https://github.com/fantasycalendar/obsidian-fantasy-calendar) lets you insert events and link notes directly to their due date without much trouble. I haven’t figured out how to integrate this with my Kanban boards yet but that functionality would definitely improve the plugin. For now I use it for tracking tests and other events, assigning each course I’m taking to a distinct color.  
  
#### GitHub Publisher  
There are other plugins to publish to your preferred static web hosting site but I prefer [GitHub Publisher](https://github.com/ObsidianPublisher/obsidian-github-publisher) because I can add my markdown READMEs to my pre-existing repositories. To push to GitHub I just use a Hotkey and it shares all updated files to my repository. You can designate which files to share and which to ignore in your frontmatter. The only downside is, a lot of the **Obsidian**-specific formatting and plugins do not get converted to GitHub flavored markdown so its not ideal but most of the basic syntax is shared by the two platforms.  
  
#### Tag-Wrangler  
[Tag Wrangler](https://github.com/pjeby/tag-wrangler) is aptly named as it allows you to query tags found in your frontmatter or anywhere else in your notes even if you misspelled one or two along the way. You can blanket rename any tags and every instance updates in their respective notes. You can also make whole pages dedicated to a single or multiple tags with all the links to notes sharing that tag name. Searches are case-insensitive and the plugin will warn you if you attempt to rename a tag that would have merge conflicts.  
  
#### Find Unlinked Files  
This plugin is a must if you, like me, get started with basic **Obsidian** and then down the road discover new functionality that you want to retroactively fit to all your older notes. I just started using frontmatter and tagging to a serious degree, so in order to keep all my old notes relevant I needed a way to update those notes with my new system. [Find Unlinked Files](https://github.com/Vinzent03/find-unlinked-files/blob/main/README.md) is a simple note parser that can help you find orphaned notes, notes without tags, empty notes, and broken links. Never leave a note behind!   
  
#### Honorable Mentions  
- [Advanced Tables](https://github.com/tgrosinger/advanced-tables-obsidian) - takes the hassle out of creating markdown tables  
- [Typewriter Scroll](https://github.com/deathau/cm-typewriter-scroll-obsidian) - keeps the view centered in the editor  
- [Dictionary ](https://github.com/phibr0/obsidian-dictionary) - great if you’re taking a language course and need to allow autocorrect for languages than the default  
- [Frontmatter Tag Suggest](https://github.com/jmilldotdev/obsidian-frontmatter-tag-suggest) - autocomplete tags in frontmatter  
- [Icon Folder](https://github.com/FlorianWoelki/obsidian-icon-folder) - add icons to your folders  
- [Tasks](https://github.com/obsidian-tasks-group/obsidian-tasks) - create advanced tasks with loads of metadata (I don’t use it but it’s popular)  
- [Dataview](https://github.com/blacksmithgu/obsidian-dataview) treat your Vault like a database with advanced queries that bring your notes together  as organized, sortable data tables  
  
## Coding and Text Editing  