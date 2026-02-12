### Run the application

The docker scripts to execute the frontend and backend application are given in run.sh

First need to run the backend docker container by executing

docker run -p 9966:9966 springcommunity/spring-petclinic-rest

The docker image for the frontend angular application is pre-built in the vm. To rebuild the image first move to the angular project directory and run
'docker build -t spring-petclinic-angular:latest .'

To run the image in detached mode 
`docker run --rm -d -p 8080:8080 spring-petclinic-angular:latest`


The application shall run at the address:

`http://localhost:8080`




### Stop application and remove container
Stop all running containers in detaiched mode by executing the script from the root project directory
`docker-compose down`
