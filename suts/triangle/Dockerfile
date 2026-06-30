FROM eclipse-temurin:21-jdk
WORKDIR /app
ADD https://repo1.maven.org/maven2/org/jacoco/org.jacoco.agent/0.8.11/org.jacoco.agent-0.8.11-runtime.jar /app/jacocoagent.jar
COPY target/*.jar app.jar
ENTRYPOINT ["java", \
  "-javaagent:/app/jacocoagent.jar=output=tcpserver,address=*,port=6300", \
  "-jar", "app.jar"]
EXPOSE 8080
EXPOSE 6300