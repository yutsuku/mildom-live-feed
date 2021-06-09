# Mildom live feed
Simple ATOM feed generator for mildom.com - a broadcasting service.

## Getting Started
These instructions will get you a copy of the project up and running on your
local machine for development and testing purposes. See deployment for notes on 
how to deploy the project on a live system.

## Installation
* Get [Docker](https://www.docker.com/)
* Copy `.env.example` as `.env`
* Change values in `.env` file as neccessary

## Deployment
* Run the following command

```
docker-compose up -d
```

It will start new container named `mildom-live-feed` in de-attached 
mode.
For more details refer to
[Docker docs](https://docs.docker.com/compose/reference/up/).
