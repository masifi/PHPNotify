# PHPNotificaiton
Realtime ZeroMQ Push notificcaiton Demo using Ratchet, React and ZeroMQ for PHP

# ZeroMQ Installation
## 1. install and build php-zmq extensions
Go to extension directory in XAMPP/MAMP then try to download and build the following `php-zmq` package:
1. `git clone https://github.com/zeromq/php-zmq.git`
2. `cd php-zmq`
3. `phpize && ./configure`
4. `make && make install`

## 2. Enable ZeroMQ extension for PHP
To enable ZeroMQ extension add the following line in `php.ini`.

`extension=zmq.so` for mac/linux systems

`extension=zmq_X.dll` for windows system

## 3. Install ZeroMQ pakcage using composer
`composer require react/zmq`

# Running
1. Clone this repo in your root server `WWW` folder.
2. There is a `PHP` script called `bin/pusher.php` try to run this in the `terminal`.
3. Then Access the `index.php` through the web server.
4. Congradulation you're done!
