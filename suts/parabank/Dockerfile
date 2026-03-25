# Stage 1: Build with Maven
FROM maven:3.9-eclipse-temurin-21 AS builder
WORKDIR /app
COPY parabank/pom.xml .
COPY parabank/src ./src
RUN mvn clean package -DskipTests

# Stage 2: Run with Tomcat
FROM tomcat:10.1.49-jre21-temurin-noble
ARG TOMCAT_HOME=/usr/local/tomcat
USER root:root

# Copy JaCoCo agent
COPY jacocoagent.jar /app/jacocoagent.jar

# Copy WAR from build stage
COPY --from=builder /app/target/parabank-5.0.0-SNAPSHOT.war ${TOMCAT_HOME}/webapps

# Unzip WAR
RUN apt update && \
    apt install -y unzip && \
    unzip ${TOMCAT_HOME}/webapps/parabank-5.0.0-SNAPSHOT.war -d ${TOMCAT_HOME}/webapps/parabank && \
    rm ${TOMCAT_HOME}/webapps/parabank-5.0.0-SNAPSHOT.war

# Attach JaCoCo agent to Tomcat JVM
ENV JAVA_TOOL_OPTIONS="-javaagent:/app/jacocoagent.jar=output=tcpserver,address=*,port=6300"

EXPOSE 8080
EXPOSE 61616
EXPOSE 9001
EXPOSE 6300