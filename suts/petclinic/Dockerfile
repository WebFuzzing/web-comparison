FROM eclipse-temurin:25-jdk
WORKDIR /app

# Copy JaCoCo agent from parent folder
COPY jacocoagent.jar /app/jacocoagent.jar

COPY petclinic/target/*.jar app.jar

# Attach JaCoCo agent to JVM
ENTRYPOINT ["java", \
  "-javaagent:/app/jacocoagent.jar=output=tcpserver,address=*,port=6300", \
  "-jar", "app.jar"]

EXPOSE 6300
