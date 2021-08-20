# PROCEDURAL

Welcome to Procedural. This app is a full-responsive & installable app for 
managing your process. 

##Demo

A demo is available at [https://procedural.a-messagerdiaz.fr](https://procedural.a-messagerdiaz.fr)

Admin role credentials:\
&nbsp;&nbsp;&nbsp;admin@demo.com/admin

User role credentials:\
&nbsp;&nbsp;&nbsp;user@demo.com/user

##Installation

1- Download Procedural for this repository\
2- Cd Procedural/src\
3 - Nano .env.local
4- Composer update \
5- Npm install\
6- Npm run build\
7- Php bin/console app:install\
8- Follow the instructions\
9- Enjoy

##Configuration

The installations command will create your first Administrator. In order to use 
properly the app, you will need to create at lest one company, one process and 
two states.

About the state, don't forget to set one of your state as 'Final State'. This variable
is required to close a process. 

##Future improvements

* Create a documentation website
* Improve UX/UI design
* Add Recaptcha V3

##Development
### Bashrc
In order to simplify the use of docker, please enter the following line in your bashrc (sudo nano ~/.bashrc):
* alias dc='docker-compose --env-file .env $*'
* alias php='docker-compose -f ../docker/docker-compose.yml exec php-fpm php $*'
* alias composer='docker-compose -f ../docker/docker-compose.yml exec php-fpm composer $*'

*Note: Those alias are totally optionals, because they override your local commands (eg: composer)*

### Composer

Start to make:

* cd &lt;project dir&gt;/src
* composer update

If you need to use composer like "composer require ...", you must be in the <project dir>/src .

### Npm

Start to make:

* cd &lt;project dir&gt;/src
* npm install (or *yarn install*)

If you need to use composer like "npm install ...", you must be in the <project dir>/src .

*Note: Node is not brought with docker images, it should be installed inside your WSL. More informations about installing node via [Nodesource](https://github.com/nodesource/distributions)*

### Docker
To start Docker, please make:

* cd &lt;project dir&gt;/docker
* docker-compose up -d  (or *dc up -d* if you use aliases)

To stop Docker, please make:
* docker-compose down (or *dc down* if you use aliases)

### Contributing

Pull request are welcome.
