/* SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; */

CREATE TABLE company (
  ID int CHECK (ID > 0) NOT NULL,
  company varchar(255) NOT NULL,
  contact varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(64) NOT NULL,
  mobile varchar(64) NOT NULL,
  url varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  zip varchar(16) NOT NULL,
  city varchar(255) NOT NULL,
  country varchar(255) NOT NULL,
  state varchar(255) NOT NULL,
  "desc" text NOT NULL
) ;

CREATE TABLE customers_assigned (
  ID int NOT NULL,
  customer int NOT NULL,
  project int NOT NULL
) ;

CREATE TABLE files (
  ID int NOT NULL,
  name varchar(255) NOT NULL DEFAULT '',
  "desc" varchar(255) NOT NULL DEFAULT '',
  project int NOT NULL DEFAULT '0',
  milestone int NOT NULL DEFAULT '0',
  "user" int NOT NULL DEFAULT '0',
  added varchar(255) NOT NULL DEFAULT '',
  datei varchar(255) NOT NULL DEFAULT '',
  type varchar(255) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  folder int NOT NULL,
  visible text NOT NULL
) ;

CREATE TABLE files_attached (
  ID int CHECK (ID > 0) NOT NULL,
  file int CHECK (file > 0) NOT NULL DEFAULT '0',
  message int CHECK (message > 0) NOT NULL DEFAULT '0'
) ;

CREATE TABLE log (
  ID int NOT NULL,
  "user" int NOT NULL DEFAULT '0',
  username varchar(255) NOT NULL DEFAULT '',
  name varchar(255) NOT NULL DEFAULT '',
  type varchar(255) NOT NULL DEFAULT '',
  action int NOT NULL DEFAULT '0',
  project int NOT NULL DEFAULT '0',
  datum varchar(255) NOT NULL DEFAULT ''
) ;

CREATE TABLE messages (
  ID int NOT NULL,
  project int NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  text text NOT NULL,
  tags varchar(255) NOT NULL,
  posted varchar(255) NOT NULL DEFAULT '',
  "user" int NOT NULL DEFAULT '0',
  username varchar(255) NOT NULL DEFAULT '',
  replyto int NOT NULL DEFAULT '0',
  milestone int NOT NULL
) ;

CREATE TABLE messages_assigned (
  ID int NOT NULL,
  "user" int NOT NULL,
  message int NOT NULL
) ;

CREATE TABLE milestones (
  ID int NOT NULL,
  project int NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  "desc" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  "end" varchar(255) NOT NULL DEFAULT '',
  status smallint NOT NULL DEFAULT '0'
) ;

CREATE TABLE milestones_assigned (
  ID int NOT NULL,
  "user" int NOT NULL DEFAULT '0',
  milestone int NOT NULL DEFAULT '0'
) ;

CREATE TABLE projectfolders (
  ID int CHECK (ID > 0) NOT NULL,
  parent int CHECK (parent > 0) NOT NULL DEFAULT '0',
  project int NOT NULL DEFAULT '0',
  name text NOT NULL,
  description varchar(255) NOT NULL,
  visible text NOT NULL
) ;

CREATE TABLE projekte (
  ID int NOT NULL,
  name varchar(255) NOT NULL DEFAULT '',
  "desc" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  "end" varchar(255) NOT NULL DEFAULT '',
  status smallint NOT NULL DEFAULT '0',
  budget double precision NOT NULL DEFAULT '0'
) ;

CREATE TABLE projekte_assigned (
  ID int NOT NULL,
  "user" int NOT NULL DEFAULT '0',
  projekt int NOT NULL DEFAULT '0'
) ;

CREATE TABLE roles (
  ID int NOT NULL,
  name varchar(255) NOT NULL,
  projects text NOT NULL,
  tasks text NOT NULL,
  milestones text NOT NULL,
  messages text NOT NULL,
  files text NOT NULL,
  chat text NOT NULL,
  timetracker text NOT NULL,
  admin text NOT NULL
) ;

CREATE TABLE roles_assigned (
  ID int NOT NULL,
  "user" int NOT NULL,
  role int NOT NULL
) ;

CREATE TABLE settings (
  ID int NOT NULL,
  settingsKey varchar(50) NOT NULL,
  settingsValue varchar(50) NOT NULL
) ;

CREATE TABLE tasklist (
  ID int NOT NULL,
  project int NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  "desc" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  status smallint NOT NULL DEFAULT '0',
  access smallint NOT NULL DEFAULT '0',
  milestone int NOT NULL DEFAULT '0'
) ;

CREATE TABLE tasks (
  ID int NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  "end" varchar(255) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  text text NOT NULL,
  liste int NOT NULL DEFAULT '0',
  status smallint NOT NULL DEFAULT '0',
  project int NOT NULL DEFAULT '0'
) ;

CREATE TABLE tasks_assigned (
  ID int NOT NULL,
  "user" int NOT NULL DEFAULT '0',
  task int NOT NULL DEFAULT '0'
) ;

CREATE TABLE timetracker (
  ID int NOT NULL,
  "user" int NOT NULL DEFAULT '0',
  project int NOT NULL DEFAULT '0',
  task int NOT NULL DEFAULT '0',
  comment text NOT NULL,
  started varchar(255) NOT NULL DEFAULT '',
  ended varchar(255) NOT NULL DEFAULT '',
  hours double precision NOT NULL DEFAULT '0',
  pstatus smallint NOT NULL DEFAULT '0'
) ;

CREATE TABLE "user" (
  ID int NOT NULL,
  name varchar(255) DEFAULT '',
  email varchar(255) DEFAULT '',
  tel1 varchar(255) DEFAULT NULL,
  tel2 varchar(255) DEFAULT NULL,
  pass varchar(255) DEFAULT '',
  company varchar(255) DEFAULT '',
  lastlogin varchar(255) DEFAULT '',
  zip varchar(10) DEFAULT NULL,
  gender char(1) DEFAULT '',
  url varchar(255) DEFAULT '',
  adress varchar(255) DEFAULT '',
  adress2 varchar(255) DEFAULT '',
  state varchar(255) DEFAULT '',
  country varchar(255) DEFAULT '',
  tags varchar(255) DEFAULT '',
  locale varchar(6) DEFAULT '',
  avatar varchar(255) DEFAULT '',
  rate varchar(10) DEFAULT NULL
) ;

