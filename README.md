# Chess-backend

## Installing:
To install project execute following commands:
```
git clone https://github.com/TayaPenskaya/chess-backend
cd chess-backend
composer install
```
Install mysql and execute following commands:
```
sudo mysql -u root
USE mysql;
CREATE USER 'player'@'127.0.0.1' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON * . * TO 'player'@'127.0.0.1';
FLUSH PRIVILEGES;
```
## Start:
```
composer run-script start
```
## Test:
```
composer run-script test
```
## How to play(routes):
```
/start-game                      - to start game ^_^
/game/{id}                       - something about game with such id
/game/{id}/move-color            - current move color
/game/{id}/board                 - board of the game with such id
/game/{id}/status                - the game is over or is still going on
/game/{id}/history               - history of moves
/game/{id}/make-move/{from}/{to} - make move from-to
```
