package parsers.model.files.graph.elements;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import utils.DefaultValues;

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;

/**
 * @author raphaelrodrigues
 */
public class Serializer {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    public void serializePath(Path path) {
        System.out.println("PATH to write is: " + path + " write ");
        FileOutputStream out = null;
        try {
            out = new FileOutputStream("card.out");
            ObjectOutputStream oos = new ObjectOutputStream(out);
            oos.writeObject(path);
            oos.flush();
        } catch (Exception e) {
            logger.error("Problem serializing: " + e);
        } finally {
            org.apache.commons.io.IOUtils.closeQuietly(out);
        }

    }

    public Path deserializePath() {
        Path path = null;
        FileInputStream in = null;
        try {
            in = new FileInputStream("card.out");
            ObjectInputStream ois = new ObjectInputStream(in);
            path = (Path) (ois.readObject());
        } catch (Exception e) {
            logger.error("Problem serializing: " + e);
        } finally {
            org.apache.commons.io.IOUtils.closeQuietly(in);
        }
        return path;
    }
}
