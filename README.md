#RPG Game REST API

Installation:
```
 php bin/console doctrine:schema:update --force
 php bin/console doctrine:database:import data/data.sql
```

The RPG Game provided as REST API, so its endpoints ready to be integrated
into some front-end application. And you can have in this way Fully- functional game
with UI. Or, you can use Guzzle client (or any curl client) to have
command line application, using end- point calls.
Enjoy :-)

Endpoint of the game reflect full game flow process

Requirements to run game using API endpoints 

- You should have Postman installed on your system.

Steps to run game

- [Import API data to Postman](postman/Game API.postman_collection.json)
- look into docs for endpoints in address /api-docs/
