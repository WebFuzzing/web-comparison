FROM jboss/wildfly:latest

WORKDIR /opt/jboss/wildfly

COPY target/applicationPetstore.war /opt/jboss/wildfly/standalone/deployments/

EXPOSE 8080 9990

CMD ["/opt/jboss/wildfly/bin/standalone.sh", "-b", "0.0.0.0"]