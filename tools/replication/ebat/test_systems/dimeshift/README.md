Page objects for the dimeshift application. Scripts to run test generation experiments.

### Run the application

Execute the Bash script to initialize the Docker image containing the web application:

`./run-docker.sh`

Inside the container, start the application server and MySQL:

`./run-services-docker.sh`

The application shall run at the address:

`http://localhost:3001`


### Stop application and remove container
Type `^C` in the terminal to stop the backend container. `docker rm $(docker ps -aq)` will remove all stopped containers.
