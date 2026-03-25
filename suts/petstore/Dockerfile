FROM jboss/wildfly:latest

WORKDIR /opt/jboss/wildfly

USER root

# Copy JaCoCo agent from build context root
COPY jacocoagent.jar /opt/jboss/jacocoagent.jar

# Copy application
COPY petstore/target/applicationPetstore.war /opt/jboss/wildfly/standalone/deployments/

RUN touch /opt/jboss/wildfly/standalone/deployments/applicationPetstore.war.dodeploy

ENV JAVA_TOOL_OPTIONS="-javaagent:/opt/jboss/jacocoagent.jar=output=tcpserver,address=*,port=6300,append=true"

EXPOSE 8080 9990 6300

CMD ["/opt/jboss/wildfly/bin/standalone.sh", "-b", "0.0.0.0"]