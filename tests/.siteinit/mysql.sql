create database '{{user}}';
grant all on user.* to '{{user}}'@'localhost' identified by '{{password}}';
